<?php
// Start the session
session_start();

// Include your database connection file
include_once "../../Controller/Database/Database.php"; 
$database = new Database(); 
$conn = $database->connect();


$product_id = $_POST['id'] ?? null; 
// Fetch the product from the database using the product_id
$query = "SELECT * FROM products WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the product exists
if (!$product) {
    $_SESSION['error'] = "Product not found.";
    header("Location: products.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['id'];
    $user_name = $_SESSION['first_name']; 
    
    // Fetch user details (including city and province)
    $query = "SELECT first_name, email, phone_number, street_address, baranggay, city, province FROM users WHERE id = :id LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        $user_city = $user['city'];
        $user_province = $user['province'];
    } else {
        echo json_encode(['success' => false, 'message' => 'User details not found.']);
        exit();
    }

    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    // Check if the product has enough stock
    if ($quantity > $product['quantity']) {
        echo json_encode(['success' => false, 'message' => 'Not enough stock available.']);
        exit();
    }

    if (isset($product['price']) && !is_null($product['price'])) {
        // Begin transaction
        $conn->beginTransaction();
        
        // Insert into buy_now
        $add_cart_query = "INSERT INTO buy_now (user_id, first_name, price, product_image, product_name, product_quantity, product_type, city, province, product_id) 
        VALUES (:user_id, :first_name, :price, :image, :name, :quantity, :type, :city, :province, :product_id)";
        
        $stmt = $conn->prepare($add_cart_query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':first_name', $user_name);
        $stmt->bindParam(':price', $product['price'], PDO::PARAM_STR);
        $stmt->bindParam(':image', $product['image']);
        $stmt->bindParam(':name', $product['name']);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':type', $product['type']);
        $stmt->bindParam(':city', $user_city);
        $stmt->bindParam(':province', $user_province);
        $stmt->bindParam(':product_id', $product['id'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Update the product quantity in the products table
            $update_query = "UPDATE products SET quantity = quantity - :quantity WHERE id = :product_id";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $update_stmt->bindParam(':product_id', $product['id'], PDO::PARAM_INT);

            if ($update_stmt->execute()) {
                // Commit transaction
                $conn->commit();
                // Return success response
                echo json_encode(['success' => true]);
                exit();
            } else {
                $conn->rollBack(); // Rollback if update fails
                echo json_encode(['success' => false, 'message' => 'Failed to update product quantity.']);
                exit();
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add product to cart.']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Product price is not set or is null.']);
        exit();
    }
}
