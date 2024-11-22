<?php
session_start();
include_once "../Controller/Database/Database.php"; 
include_once "Cart.php"; 

$database = new Database(); 
$conn = $database->connect();
$cart = new Cart($conn); 
// var_dump($_POST);
//Fetch user details
$user_id = intval($_SESSION['user_id']); // Get user ID from session
$stmt = $conn->prepare("SELECT first_name, last_name FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'], $_POST['quantity'], $_POST['user_id'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $user_id = intval($_POST['user_id']);
    /// Retrieve first_name and last_name from the user data
    $first_name = htmlspecialchars($user['first_name']);
    $last_name = htmlspecialchars($user['last_name']);
    // Cal the method to add the product to the cart
    if ($cart->addToCart($user_id, $product_id, $quantity, $first_name, $last_name)) {
        header("Location: ../../Views/user/view-product.php");
        exit;
    } else {
        echo "Failed to add product to cart.";
    }
} else {
    echo "Invalid request.";
}
?>