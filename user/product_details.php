<?php
// Start the session
session_start();

// Include database connection file
include_once "../../Controller/Database/Database.php"; 
$database = new Database(); 
$conn = $database->connect();

// Ensure user is logged in
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'user') {
    $_SESSION['error'] = "Access denied.";
    header("Location: signin.php");
    exit();
}


$product_id = $_GET['product_id'];

// Fetch the product details from the database
$query = "SELECT * FROM products WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);



// Handle Add to Cart functionality (as shown earlier in your request)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $user_id = $_SESSION['id'];
  
    // Ensure product price exists and is not null
    if (isset($product['price']) && !is_null($product['price'])) {
        $add_cart_query = "INSERT INTO cart (user_id, price, product_image, product_name, product_quantity, product_type) 
                           VALUES (:user_id, :price, :image, :name, :quantity, :type)";
        $stmt = $conn->prepare($add_cart_query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':price', $product['price'], PDO::PARAM_STR);
        $stmt->bindParam(':image', $product['image']);
        $stmt->bindParam(':name', $product['name']);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':type', $product['type']);

        if ($stmt->execute()) {
            $_SESSION['cart_success'] = true;
            $_SESSION['cart_product_name'] = $product['name'];
            $_SESSION['cart_product_type'] = $product['type'];
            $_SESSION['cart_product_quantity'] = $quantity;
        } else {
            $_SESSION['error'] = "Failed to add product to cart.";
        }
    } else {
        $_SESSION['error'] = "Product price is not set or is null.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .swal2-confirm {
            background-color: #eab308 !important;
            color: white !important;
        }
        .swal2-confirm:hover {
            background-color: #d7a206 !important;
        }
        .buy-now-btn {
            background-color: #eab308;
        }
        .buy-now-btn:hover {
            background-color: #d7a206;
        }
    </style>
</head>
<body class="bg-slate-100">
<?php include "../includes/header.php"; ?>

<main class="p-6">
    <form method="POST" action="product_details.php?product_id=<?php echo htmlspecialchars($product['id']); ?>">
        <div class="max-w-2xl mx-auto border rounded p-4 bg-white mt-40 shadow-lg flex flex-col lg:flex-row items-center">
            <div class="lg:w-1/3 flex justify-center lg:justify-start mb-4 lg:mb-0">
                <div class="overflow-hidden rounded-md w-64 h-64 border border-gray-300">
                    <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-full object-cover">
                </div>
            </div>

            <div class="lg:w-2/3 lg:pl-6 text-left">
                <h2 class="text-3xl font-bold mb-2"><?php echo htmlspecialchars($product['name']); ?> <span class="text-gray-500">(<?php echo ($product['type']); ?>)</span></h2>
                <p class="text-xl text-green-700 font-semibold mb-2">₱<?php echo ($product['price']); ?></p>
                
                <div class="flex items-center mb-4">
                    <label for="quantity" class="mr-3 text-gray-600">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" class="w-16 border border-gray-300 rounded px-2 py-1 text-center" value="1" min="1" required>
                </div>

                <div class="flex space-x-4 mb-4">
                    <button type="submit" name="add_to_cart" class="bg-green-600 text-white px-5 py-2 rounded hover:bg-green-500">Add to Cart</button>
                    <button type="button" class="buy-now-btn text-white px-5 py-2 rounded">Buy Now</button>
                </div>

                <div class="flex space-x-4 text-gray-500 text-xl">
                    <a href="#" class="hover:text-gray-800"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="hover:text-gray-800"><i class="fas fa-share"></i></a>
                </div>
            </div>
        </div>
    </form>

    <div class="max-w-5xl mx-auto mt-4 p-4 border-t border-gray-300">
        <h3 class="text-xl font-semibold">Description</h3>
        <p class="text-gray-600 mt-2">
            <!-- Add product description here -->
        </p>
    </div>
</main>

<script>
    <?php if (isset($_SESSION['cart_success']) && $_SESSION['cart_success']): ?>
        Swal.fire({
            title: 'Success!',
            text: '<?php echo htmlspecialchars($_SESSION['cart_product_name']); ?> has been added to your cart.',
            icon: 'success',
            confirmButtonText: 'OK',
            customClass: {
                confirmButton: 'swal2-confirm'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "products.php";
            }
        });
        <?php unset($_SESSION['cart_success']); ?>
    <?php endif; ?>

    document.querySelector('.buy-now-btn').addEventListener('click', function() {
        Swal.fire({
            title: 'Proceed to Checkout',
            text: 'Would you like to proceed with this order?',
            icon: 'info',
            confirmButtonText: 'Proceed',
            customClass: {
                confirmButton: 'swal2-confirm'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "checkout-page.php";
            }
        });
    });
</script>

</body>
</html>
