<?php

// session_start();

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
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);

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
<!-- Main Layout Container -->
<div class="flex min-h-screen">

  
    <!-- Content Area for the Form -->
    <div class="flex-1 p-8 bg-gray-100">
        <div class="max-w-lg bg-white p-6 rounded-lg shadow-lg mx-auto">
            <h2 class="text-2xl font-bold mb-6">Add Product</h2>
            <form action="" method="POST" enctype="multipart/form-data">

                <div class="mb-4">
                    <label for="image" class="block text-gray-700 font-semibold mb-2">Image:</label>
                    <input type="file" name="image" required class="border border-gray-300 p-2 rounded w-full">
                </div>

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-semibold mb-2">Product Name:</label>
                    <input type="text" name="name" required class="border border-gray-300 p-2 rounded w-full">
                </div>

                <div class="mb-4">
                    <label for="type" class="block text-gray-700 font-semibold mb-2">Type:</label>
                    <select name="type" required class="border border-gray-300 p-2 rounded w-full">
                        <option value="preimera">Preimera</option>
                        <option value="segunda">Segunda</option>
                        <option value="tresera">Tresera</option>
                        <option value="kwadra">Kwadra</option>
                        <option value="punla">Punla</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="quantity" class="block text-gray-700 font-semibold mb-2">Quantity:</label>
                    <input type="number" name="quantity" required class="border border-gray-300 p-2 rounded w-full">
                </div>
                <div class="mb-4">
                    <label for="quantity" class="block text-gray-700 font-semibold mb-2">price:</label>
                    <input type="number" name="price" required class="border border-gray-300 p-2 rounded w-full">
                </div>
                <div class="mb-4">
                    <label for="date" class="block text-gray-700 font-semibold mb-2">Date:</label>
                    <input type="date" name="date" required class="border border-gray-300 p-2 rounded w-full">
                </div>

                <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition">Add Product</button>
            </form>
        </div>
    </div>
</div>
