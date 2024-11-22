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

}

// Ensure that the 'id' and 'first_name' keys exist in the session
$user_id = $_SESSION['id'] ?? null; // Get user ID from session
$user_name = $_SESSION['first_name'] ?? null; // Get user's first name from session

// If session data is missing, show an error
if (!$user_id || !$user_name) {
    $_SESSION['error'] = "User session data is incomplete.";

}

// Fetch the product from the database using the product_id
$product_id = $_POST['id'] ?? null; 
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    // Fetch user details (including city and province)
    $query = "SELECT first_name, email, phone_number, street_address, baranggay, city, province FROM users WHERE id = :id LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->execute(['id' => $user_id]);

    // Fetch the user details
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
        
        // Insert into cart
        $add_cart_query = "INSERT INTO cart (user_id, first_name, price, product_image, product_name, product_quantity, product_type, city, province, product_id) 
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
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

    <style>
        /* Custom Button Styles */
        .swal2-confirm {
            background-color: #eab308 !important;
            color: white !important;
        }
        .swal2-confirm:hover {
            background-color: #d7a206 !important;
        }
        /* Custom "Buy Now" button color */
        .buy-now-btn {
            background-color: #eab308;
        }
        .buy-now-btn:hover {
            background-color: #d7a206;
        }
        #success-modal {
            display: none; /* Hidden by default */
        }
    </style>
</head>
<body class="bg-gray-100">
<?php include "../includes/header.php"; ?> 
<br><br>



<main class="p-6">
<form method="POST" action="" id="addToCartForm">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
        <div class="max-w-2xl mx-auto border rounded p-4 bg-white mt-40 shadow-lg flex flex-col lg:flex-row items-center">
            <!-- Left side: Image -->
            <div class="lg:w-1/3 flex justify-center lg:justify-start mb-4 lg:mb-0">
                <div class="overflow-hidden rounded-md w-64 h-64 border border-gray-300">
                    <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-full object-cover">
                </div>
            </div>

            <!-- Right side: Product details -->
            <div class="lg:w-2/3 lg:pl-6 text-left">
            <?php
$product_id = $product['id']; // Ensure this ID is correct
$product_type = $product['type']; // Use the correct key for product type

// Update the query to use the correct product_type
$rating_query = "SELECT AVG(rating) as average_rating, COUNT(*) as review_count FROM reviews WHERE product_type = :product_type";
$stmt = $conn->prepare($rating_query);
$stmt->bindParam(':product_type', $product_type, PDO::PARAM_STR);

try {
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $average_rating = $result['average_rating'];
    $review_count = $result['review_count'];
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$average_rating = $average_rating ? round($average_rating) : 0; 

?>

<h2 class="text-3xl font-bold mb-2">
    <?php echo htmlspecialchars($product['name']); ?> <span class="text-gray-500">(PRIMERA)</span>
</h2>
<p class="text-xl text-green-700 font-semibold mb-2">₱<?php echo ($product['price']); ?></p>

<!-- Render the star rating -->
<div class="flex items-center mb-4">
    <?php for ($i = 1; $i <= 5; $i++): ?>
        <span class="<?php echo $i <= $average_rating ? 'text-yellow-500' : 'text-gray-300'; ?>">
            ★
        </span>
    <?php endfor; ?>
    <span class="ml-2 text-gray-500">(<?php echo $review_count; ?> reviews)</span>
</div>


                <!-- Quantity Section -->
                <div class="flex items-center mb-4">
                    <label for="quantity" class="mr-3 text-gray-600">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" class="w-16 border border-gray-300 rounded px-2 py-1 text-center" value="1" min="1" required>
                </div>
                <form method="POST" action="" id="addToCartForm">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
       
                <input type="hidden" name="buy_now" id="buyNowInput" value="0"> 
                <div class="flex space-x-4 mb-4">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
               
                <button type="submit" name="add_to_cart" class="bg-green-600 text-white px-5 py-2 rounded hover:bg-green-500">Add to Cart</button>
                <button type="button" class="buy-now-btn text-white px-5 py-2 rounded" id="buyNowBtn">Buy Now</button>
                </div>
                </form>

                <!-- Social Icons (Optional: Facebook, Share, etc.) -->
                <!-- <div class="flex space-x-4 text-gray-500 text-xl">
                    <a href="#" class="hover:text-gray-800"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="hover:text-gray-800"><i class="fas fa-share"></i></a>
                </div> -->
            </div>
        </div>
    </form>

    <div id="success-modal" class="fixed inset-0 bg-blur full-screen mt-60   flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow-lg max-w-md w-full mx-auto"> <!-- Added max-width and width -->
        <h2 class="text-xl font-bold mb-4 text-center">Order Success!</h2> <!-- Centered text -->
        <p class="text-center">Your order has been placed successfully.</p> <!-- Centered text -->
        <div class="flex justify-center mt-4"> <!-- Center the button -->
            <button class="bg-blue-500 text-white px-4 py-2 rounded" onclick="hideSuccessModal()">OK</button>
        </div>
    </div>
</div>


    <!-- Description Section -->
    <!-- <div class="max-w-5xl mx-auto mt-4 p-4 border-t border-gray-300">
        <h3 class="text-xl font-semibold">Description</h3>
        <p class="text-gray-600 mt-2"> -->
            <!-- Add product description here -->
        </p>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   $(document).ready(function() {
    // Handle Add to Cart form submission
    $('#addToCartForm').submit(function(event) {
        event.preventDefault(); 

        const productId = $('input[name="id"]').val();
        const quantity = $('#quantity').val();

        $.ajax({
            type: "POST",
            url: window.location.href, // Posts to the current URL
            data: {
                id: productId,
                quantity: quantity,
                add_to_cart: true
            },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    showSuccessModal(); // Call the function to show the modal
                } else {
                    alert(response.message); // Show the error message
                }
            },
            error: function() {
                alert("An error occurred. Please try again.");
            }
        });
    });
});

// Function to show the success modal
function showSuccessModal() {
    $('#success-modal').fadeIn(); // Show the modal
}

// Function to hide the success modal
function hideSuccessModal() {
    $('#success-modal').fadeOut(); // Hide the modal
    window.location.href = "products.php";
}
$('#buyNowBtn').click(function() {
    const productId = <?php echo json_encode($product['id']); ?>;
    const quantity = $('#quantity').val();
    $.ajax({
        url: 'buy_now.php',
        method: 'POST',
        data: {
            id: productId,
            quantity: quantity
        },
        dataType: 'json',
        success: function(response) {
            
            if (response.success) {
                window.location.href = "checkout_2.php";
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('An error occurred while processing your request.');
        }
    });
});
</script>
<?php include "../includes/footer.php"; ?>