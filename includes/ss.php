<?php
// Start the session


error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database connection file
// include_once "../../Controller/Database/Database.php"; 
$database = new Database(); 
$conn = $database->connect();

// Check if connection is successful
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Enable PDO error mode
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if user is logged in
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'user') {
    $_SESSION['error'] = "Access denied.";
    header("Location: signin.php");
    exit();
}

// Fetch user ID
$user_id = $_SESSION['id'];

// Fetch user purchases
$query = "SELECT * FROM order_items WHERE user_id = :user_id ORDER BY order_date DESC";
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
    <title>My Purchases</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-slate-100">
    
    <main class="p-6">
        <div class="max-w-5xl mx-auto bg-white rounded shadow-lg mt-10 p-4">
            <h2 class="text-xl font-bold mb-4">My Purchases</h2>
            <div class="space-y-4 max-h-64 overflow-y-auto"> <!-- Scrollable section -->
                <?php if (!empty($purchases)): ?>
                    <?php foreach ($purchases as $purchase): ?>
                        <div class="bg-gray-100 p-4 rounded shadow flex justify-between items-center">
                            <div>
                                <p class="font-bold"><?php echo htmlspecialchars($purchase['product_name']); ?></p>
                                <p class="text-sm">Type: <?php echo htmlspecialchars($purchase['product_type']); ?></p>
                                <p class="text-sm">Quantity: <?php echo htmlspecialchars($purchase['quantity']); ?></p>
                                <p class="text-sm">Price: ₱<?php echo number_format($purchase['price'], 2); ?></p>
                                <p class="text-sm">Order Date: <?php echo date("F j, Y", strtotime($purchase['order_date'])); ?></p>
                            </div>
                            <div class="flex items-center">
                                <div class="mr-4">
                                    <!-- Status Display -->
                                    <span class="font-bold"><?php echo htmlspecialchars($purchase['status']); ?></span>
                                </div>
                                <!-- Update Status Button -->
                                <form action="update_status.php" method="POST">
                                    <input type="hidden" name="order_id" value="<?php echo $purchase['id']; ?>">
                                    <select name="status" onchange="this.form.submit()" class="p-2 border rounded">
                                        <option value="Packing" <?php echo $purchase['status'] == 'Packing' ? 'selected' : ''; ?>>Packing</option>
                                        <option value="Shipped" <?php echo $purchase['status'] == 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                                        <option value="Delivering" <?php echo $purchase['status'] == 'Delivering' ? 'selected' : ''; ?>>Delivering</option>
                                        <option value="Delivered" <?php echo $purchase['status'] == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-sm text-gray-600">You have no purchases yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>
