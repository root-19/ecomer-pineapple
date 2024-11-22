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
        // Fetch the delivered order
        $deliveredOrderQuery = "SELECT * FROM order_items WHERE id = :product_id";
        $deliveredOrderStmt = $conn->prepare($deliveredOrderQuery);
        $deliveredOrderStmt->bindParam(':product_id', $order_id, PDO::PARAM_INT);
        $deliveredOrderStmt->execute();
        $deliveredOrder = $deliveredOrderStmt->fetch(PDO::FETCH_ASSOC);

        $insertQuery = "INSERT INTO sold (user_id, product_name, product_type, first_name, city, quantity, price, payment_method, order_date, product_image,product_id, status) 
                VALUES (:user_id, :product_name, :product_type, :first_name, :city, :quantity, :price, :payment_method, :order_date,:product_image, :product_id, 'Delivered')";

$insertStmt = $conn->prepare($insertQuery);
$insertStmt->bindParam(':user_id', $deliveredOrder['user_id']);
$insertStmt->bindParam(':product_name', $deliveredOrder['product_name']);
$insertStmt->bindParam(':first_name', $deliveredOrder['first_name']);
$insertStmt->bindParam(':city', $deliveredOrder['city']);
$insertStmt->bindParam(':product_type', $deliveredOrder['product_type']);
$insertStmt->bindParam(':product_image', $deliveredOrder['product_image']);
$insertStmt->bindParam(':quantity', $deliveredOrder['quantity']);
$insertStmt->bindParam(':price', $deliveredOrder['price']);
$insertStmt->bindParam(':payment_method', $deliveredOrder['payment_method']);
$insertStmt->bindParam(':product_id', $deliveredOrder['product_id']);
$insertStmt->bindParam(':order_date', $deliveredOrder['order_date']);
        $insertStmt->execute();

        // Delete from the order_items table
        $deleteQuery = "DELETE FROM order_items WHERE id = :product_id";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bindParam(':product_id', $order_id, PDO::PARAM_INT);
        $deleteStmt->execute();
    }

    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch all purchases except those that are Shipped or Delivered
$query = "SELECT * FROM order_items WHERE status NOT IN ('Shipped', 'Delivered') ORDER BY order_date DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <title>Client Purchases</title>


<body class="bg-slate-100">
   <?php include "../includes/header_admin.php"; ?>

    <br><br>
    <main class="p-6">
        
    <!-- <div class="max-w-5xl mx-auto bg-white rounded shadow-lg mt-5 p-4"> -->
        <!-- <h2 class="text-xl font-bold mb-4">Client Purchases</h2> -->

        <div class="flex items-center justify-center min-w-full">

            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                <!-- <h2 class="text-xl font-bold mb-4">Client Purchases</h2> -->
                    <tr>
                        <th class="px-6 py-3 text-left">Product Image</th>
                        <th class="px-6 py-3 text-left">Order Date</th>
                        <th class="px-6 py-3 text-left">Product Name</th>
                        <th class="px-6 py-3 text-left">Client Name</th>
                        <th class="px-6 py-3 text-left">Client Location</th>
                        <th class="px-6 py-3 text-left">Quantity</th>
                        <th class="px-6 py-3 text-left">Price</th>
                        <th class="px-6 py-3 text-left">Payment Method</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
    <?php if (!empty($purchases)): ?>
        <?php foreach ($purchases as $purchase): ?>
            <tr>
                <!-- Product Image -->
                <td class="border px-6 py-4">
                    <img src="../uploads/<?php echo htmlspecialchars($purchase['product_image']); ?>" alt="Product Image" class="h-16 w-16 object-cover rounded">
                </td>
                <!-- Other purchase details -->
                <td class="border px-6 py-4"><?php echo htmlspecialchars($purchase['order_date']); ?></td>
                <td class="border px-6 py-4"><?php echo htmlspecialchars($purchase['product_name']); ?></td>
                <td class="border px-6 py-4"><?php echo htmlspecialchars($purchase['first_name']); ?></td>
                <td class="border px-6 py-4"><?php echo htmlspecialchars($purchase['city']); ?></td>
                <td class="border px-6 py-4"><?php echo htmlspecialchars($purchase['quantity']); ?></td>
                <td class="border px-6 py-4">₱<?php echo number_format($purchase['price'] + $purchase['shipping_fee'], 2); ?></td>
                <td class="border px-6 py-4"><?php echo htmlspecialchars($purchase['payment_method']); ?></td>
                <td class="border px-6 py-4"><?php echo htmlspecialchars($purchase['status']); ?></td>
                <td class="border px-6 py-4">
                <form method="POST" action="">
    <input type="hidden" name="order_id" value="<?php echo $purchase['id']; ?>">
    <input type="hidden" name="status" value="Shipped">
    <button type="submit" name="update_status" class="ml-2 bg-blue-500 text-white px-3 py-2 rounded">
        Shipped
    </button>
</form>

                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="10" class="border px-6 py-4 text-center">No purchases found.</td>
        </tr>
    <?php endif; ?>
</tbody>
            </table>
        </div>
    </div>
</main>
</body>
</html>
