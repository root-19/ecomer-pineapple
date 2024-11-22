<?php
// Start the session
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database connection file
include_once "../../Controller/Database/Database.php"; 
$database = new Database(); 
$conn = $database->connect();

// Check if user is logged in
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'user') {
    $_SESSION['error'] = "Access denied.";

}

// Fetch user ID
$user_id = $_SESSION['id'];

// Check if the order ID is provided in the URL
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    try {
        // Fetch the order details from the sold table
        $orderQuery = "SELECT * FROM sold WHERE id = :order_id AND user_id = :user_id";
        $stmt = $conn->prepare($orderQuery);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            // Insert order into the history table
            $insertHistoryQuery = "INSERT INTO history (user_id, product_id, product_name, product_type, quantity, price, payment_method, order_date, product_image, status) 
                                   VALUES (:user_id, :product_id, :product_name, :product_type, :quantity, :price, :payment_method, :order_date, :product_image, :status)";
            $insertStmt = $conn->prepare($insertHistoryQuery);
            $insertStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $insertStmt->bindParam(':product_id', $order['product_id'], PDO::PARAM_INT);
            $insertStmt->bindParam(':product_name', $order['product_name'], PDO::PARAM_STR);
            $insertStmt->bindParam(':product_type', $order['product_type'], PDO::PARAM_STR);
            $insertStmt->bindParam(':quantity', $order['quantity'], PDO::PARAM_INT);
            $insertStmt->bindParam(':price', $order['price'], PDO::PARAM_STR);
            $insertStmt->bindParam(':payment_method', $order['payment_method'], PDO::PARAM_STR);
            $insertStmt->bindParam(':order_date', $order['order_date'], PDO::PARAM_STR);
            $insertStmt->bindParam(':product_image', $order['product_image'], PDO::PARAM_STR);
            $insertStmt->bindParam(':status', $order['status'], PDO::PARAM_STR);

            // Execute the insert statement
            $insertStmt->execute();

            // Now update the status of the order in the sold table to "Delivered"
            $updateOrderQuery = "UPDATE sold SET status = 'Delivered' WHERE id = :order_id";
            $updateStmt = $conn->prepare($updateOrderQuery);
            $updateStmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $updateStmt->execute();

            // Redirect back to the history page or show a success message
            header("Location: history-order.php?status=success");
            exit();
        } else {
            echo "Order not found or you're not authorized to view this order.";
            exit();
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
} else {
    echo "No order ID specified.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-slate-100">
    <?php include "../includes/header.php"; ?>

    <br><br>
    <main class="p-6">
        <div class="max-w-5xl mx-auto bg-white rounded shadow-lg mt-10 p-4">
            <h2 class="text-xl font-bold mb-4">Order Details</h2>

            <!-- Display order details -->
            <div class="mb-6">
                <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['id']); ?></p>
                <p><strong>Product Name:</strong> <?php echo htmlspecialchars($order['product_name']); ?></p>
                <p><strong>Product Type:</strong> <?php echo htmlspecialchars($order['product_type']); ?></p>
                <p><strong>Quantity:</strong> <?php echo htmlspecialchars($order['quantity']); ?></p>
                <p><strong>Price:</strong> ₱<?php echo number_format($order['price'], 2); ?></p>
                <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
                <p><strong>Shipping Fee:</strong> ₱<?php echo number_format($order['shipping_fee'], 2); ?></p>
                <p><strong>Payment Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
            </div>

            <!-- Optionally, you can display an action button if you want to allow the user to take an action on this order -->
            <div class="flex justify-end">
                <a href="my-purchases.php" class="bg-gray-500 text-white px-4 py-2 rounded">Back to My Purchases</a>
            </div>
        </div>
    </main>
</body>
</html>
