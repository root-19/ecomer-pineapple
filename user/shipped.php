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

// Fetch user purchases with status 'Shipped'
$query = "SELECT * FROM order_items WHERE user_id = :user_id AND status = 'Shipped' ORDER BY order_date DESC";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Shipped Purchases</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        .steps {
            display: flex;
            justify-content: space-between;
        }
        .step {
            flex: 1;
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #f3f4f6;
            position: relative;
        }
        .step-primary {
            background:#facc15;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-slate-100">
    <?php include "../includes/header.php"; ?>

    <br><br>
    <main class="p-6">
        <div class="max-w-5xl mx-auto bg-white rounded shadow-lg mt-10 p-4">
            <h2 class="text-xl font-bold mb-4">My Shipped Purchases</h2>

            <ul class="steps mb-4">
    <li class="step cursor-pointer" onclick="navigateTo('packing.php')">Packing</li>
    <li class="step step-primary cursor-pointer" onclick="navigateTo('shipped.php')">Shipped</li>
    <li class="step cursor-pointer" onclick="navigateTo('receive.php')">Delivered</li>
</ul>

<script>
function navigateTo(url) {
    window.location.href = url;
}
</script>
            <div class="overflow-x-auto">
    <table class="min-w-full table-auto border-collapse">
        <thead>
            <tr class="bg-gray-200">
                <th class="px-4 py-2 text-left text-sm font-bold">Product Image</th>
                <th class="px-4 py-2 text-left text-sm font-bold">Order Date</th>
                <th class="px-4 py-2 text-left text-sm font-bold">Product Name</th>
                <th class="px-4 py-2 text-left text-sm font-bold">Product Type</th>
                <th class="px-4 py-2 text-left text-sm font-bold">Quantity</th>
                <th class="px-4 py-2 text-left text-sm font-bold">Price</th>
                <th class="px-4 py-2 text-left text-sm font-bold">Payment Method</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($purchases)): ?>
                <?php foreach ($purchases as $purchase): ?>
                    <tr class="bg-gray-100 border-b">
                        <td class="px-4 py-2">
                            <img src="../uploads/<?php echo htmlspecialchars($purchase['product_image']); ?>" alt="Product Image" class="h-16 w-16 object-cover rounded">
                        </td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($purchase['order_date']); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($purchase['product_name']); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($purchase['product_type']); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($purchase['quantity']); ?></td>
                        <td class="px-4 py-2">₱<?php echo number_format($purchase['price'], 2); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($purchase['payment_method']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="px-4 py-2 text-center text-sm text-gray-600">You have no purchases in Packing status yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </main>
</body>
</html>
