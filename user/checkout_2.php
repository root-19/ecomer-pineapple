<?php
// Start the session
session_start();


// Include your database connection file
include_once "../../Controller/Database/Database.php"; 
$database = new Database(); 
$conn = $database->connect();



// Enable PDO error mode
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if user is logged in
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'user') {
    $_SESSION['error'] = "Access denied.";

}

// Fetch user ID
$user_id = $_SESSION['id'];

// Fetch user details
$query = "SELECT first_name, last_name, email, phone_number, street_address, baranggay, city, province FROM users WHERE id = :id LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch cart items for the logged-in user
$query = "SELECT * FROM buy_now  WHERE user_id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if cart is empty
if (empty($cartItems)) {
    $_SESSION['error'] = "Your cart is empty.";
    header("Location: view-product.php");
    exit();
}


// Shipping fees by province
$shippingFees = [
    'Abra' => 400,
    'Agusan del Norte' => 400,
    'Agusan del Sur' => 400,
    'Aklan' => 400,
    'Albay' => 400,
    'Antique' => 400,
    'Apayao' => 400,
    'Aurora' => 400,
    'Basilan' => 400,
    'Bataan' => 400,
    'Batanes' => 400,
    'Batangas' => 400,
    'Benguet' => 400,
    'Biliran' => 400,
    'Bohol' => 400,
    'Bukidnon' => 400,
    'Bulacan' => 400,
    'Cagayan' => 400,
    'Camarines Norte' => 400,
    'Camarines Sur' => 400,
    'Camiguin' => 400,
    'Capiz' => 400,
    'Catanduanes' => 400,
    'Cavite' => 400,
    'Cebu' => 400,
    'Compostela Valley' => 400,
    'Cotabato' => 400,
    'Davao de Oro' => 400,
    'Davao del Norte' => 400,
    'Davao del Sur' => 400,
    'Davao Occidental' => 400,
    'Eastern Samar' => 400,
    'Guimaras' => 400,
    'Ifugao' => 400,
    'Ilocos Norte' => 400,
    'Ilocos Sur' => 400,
    'Iloilo' => 400,
    'Isabela' => 400,
    'Kalinga' => 400,
    'La Union' => 400,
    'Laguna' => 400,
    'Lanao del Norte' => 400,
    'Lanao del Sur' => 400,
    'Leyte' => 400,
    'Maguindanao' => 400,
    'Marinduque' => 400,
    'Masbate' => 400,
    'Misamis Occidental' => 400,
    'Misamis Oriental' => 400,
    'Mountain Province' => 400,
    'Negros Occidental' => 400,
    'Negros Oriental' => 400,
    'Northern Samar' => 400,
    'Nueva Ecija' => 400,
    'Nueva Vizcaya' => 400,
    'Occidental Mindoro' => 400,
    'Oriental Mindoro' => 400,
    'Palawan' => 400,
    'Pampanga' => 400,
    'Pangasinan' => 400,
    'Quezon' => 400,
    'Quirino' => 400,
    'Rizal' => 400,
    'Romblon' => 400,
    'Samar' => 400,
    'Sarangani' => 400,
    'Siquijor' => 400,
    'Sorsogon' => 400,
    'South Cotabato' => 400,
    'Southern Leyte' => 400,
    'Sultan Kudarat' => 400,
    'Surigao del Norte' => 400,
    'Surigao del Sur' => 400,
    'Tarlac' => 400,
    'Tawi-Tawi' => 400,
    'Zambales' => 400,
    'Zamboanga del Norte' => 400,
    'Zamboanga del Sur' => 400,
    'Zamboanga Sibugay' => 400,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn->beginTransaction();

        // Get payment method from POST data
        $payment_method = $_POST['payment_method'];
        
        // Initialize payment receipt path
        $payment_receipt_path = null;

        // Handle file upload for GCash payment
        if ($payment_method === 'gcash' && isset($_FILES['payment_receipt'])) {
            $file = $_FILES['payment_receipt'];
            $uploadDir = '../uploads/';
            $uploadFile = $uploadDir . basename($file['name']);
            $fileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

            // Check if the file is a valid image type (you can customize this)
            if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                    $payment_receipt_path = basename($file['name']);
                } else {
                    throw new Exception("Failed to upload payment receipt.");
                }
            } else {
                throw new Exception("Invalid file type for payment receipt.");
            }
        }

        // Loop through all cart items to insert them into order_items
        foreach ($cartItems as $item) {
            // Calculate total, shipping fee, and item total
            $total = ($item['price'] * $item['product_quantity']); 
            $shippingFee = $shippingFees[$user['province']] ?? 0; 
            $itemTotal = $total + $shippingFee; 
        
            // Insert the order
            $orderQuery = "INSERT INTO order_items (user_id, first_name, city, province, product_name, product_type, quantity, price, payment_method, product_image, order_date, product_id, shipping_fee, payment_receipt) 
                           VALUES (:user_id, :first_name, :city, :province, :product_name, :product_type, :quantity, :price, :payment_method, :product_image, NOW(), :product_id, :shipping_fee, :payment_receipt)";
            
            $orderStmt = $conn->prepare($orderQuery);
            $orderStmt->execute([
                ':user_id' => $user_id,
                ':first_name' => $user['first_name'],
                ':city' => $user['city'],
                ':province' => $user['province'],
                ':product_name' => $item['product_name'], 
                ':product_type' => $item['product_type'], 
                ':quantity' => $item['product_quantity'],
                ':price' => $item['price'],
                ':product_image'=> $item['product_image'],
                ':payment_method' => $payment_method,
                ':product_id' => $item['product_id'],
                ':shipping_fee' => $shippingFee,
                ':payment_receipt' => $payment_receipt_path, // Save the path of the receipt
            ]);
        }

        // Delete cart items for the user after placing the order
        $deleteQuery = "DELETE FROM buy_now WHERE user_id = :user_id";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $deleteStmt->execute();

        // Commit transaction
        $conn->commit();

        // Send JSON response to be handled by AJAX
        echo json_encode(["success" => true, "message" => "Order placed successfully!"]);
        exit();
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollBack();
        echo json_encode(["success" => false, "message" => "Something went wrong: " . $e->getMessage()]);
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Include SweetAlert JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
    <!-- Include SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/heroic-tailwind@1.0.2/dist/heroic-tailwind.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>

</head>
<body class="bg-slate-100">
   <?php include "../includes/header.php"; ?>
    <br><br>
    <main class="p-6">
    <div class="max-w-5xl mx-auto bg-white rounded shadow-lg mt-10 p-4">
        <!-- Layout: Two columns (Shipping info + Payment on left, Order Summary on right) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Left column: Shipping Info and Payment Method -->
            <div>
                <!-- Shipping Information -->
                <div class="bg-gray-100 p-4 rounded mb-6">
                    <h2 class="text-xl font-bold mb-4">Shipping Information</h2>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($user['first_name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone_number']); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($user['street_address'] . ', ' . $user['baranggay']); ?></p>
                    <p><strong>City:</strong> <?php echo htmlspecialchars($user['city']); ?></p>
                    <p><strong>Province:</strong> <?php echo htmlspecialchars($user['province']); ?></p>
                </div>

                <!-- Payment Method -->
                <div class="bg-gray-100 p-6 rounded shadow">
                    <h2 class="text-xl font-bold mb-6">Payment Method</h2>
                    
                    <form id="order-form">
                        <!-- Payment method options (GCash, COD, Pick up) -->
                        <div class="space-y-4">
                            <!-- GCash Option -->
                            <label for="gcash" class="flex items-center space-x-4 p-2 bg-white rounded shadow cursor-pointer hover:bg-gray-50 transition duration-150 ease-in-out">
    <input type="radio" name="payment_method" value="gcash" id="gcash" class="form-radio h-5 w-5 text-blue-600 cursor-pointer">
    <span class="text-lg font-medium">GCash</span>
    <!-- Mobile Payment Icon (Font Awesome) -->
    <i class="fas fa-mobile-alt text-blue-600 ml-auto text-2xl"></i>
</label>

<!-- Cash on Delivery Option -->
<label for="cash_on_delivery" class="flex items-center space-x-4 p-2 bg-white rounded shadow cursor-pointer hover:bg-gray-50 transition duration-150 ease-in-out">
    <input type="radio" name="payment_method" value="cash_on_delivery" id="cash_on_delivery" class="form-radio h-5 w-5 text-blue-600 cursor-pointer">
    <span class="text-lg font-medium">Cash on Delivery</span>
    <!-- Cash on Delivery Icon (Font Awesome) -->
    <i class="fas fa-money-bill-wave text-blue-600 ml-auto text-2xl"></i>
</label>

<!-- Pick-up Option -->
<label for="pick_up" class="flex items-center space-x-4 p-2 bg-white rounded shadow cursor-pointer hover:bg-gray-50 transition duration-150 ease-in-out">
    <input type="radio" name="payment_method" value="Pick_up" id="pick_up" class="form-radio h-5 w-5 text-blue-600 cursor-pointer">
    <span class="text-lg font-medium">Pick-up</span>
    <!-- Pick-up Icon (Font Awesome) -->
    <i class="fas fa-truck text-blue-600 ml-auto text-2xl"></i>
</label>
                        </div>
                        
                        <div id="gcash-details" style="display: none;" class="mt-4">
    <h3 class="text-lg font-semibold">GCash QR Code</h3>
    <img id="qr_code" src="../../Assets/images/gcash.jpg" alt="QR Code" class="mt-2 w-44 h-44"> 

    <p>Scan the QR code to pay via GCash.</p>
    <label class="block text-sm font-medium mt-4">
        Upload Payment Receipt:
    </label>
    <input type="file" name="payment_receipt" class="mt-2 p-2 border rounded">
</div>
                        <!-- Place Order Button -->
                        <div class="text-center mt-6 text-start">
                            <button type="submit" class="bg-yellow-600 text-white font-bold py-2 px-6 rounded hover:bg-yellow-700 transition duration-200">
                                Place Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>

          <!-- Right column: Order Summary -->
<div>
<div class="bg-gray-100 p-4 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Order Summary</h2>

    <!-- Scrollable section -->
    <div class="space-y-4 max-h-64 overflow-y-auto">

        <!-- Display each cart item -->
        <?php if (!empty($cartItems)): ?>
            <?php
            $grandTotal = 0; 
            foreach ($cartItems as $item):
                $total = ($item['price'] * $item['product_quantity']); 
                $shippingFee = $shippingFees[$user['province']] ?? 0; 
                $itemTotal = $total + $shippingFee; 
                $grandTotal += $itemTotal; 
            ?>
            <div class="flex items-center justify-between bg-white p-4 rounded shadow relative">
                <!-- Delete button (X) -->
                <form method="post" action="delete_item.php" class="absolute top-2 right-2">
                    <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item['id']); ?>">
                    <button type="submit" class="text-red-500 font-bold hover:text-red-700">X</button>
                </form>

                <img src="../uploads/<?php echo htmlspecialchars($item['product_image']); ?>" 
                     alt="<?php echo htmlspecialchars($item['product_name']); ?>" 
                     class="w-16 h-16 object-cover">
                <div>
                    <p class="font-bold"><?php echo htmlspecialchars($item['product_name']); ?></p>
                    <p class="text-sm"><?php echo htmlspecialchars($item['product_type']); ?></p>
                </div>
                <div>
                    <p class="font-bold">₱<?php echo number_format($item['price'], 2); ?></p>
                    <p class="text-sm">Qty: <?php echo htmlspecialchars($item['product_quantity']); ?></p>
                    <p class="text-sm">Shipping Fee: ₱<?php echo number_format($shippingFee, 2); ?></p> <!-- Display shipping fee here -->
                    <p class="font-bold">Total: ₱<?php echo number_format($itemTotal, 2); ?></p> <!-- Total including shipping fee -->
                </div>
            </div>
            <?php endforeach; ?>

      
        <?php else: ?>
            <p class="text-sm text-gray-600">Your cart is empty.</p> <!-- Message for empty cart -->
        <?php endif; ?>

           <!-- Display Grand Total -->
    <div class="flex justify-between font-bold mt-4">
        <span>Total:</span>
        <span>₱<?php echo number_format($grandTotal, 2); ?></span> 
    </div>
</div>
    </div>
</div>




        </div>
    </div>
    </main>
    
    <!-- Success Modal -->
    <div id="success-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white p-6 rounded shadow-lg">
            <h2 class="text-xl font-bold mb-4">Order Success!</h2>
            <p>Your order has been placed successfully.</p>
            <button class="bg-blue-500 text-white px-4 py-2 rounded mt-4" onclick="showReviewModal()">OK</button>
        </div>
    </div>

   



    <!-- Review Modal -->
    <div id="review-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow-lg relative">
       
        <button id="close-modal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9.293l4.646-4.647a1 1 0 011.414 1.414L11.414 10l4.646 4.646a1 1 0 01-1.414 1.414L10 11.414l-4.646 4.646a1 1 0 01-1.414-1.414L8.586 10 3.94 5.354A1 1 0 015.354 3.94L10 8.586z" clip-rule="evenodd" />
            </svg>
        </button>
        
        <h2 class="text-xl font-bold mb-4">Add a Review</h2>
        <form action="review.php" method="POST">
            <div class="space-y-4">
                <?php foreach ($cartItems as $item): ?>
                    <div>
                        <p class="font-bold"><?php echo htmlspecialchars($item['product_name']); ?></p>

                        <form action="review.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['product_id'] ?? ''); ?>"> <!-- Handle null value -->
                            <input type="hidden" name="product_type" value="<?php echo htmlspecialchars($item['product_type'] ?? ''); ?>"> <!-- Handle null value -->

                            <div class="flex items-center mb-4">
                                <label class="font-medium mr-2">Rate:</label>
                                <div class="flex space-x-1">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <label>
                                            <input type="radio" name="rating" value="<?php echo $i; ?>" required>
                                            <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.518 4.674h4.912c.969 0 1.371 1.24.588 1.81l-3.97 2.883 1.518 4.674c.3.921-.755 1.688-1.539 1.188l-3.97-2.883-3.971 2.883c-.783.5-1.838-.267-1.539-1.188l1.518-4.674-3.97-2.883c-.783-.57-.38-1.81.588-1.81h4.912L9.049 2.927z"/>
                                            </svg>
                                        </label>
                                    <?php endfor; ?>
                                </div>
                            </div>

                      
                            <textarea name="review" rows="4" class="w-full p-2 border rounded" placeholder="Write your review..." required></textarea>

                          
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded mt-2">Submit Review</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('close-modal').addEventListener('click', function() {
    // Show SweetAlert before redirecting
    Swal.fire({
        title: 'Thank you for purchasing!',
        text: 'Your order has been received.',
        icon: 'success'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect to products.php after the alert is confirmed
            window.location.href = 'products.php';
        }
    });
});
</script>


<script>
      document.getElementById('order-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form submission

            let formData = new FormData(this);

            fetch('<?php echo $_SERVER['PHP_SELF']; ?>', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success modal
                    Swal.fire("Order Placed", data.message, "success");
                   setTimeout(() => {
                    window.location.href = "products.php";
                   }, 4000);
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            })
            .catch(error => Swal.fire("Error", "Something went wrong!", "error"));
        });

        // Show review modal after success modal is closed
        function showReviewModal() {
            document.getElementById('success-modal').classList.add('hidden');
            document.getElementById('review-modal').classList.remove('hidden');
        }
    </script>

<script>
     // Show GCash QR code details when GCash is selected
     document.getElementById('gcash').addEventListener('change', function() {
        document.getElementById('gcash-details').style.display = 'block';
    });

    // Hide GCash details if another payment method is selected
    document.getElementById('cash_on_delivery').addEventListener('change', function() {
        document.getElementById('gcash-details').style.display = 'none'; 
    });
    
    document.getElementById('pick_up').addEventListener('change', function() {
        document.getElementById('gcash-details').style.display = 'none'; 
    })
</script>

</body>
</html>

