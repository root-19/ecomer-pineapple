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

try {
    // Prepare the SQL query to fetch orders based on the user_id
    $deliveredOrderQuery = "SELECT * FROM sold WHERE user_id = :user_id"; 
    $deliveredOrderStmt = $conn->prepare($deliveredOrderQuery);
    $deliveredOrderStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT); 

    // Execute the query
    $deliveredOrderStmt->execute();

    // Fetch all the results
    $purchases = $deliveredOrderStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Log or display the error
    echo "Error fetching orders: " . $e->getMessage();
    exit();
}

// Check if the "Receive" button was clicked and update the order status
if (isset($_POST['receive'])) {
    $order_id = $_POST['order_id'];

    try {
        $updateQuery = "UPDATE sold SET status = 'Completed' WHERE id = :order_id";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $updateStmt->execute();

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            echo json_encode(['success' => true, 'message' => 'Order status updated successfully.']);
            exit();
        } else {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    } catch (PDOException $e) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit();
        } else {
            echo "Error updating order status: " . $e->getMessage();
        }
    }
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Received Purchases</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .star {
            cursor: pointer;
            font-size: 24px;
            color: #ccc;
        }
        .star.selected {
            color: #ffcc00;
        }
    </style>
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
            background: #facc15;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-slate-100">
    <?php include "../includes/header.php"; ?>

    <br><br>
    <main class="p-6">
        <div class="max-w-5xl mx-auto bg-white rounded shadow-lg mt-10 p-4">
            <h2 class="text-xl font-bold mb-4">My Received Purchases</h2>

            <!-- Step tracking UI at the top -->
            <ul class="steps mb-4">
                <li class="step cursor-pointer" onclick="navigateTo('packing.php')">Packing</li>
                <li class="step cursor-pointer" onclick="navigateTo('shipped.php')">Shipped</li>
                <li class="step step-primary cursor-pointer" onclick="navigateTo('recieve.php')">Delivered</li>
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
                            <th class="px-4 py-2 text-left text-sm font-bold">Action</th>
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
                                    <td class="px-4 py-2">
                                        
                                         <!-- Conditional Button Display -->
                <?php if ($purchase['status'] === 'Completed'): ?>
                    <!-- <span class="text-green-500 font-bold">Completed</span> -->
                    <button onclick="showReviewModal(<?php echo htmlspecialchars(json_encode($purchase)); ?>)" class="bg-yellow-500 text-white px-4 py-2 rounded">Review</button>
                <?php elseif ($purchase['status'] === 'Received'): ?>
                    <button onclick="showReviewModal(<?php echo htmlspecialchars(json_encode($purchase)); ?>)" class="bg-yellow-500 text-white px-4 py-2 rounded">Review</button>
                <?php else: ?>
                    <form action="" method="POST">
                    <button 
            onclick="markAsReceived(<?php echo $purchase['id']; ?>, this)" 
            class="bg-green-500 text-white px-4 py-2 rounded">
            Receive
        </button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="8" class="px-4 py-2 text-center text-sm text-gray-600">You have no purchases in Delivered status yet.</td>
    </tr>
<?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <!-- Review Modal -->
    <div id="reviewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded shadow-lg w-96">
            <h3 class="text-lg font-semibold mb-4">Write a Review</h3>
            <div id="modalContent"></div>
            <textarea id="reviewText" class="w-full border rounded-lg p-2 mb-4" placeholder="Write your review..."></textarea>
            <div class="flex space-x-2 justify-end">
                <button onclick="submitReview()" class="bg-green-500 text-white px-4 py-2 rounded">Submit</button>
                <button onclick="closeReviewModal()" class="bg-red-500 text-white px-4 py-2 rounded">Cancel</button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script src="../../Assets/js/delivered-review.js"></script>

</body>
</html>
