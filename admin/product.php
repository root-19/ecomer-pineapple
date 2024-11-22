<?php
session_start();

// Include your database connection file
include_once "../../Controller/Database/Database.php"; 
$database = new Database(); 
$conn = $database->connect();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Access denied.";

}

// Fetch all products from the 'products' table
$query = "SELECT id, image, name, type, price, quantity FROM products";
$result = $conn->query($query);

// Handle the form submission for updating product details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $product_id = $_POST['product_id'];
    $new_name = $_POST['product_name'];
    $new_price = $_POST['product_price'];
    $new_quantity = $_POST['product_quantity'];

    // Update the product details in the database using PDO
    $update_query = "UPDATE products SET name = :name, price = :price, quantity = :quantity WHERE id = :id";
    $stmt = $conn->prepare($update_query);
    
    // Bind parameters
    $stmt->bindValue(':name', $new_name, PDO::PARAM_STR);
    $stmt->bindValue(':price', $new_price, PDO::PARAM_STR);
    $stmt->bindValue(':quantity', $new_quantity, PDO::PARAM_INT);
    $stmt->bindValue(':id', $product_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirect to avoid form resubmission
        header('Location: product.php');
        exit();
    } else {
        echo "Error updating record: " . $stmt->errorInfo()[2];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        function openModal(product) {
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('product_id').value = product.id;
            document.getElementById('product_name').value = product.name;
            document.getElementById('product_price').value = product.price;
            document.getElementById('product_quantity').value = product.quantity;
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }
    </script>
</head>
<body class="bg-gray-100">
<?php include "../includes/header_admin.php"; ?>
    <div class="max-w-5xl mx-auto bg-white rounded shadow-lg mt-5 p-4">
        <h2 class="text-xl font-bold mb-4">Manage Products</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left">Product Image</th>
                        <th class="px-6 py-3 text-left">Product Name</th>
                        <th class="px-6 py-3 text-left">Product Type</th>
                        <th class="px-6 py-3 text-left">Price</th>
                        <th class="px-6 py-3 text-left">Quantity</th>
                        <th class="px-6 py-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->rowCount() > 0): ?>
                        <?php while ($product = $result->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <!-- Display product details -->
                                <td class="border px-6 py-4">
                                    <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" class="h-16 w-16 object-cover rounded">
                                </td>
                                <td class="border px-6 py-4"><?php echo htmlspecialchars($product['name']); ?></td>
                                <td class="border px-6 py-4"><?php echo htmlspecialchars($product['type']); ?></td>
                                <td class="border px-6 py-4"><?php echo htmlspecialchars($product['price']); ?></td>
                                <td class="border px-6 py-4"><?php echo htmlspecialchars($product['quantity']); ?></td>
                                <td class="border px-6 py-4">
                                    <button onclick='openModal(<?php echo json_encode($product); ?>)' class="bg-yellow-500 text-white px-3 py-1 rounded">Update</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="border px-6 py-4 text-center">No products found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for updating product details -->
    <div id="modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded shadow-lg">
            <h3 class="text-lg font-bold mb-4">Update Product</h3>
            <form method="POST" action="">
                <input type="hidden" name="product_id" id="product_id">
                <div class="mb-4">
                    <label for="product_name" class="block">Product Name</label>
                    <input type="text" name="product_name" id="product_name" class="border rounded p-2 w-full" required>
                </div>
                <div class="mb-4">
                    <label for="product_price" class="block">Price</label>
                    <input type="text" name="product_price" id="product_price" class="border rounded p-2 w-full" required>
                </div>
                <div class="mb-4">
                    <label for="product_quantity" class="block">Quantity</label>
                    <input type="number" name="product_quantity" id="product_quantity" class="border rounded p-2 w-full" required>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal()" class="bg-gray-300 px-4 py-2 rounded mr-2">Cancel</button>
                    <button type="submit" name="update_product" class="bg-yellow-500 text-white px-4 py-2 rounded">Update</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
