<?php
// Start the session
session_start();

// Include your database connection file
include_once "../../Controller/Database/Database.php"; 
$database = new Database(); 
$conn = $database->connect();

// Check if user is logged in
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'user') {
    $_SESSION['error'] = "Access denied.";

}

// Fetch user province from the database
$user_id = $_SESSION['id'];
$query = "SELECT province FROM users WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$province = $user['province'] ?? '';

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

// Determine the shipping fee based on the user's province
$shippingFee = $shippingFees[$province] ?? 0; 

// Fetch cart items for the logged-in user
$query = "SELECT * FROM cart WHERE user_id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if cart is empty
if (empty($cartItems)) {
    $_SESSION['error'] = "Your cart is empty.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-slate-100">

    <?php include "../includes/header.php"; ?>
    <br><br>
    <main class="p-6">
        <div class="max-w-5xl mx-auto bg-white rounded shadow-lg mt-10 p-4">
            <h2 class="text-2xl font-bold mb-4">Your Cart</h2>

            <?php if (!empty($cartItems)): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead>
                            <tr>
                                <th class="border border-gray-300 px-4 py-2">Item</th>
                                <th class="border border-gray-300 px-4 py-2">Province</th>
                                <th class="border border-gray-300 px-4 py-2">Price</th>
                                <th class="border border-gray-300 px-4 py-2">Quantity</th>
                                <th class="border border-gray-300 px-4 py-2">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $grandTotal = 0;
                            foreach ($cartItems as $item):
                                $total = $item['price'] * $item['product_quantity'];
                                $grandTotal += $total;
                            ?>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2 flex items-center">
                                    <img src="../uploads/<?php echo htmlspecialchars($item['product_image']); ?>" 
                                         alt="<?php echo htmlspecialchars($item['product_name']); ?>" 
                                         class="w-16 h-16 object-cover mr-4">
                                     <span class="text-2xl md:text-base"><?php echo htmlspecialchars($item['product_name']); ?></span>
                                     <span class="text-2xl md:text-base"> (<?php echo htmlspecialchars($item['product_type']); ?>)</span>
                                </td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($province); ?></td> <!-- Display user's province -->
                                <td class="border border-gray-300 px-4 py-2">₱<?php echo number_format($item['price'], 2); ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($item['product_quantity']); ?></td>
                                <td class="border border-gray-300 px-4 py-2">₱<?php echo number_format($total, 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <h3 class="text-lg text-right text-light">SubTotal: ₱<?php echo number_format($grandTotal, 2); ?></h3>
                    <hr>
                    <h3 class="text-lg text-right">Shipping Fee for <?php echo htmlspecialchars($province); ?>: ₱<?php echo number_format($shippingFee, 2); ?></h3>
                    <hr>
                    <h3 class="text-lg text-right font-bold">Grand Total: ₱<?php echo number_format($grandTotal + $shippingFee, 2); ?></h3>
                    <hr>
                    <div class="text-right mt-4">
                        <form action="checkout.php" method="GET">
                            <button type="submit" class="bg-yellow-600 text-white font-bold py-2 px-4 rounded hover:bg-yellow-500">Proceed to Checkout</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-red-500 text-lg">Your cart is empty.</p>
            <?php endif; ?>
        </div>
    </main>


</body>
</html>
