<?php
session_start();
include_once "../Controller/Database/Database.php"; 
include_once "Cart.php"; 

$database = new Database(); 
$conn = $database->connect();
$cart = new Cart($conn); 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']); // Make sure this is the quantity from the form

    // Call the method to add the product to the cart
    if ($cart->addToCart($product_id, $quantity)) {
        // Redirect or display success message
        header("Location: ../../Views/user/place-order.php");
        exit;
    } else {
        echo "Failed to add product to cart.";
    }
} else {
    echo "Invalid request.";
}
