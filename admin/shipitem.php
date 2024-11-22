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

// Handle status update
if (isset($_POST['update_status']) && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Update the order status
    $updateQuery = "UPDATE order_items SET status = :status WHERE id = :order_id";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bindParam(':status', $status, PDO::PARAM_STR);
    $updateStmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $updateStmt->execute();

    // Move to the orders table if status is 'Delivered'
    if ($status === 'Delivered') {
        // Fetch the delivered order along with first_name, city, and product_image
        $deliveredOrderQuery = "SELECT user_id, product_name, product_type, quantity, shipping_fee, price, payment_method, order_date, first_name, city, product_image 
                                 FROM order_items WHERE id = :order_id";
        $deliveredOrderStmt = $conn->prepare($deliveredOrderQuery);
        $deliveredOrderStmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $deliveredOrderStmt->execute();
        $deliveredOrder = $deliveredOrderStmt->fetch(PDO::FETCH_ASSOC);
    
        // Insert into the sold table
        $insertQuery = "INSERT INTO sold (user_id, product_name, product_type, quantity, price, payment_method, order_date, first_name, city, product_image, shipping_fee, status) 
                        VALUES (:user_id, :product_name, :product_type, :quantity, :price, :payment_method, :order_date, :first_name, :city, :product_image, :shipping_fee, 'Delivered')";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bindParam(':user_id', $deliveredOrder['user_id']);
        $insertStmt->bindParam(':product_name', $deliveredOrder['product_name']);
        $insertStmt->bindParam(':product_type', $deliveredOrder['product_type']);
        $insertStmt->bindParam(':quantity', $deliveredOrder['quantity']);
        $insertStmt->bindParam(':price', $deliveredOrder['price']);
        $insertStmt->bindParam(':payment_method', $deliveredOrder['payment_method']);
        $insertStmt->bindParam(':order_date', $deliveredOrder['order_date']);
        $insertStmt->bindParam(':first_name', $deliveredOrder['first_name']);
        $insertStmt->bindParam(':product_image', $deliveredOrder['product_image']);
        $insertStmt->bindParam(':shipping_fee', $deliveredOrder['shipping_fee']);
        $insertStmt->bindParam(':city', $deliveredOrder['city']);
        $insertStmt->execute();
    
        // Delete from the order_items table
        $deleteQuery = "DELETE FROM order_items WHERE id = :order_id";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $deleteStmt->execute();
        
        $_SESSION['success'] = "Order status updated to Delivered.";
    }
    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch "Shipped" orders
$query = "SELECT * FROM order_items WHERE status = 'Shipped' ORDER BY order_date DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$shippedOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipped Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>

<body class="bg-slate-100">
    <?php include "../includes/header_admin.php"; ?>

    <main class="p-6">
    <div class="max-w-9xl mx-auto bg-white rounded shadow-lg mt-10 p-4">
        <h2 class="text-xl font-bold mb-4">Shipped Orders</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-500 text-white p-2 rounded mb-4">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <div class="overflow-x-auto "> 
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left">Product</th>
                        <th class="px-6 py-3 text-left">Order Date</th>
                        <th class="px-6 py-3 text-left">Product Name</th>
                        <th class="px-6 py-3 text-left">Client Name</th>
                        <th class="px-6 py-3 text-left">Client Location</th>
                        <th class="px-6 py-3 text-left">Quantity</th>
                        <th class="px-6 py-3 text-left">Price</th>
                        <th class="px-6 py-3 text-left">Shipping Fee</th>
                        <th class="px-6 py-3 text-left">Total Price</th>
                        <th class="px-6 py-3 text-left">Payment Method</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($shippedOrders)): ?>
                        <?php foreach ($shippedOrders as $order): ?>
                            <tr>
                                <td class="border px-6 py-4">
                                    <img src="../uploads/<?php echo htmlspecialchars($order['product_image']); ?>" alt="Product Image" class="h-16 w-16 object-cover rounded">
                                </td>
                                <td class="border px-6 py-4"><?php echo htmlspecialchars($order['order_date']); ?></td>
                                <td class="border px-6 py-4"><?php echo htmlspecialchars($order['product_name']); ?></td>
                                <td class="border px-6 py-4"><?php echo htmlspecialchars($order['first_name']); ?></td>
                                <td class="border px-6 py-4"><?php echo htmlspecialchars($order['city']); ?></td>
                                <td class="border px-6 py-4"><?php echo htmlspecialchars($order['quantity']); ?></td>
                                <td class="border px-6 py-4">₱<?php echo number_format($order['price'] ?? 0, 2); ?></td>
<td class="border px-6 py-4">₱<?php echo number_format($order['shipping_fee'] ?? 0, 2); ?></td>
<td class="border px-6 py-4">₱<?php echo number_format(($order['price'] ?? 0) + ($order['shipping_fee'] ?? 0), 2); ?></td>

                                <td class="border px-6 py-4"><?php echo htmlspecialchars($order['payment_method']); ?></td>
                                <td class="border px-6 py-4"><?php echo htmlspecialchars($order['status']); ?></td>
                                <td class="border px-6 py-4">
                                    <form method="POST" action="">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <select name="status" class="border rounded p-1">
                                            <option value="Packing" <?php if ($order['status'] === 'Packing') echo 'selected'; ?>>Packing</option>
                                            <option value="Shipped" <?php if ($order['status'] === 'Shipped') echo 'selected'; ?>>Shipped</option>
                                            <option value="Delivered" <?php if ($order['status'] === 'Delivered') echo 'selected'; ?>>Delivered</option>
                                        </select>
                                        <button type="submit" name="update_status" class="ml-2 bg-blue-500 text-white px-3 py-1 rounded">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="12" class="border px-6 py-4 text-center">No shipped orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
