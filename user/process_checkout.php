<?php 
// Start the session
session_start();

// Include your database connection file
include_once "../../Controller/Database/Database.php"; 
$database = new Database(); 
$conn = $database->connect();

// Check if user is logged in
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'user') {
    $_SESSION['error'] = "Access denied.";
    header("Location: signin.php");
    exit();
}

// Fetch user ID
$user_id = $_SESSION['id'];

// Fetch user details
$query = "SELECT first_name, email, phone_number, street_address, baranggay, city, province FROM users WHERE id = :id LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get payment method from POST data
    $payment_method = $_POST['payment_method'];

    // Fetch cart items for the logged-in user
    $query = "SELECT * FROM cart WHERE user_id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Insert each cart item as an order
    foreach ($cart_items as $item) {
        if (isset($item['product_id'])) { // Ensure product_id is present
            $orderQuery = "INSERT INTO order_items (user_id, product_id, quantity, price, payment_method) 
                           VALUES (:user_id, :product_id, :quantity, :price, :payment_method)";
            $orderStmt = $conn->prepare($orderQuery);
            $orderStmt->execute([
                ':user_id' => $user_id,
                ':product_id' => $item['product_id'], 
                ':quantity' => $item['product_quantity'],
                ':price' => $item['price'],
                ':payment_method' => $payment_method
            ]);
        } 
    }

    // Delete cart items for the user
    $deleteQuery = "DELETE FROM cart WHERE user_id = :user_id";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $deleteStmt->execute();

    // Redirect or show success message
    $_SESSION['success'] = "Your order has been placed successfully!";
    header("Location: view-product.php");
    exit();
}
?>
