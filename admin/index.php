<?php
// session_start();
include "../../Model/Product.php";
include "../../Model/Admin.php";


// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Access denied.";
  
}




if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $image = $_FILES['image']['name'];
        $name = $_POST['name'];
        $type = $_POST['type'];
        $price = $_POST['price'];

        $quantity = $_POST['quantity'];
        $date = $_POST['date'];

        // Upload the image to the server
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $image);

        $product = new Product($image, $name,$price, $type, $quantity, $date);
        $admin = new Admin();

        if ($admin->addProduct($product)) {
            echo "<div class='bg-green-500 text-white p-3 rounded mb-4'>Product added successfully!</div>";
        } else {
            echo "<div class='bg-red-500 text-white p-3 rounded mb-4'>Failed to add product.</div>";
        }
    } catch (Exception $e) {
        echo "<div class='bg-red-500 text-white p-3 rounded mb-4'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<?php include "../includes/header_admin.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="max-w-md mx-auto mt-8 bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4 text-center">Add Product</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="grid gap-4">
            <!-- Image Upload -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                <input type="file" name="image" required class="w-full border border-gray-300 p-1 rounded-sm text-sm">
            </div>

            <!-- Product Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                <input type="text" name="name" required class="w-full border border-gray-300 p-1 rounded-sm text-sm" placeholder="Enter product name">
            </div>

            <!-- Product Type -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                <select name="type" required class="w-full border border-gray-300 p-1 rounded-sm text-sm">
                    <option value="preimera">Preimera</option>
                    <option value="segunda">Segunda</option>
                    <option value="tresera">Tresera</option>
                    <option value="kwadra">Kwadra</option>
                    <option value="punla">Punla</option>
                </select>
            </div>

            <!-- Quantity -->
            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                <input type="number" name="quantity" required class="w-full border border-gray-300 p-1 rounded-sm text-sm" placeholder="Enter quantity">
            </div>

            <!-- Price -->
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                <input type="number" name="price" required class="w-full border border-gray-300 p-1 rounded-sm text-sm" placeholder="Enter price">
            </div>

            <!-- Date -->
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" name="date" required class="w-full border border-gray-300 p-1 rounded-sm text-sm">
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-4 text-center">
            <button type="submit" class="w-full bg-green-600 text-white font-medium py-2 rounded-md hover:bg-green-600 transition text-sm">
                Add Product
            </button>
        </div>
    </form>
</div>
