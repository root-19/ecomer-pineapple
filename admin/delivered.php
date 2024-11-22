<?php
// Start the session
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database connection file
include_once "../../Controller/Database/Database.php"; 
$database = new Database(); 
$conn = $database->connect();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Access denied.";
 
}

try {
    // Prepare the SQL query to fetch all orders from the `sold` table
    $deliveredOrderQuery = "SELECT product_image, delivery_image, product_name, first_name, city, quantity, price, shipping_fee, payment_method FROM sold"; 
    $deliveredOrderStmt = $conn->prepare($deliveredOrderQuery);

    // Execute the query
    $deliveredOrderStmt->execute();

    // Fetch all the results
    $deliveredOrders = $deliveredOrderStmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if any orders are found
    if (!$deliveredOrders) {
        echo "<p class='text-red-500 text-center'>No delivered orders found.</p>";
        exit;
    }
} catch (PDOException $e) {
    // Handle SQL execution errors
    echo "<p class='text-red-500 text-center'>Error fetching orders: " . htmlspecialchars($e->getMessage()) . ".</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivered Order Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>

<body class="bg-slate-100">
    <?php include "../includes/header_admin.php"; ?>

    <main class="p-6">
        <div class="max-w-5xl mx-auto bg-white rounded shadow-lg mt-10 p-4">
            <h2 class="text-xl font-bold mb-4">Delivered Order Details</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left">proof of receive</th>
                            <th class="px-6 py-3 text-left">Product</th>
                            <th class="px-6 py-3 text-left">Product Name</th>
                            <th class="px-6 py-3 text-left">Client Name</th>
                            <th class="px-6 py-3 text-left">Client Location</th>
                            <th class="px-6 py-3 text-left">Sold Quantity</th>
                            <th class="px-6 py-3 text-left">Sold Price</th>
                            <th class="px-6 py-3 text-left">Shipping Fee</th>
                            <th class="px-6 py-3 text-left">Total Price</th>
                            <th class="px-6 py-3 text-left">Payment Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Loop through the delivered orders and display each one -->
                        <?php foreach ($deliveredOrders as $deliveredOrder) : ?>
                            <tr>
                            <td class="border px-6 py-4">
                                    <img src="../uploads/<?php echo htmlspecialchars($deliveredOrder['delivery_image']); ?>" alt="Product Image" class="h-16 w-16 object-cover rounded">
                                </td>
                                <td class="border px-6 py-4">
                                    <img src="../uploads/<?php echo htmlspecialchars($deliveredOrder['product_image']); ?>" alt="Product Image" class="h-16 w-16 object-cover rounded">
                                </td>
                                <td class="border px-6 py-4"><?php echo htmlspecialchars($deliveredOrder['product_name'] ?? ''); ?></td>
                                <td class="border px-6 py-4"><?php echo htmlspecialchars($deliveredOrder['first_name'] ?? ''); ?></td>
                                <td class="border px-6 py-4"><?php echo htmlspecialchars($deliveredOrder['city'] ?? ''); ?></td>
                                <td class="border px-6 py-4"><?php echo htmlspecialchars($deliveredOrder['quantity'] ?? ''); ?></td>
                                <td class="border px-6 py-4">₱<?php echo number_format($deliveredOrder['price'] ?? 0, 2); ?></td>
                                <td class="border px-6 py-4">₱<?php echo number_format($deliveredOrder['shipping_fee'] ?? 0, 2); ?></td>
                                <td class="border px-6 py-4">₱<?php echo number_format(($deliveredOrder['price'] + $deliveredOrder['shipping_fee']) ?? 0, 2); ?></td>
                                <td class="border px-6 py-4"><?php echo htmlspecialchars($deliveredOrder['payment_method'] ?? ''); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
