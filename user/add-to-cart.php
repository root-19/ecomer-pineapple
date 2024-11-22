<?php
session_start();

// Include your database connection file
include_once "../../Controller/Database/Database.php"; 
$database = new Database(); 
$conn = $database->connect();

// Check if product ID and quantity are set
if (!isset($_POST['id'], $_POST['quantity'])) {
    $_SESSION['error'] = "Invalid request.";

}

$id = $_POST['id'];
$quantity = (int) $_POST['quantity'];

// Fetch the product details
$query = "SELECT * FROM products WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    $_SESSION['error'] = "Product not found.";
    header("Location: shop.php");
    exit();
}

// Initialize the cart if not already done
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add or update the cart
$cart_item = [
    'id' => $product['id'],
    'name' => $product['name'],
    'type' => $product['type'],
    'quantity' => $quantity,
    'price' => $product['price']
];

// Check if the product is already in the cart
$found = false;
foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $id) {
        $item['quantity'] += $quantity;  // Update quantity if it exists
        $found = true;
        break;
    }
}

if (!$found) {
    $_SESSION['cart'][] = $cart_item;
}

// Redirect back to shop or cart page
header("Location: cart.php");
exit();
