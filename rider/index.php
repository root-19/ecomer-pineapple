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
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'rider') {
    $_SESSION['error'] = "Access denied.";
}

// Handle status update with image upload
if (isset($_POST['update_status']) && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $imageName = null;

    // Handle image upload if provided
    if (isset($_FILES['delivery_image']) && $_FILES['delivery_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        $imageName = time() . '_' . basename($_FILES['delivery_image']['name']);
        $uploadFilePath = $uploadDir . $imageName;

        if (!move_uploaded_file($_FILES['delivery_image']['tmp_name'], $uploadFilePath)) {
            $_SESSION['error'] = "Failed to upload the image.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    // Update the order status
    $updateQuery = "UPDATE order_items SET status = :status WHERE id = :order_id";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bindParam(':status', $status, PDO::PARAM_STR);
    $updateStmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $updateStmt->execute();

    // Move to the orders table if status is 'Delivered'
    if ($status === 'Delivered') {
        // Fetch the delivered order
        $deliveredOrderQuery = "SELECT oi.user_id, oi.product_name, oi.product_type, oi.quantity, oi.shipping_fee, oi.price, oi.payment_method, oi.order_date, 
                                       u.first_name, u.city, u.street_address, oi.product_image
                                FROM order_items oi
                                JOIN users u ON oi.user_id = u.id
                                WHERE oi.id = :order_id";
        $deliveredOrderStmt = $conn->prepare($deliveredOrderQuery);
        $deliveredOrderStmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $deliveredOrderStmt->execute();
        $deliveredOrder = $deliveredOrderStmt->fetch(PDO::FETCH_ASSOC);

        // Insert into the sold table
        $insertQuery = "INSERT INTO sold (user_id, product_name, product_type, quantity, price, payment_method, order_date, first_name, city, street_address, product_image, shipping_fee, delivery_image, status) 
                        VALUES (:user_id, :product_name, :product_type, :quantity, :price, :payment_method, :order_date, :first_name, :city, :street_address, :product_image, :shipping_fee, :delivery_image, 'Delivered')";
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
        $insertStmt->bindParam(':street_address', $deliveredOrder['street_address']);
        $insertStmt->bindParam(':delivery_image', $imageName);
        $insertStmt->execute();

        // Delete from the order_items table
        $deleteQuery = "DELETE FROM order_items WHERE id = :order_id";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $deleteStmt->execute();

        $_SESSION['success'] = "Order marked as delivered with the image uploaded.";
    }

    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch "Shipped" orders
$query = "SELECT oi.*, u.city, u.street_address, u.first_name 
          FROM order_items oi
          JOIN users u ON oi.user_id = u.id
          WHERE oi.status = 'Shipped' ORDER BY oi.order_date DESC";
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
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
<header class="bg-green-700 text-white">
    <div class="container mx-auto flex items-center justify-between p-4">
        <div class="text-lg font-bold">Rider Side</div>
        <button class="hidden md:block bg-red-500 px-4 py-2 rounded hover:bg-red-700" onclick="logout()">Logout</button>
    </div>
</header>

<main class="p-6">
    <div class="max-w-6xl mx-auto bg-white rounded shadow-lg mt-10 p-4">
        <h2 class="text-xl font-bold mb-4">Shipped Orders</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-6 py-3 text-left">Product Image</th>
                        <!-- <th class="px-6 py-3 text-left">Order Date</th> -->
                        <!-- <th class="px-6 py-3 text-left">Status</th> -->
                        <th class="px-6 py-3 text-left"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($shippedOrders)): ?>
                        <?php foreach ($shippedOrders as $order): ?>
                            <tr class="border-b group">
                                <td class="border px-6 py-4">
                                    <img src="../uploads/<?php echo htmlspecialchars($order['product_image']); ?>" 
                                         alt="<?php echo htmlspecialchars($order['product_name']); ?>" 
                                         class="w-16 h-16 object-cover">
                                </td>
                                <!-- <td class="border px-6 py-4"><?php echo htmlspecialchars($order['order_date']); ?></td> -->
                                <!-- <td class=" px-6 py-4"><?php echo htmlspecialchars($order['status']); ?></td> -->
                                <td class=" px-6 py-4 text-center">
                                    <button 
                                        class="expand-row-btn focus:outline-none text-gray-500 hover:text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" 
                                             fill="none" 
                                             viewBox="0 0 24 24" 
                                             stroke-width="2" 
                                             stroke="currentColor" 
                                             class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" 
                                                  d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <tr class="details-row hidden">
                            <td colspan="4" class="bg-gray-50 p-6">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <p><strong>Product Name:</strong> <?php echo htmlspecialchars($order['product_name']); ?></p>
            <p><strong>Client Name:</strong> <?php echo htmlspecialchars($order['first_name']); ?></p>
            <p><strong>Client Location:</strong> <?php echo htmlspecialchars($order['city']) . ', ' . htmlspecialchars($order['street_address']); ?></p>
        </div>
        <div>
            <p><strong>Quantity:</strong> <?php echo htmlspecialchars($order['quantity']); ?></p>
            <p><strong>Price:</strong> <?php echo number_format($order['price'], 2); ?></p>
            <p><strong>Shipping Fee:</strong> <?php echo number_format($order['shipping_fee'], 2); ?></p>
            <p><strong>Total Price:</strong> <?php echo number_format($order['quantity'] * $order['price'] + $order['shipping_fee'], 2); ?></p>
        </div>
    </div>
    <div class="flex items-center justify-between mt-4">
        <a href="https://www.google.com/maps?q=<?php echo urlencode($order['street_address'] . ', ' . $order['city']); ?>" 
           target="_blank" 
           class="text-blue-600 underline">
           View on Google Maps
        </a>
        <form action="" method="post" enctype="multipart/form-data" class="flex items-center gap-2">
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id']); ?>">
            <input type="hidden" name="status" value="Delivered">
            <label class="block">
                <span class="sr-only">Choose image</span>
                <input type="file" name="delivery_image" accept="image/*" 
                       class="block w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4 file:border-0 
                              file:bg-gray-100 file:text-blue-600 hover:file:bg-blue-50">
            </label>
            <button type="submit" name="update_status" 
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Delivered
            </button>
        </form>
    </div>
</td>


                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-4">No shipped orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
    // JavaScript to toggle row details visibility
    document.querySelectorAll('.expand-row-btn').forEach(button => {
        button.addEventListener('click', () => {
            const detailsRow = button.closest('tr').nextElementSibling;
            detailsRow.classList.toggle('hidden');
            button.querySelector('svg').classList.toggle('rotate-180');
        });
    });

    function logout() {
        window.location.href = "../user/logout.php";
    }
</script>

</body>
</html>

