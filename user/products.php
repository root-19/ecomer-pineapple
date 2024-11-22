<?php


// Include header
include_once "../includes/header.php"; 

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'user') {
    $_SESSION['error'] = "Access denied.";

}

// Include your database connection file
include_once "../../Controller/Database/Database.php"; 
$database = new Database(); 
$conn = $database->connect();

// Initialize the product types array
$product_types = [
    'preimera' => [],
    'segunda'  => [],
    'tresera'  => [],
    'kwadra'   => [],
    'punla'    => []
];

// Query to fetch products based on type
$query = "SELECT * FROM products ORDER BY type";
$result = $conn->query($query);

// Check if any products were fetched
if ($result) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        // Ensure the type exists in the product_types array before adding
        if (array_key_exists($row['type'], $product_types)) {
            $product_types[$row['type']][] = $row;
        }
    }
}

// Fetch all announcements
$announcements = $conn->query("SELECT * FROM announcements ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RJ Farm</title>
    <script src="https://cdn.tailwindcss.com"></script>
   
</head>
<body class="bg-slate-100">

<br><br><br>
<main class="p-6">
    <h2 class="text-3xl font-bold mb-6 text-black">SHOP</h2>
    <?php if (count($announcements) > 0): ?>
        <?php foreach ($announcements as $announcement): ?>
    <p class="text-center mb-4 text-black"><?= nl2br(htmlspecialchars($announcement['content'])); ?> <?= date("F j, Y, g:i a", strtotime($announcement['created_at'])); ?></p>
    <?php endforeach; ?>
    <?php else: ?>
        <p class="text-center text-gray-600"></p>
    <?php endif; ?>

    <!-- Loop through product types and display products -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <?php foreach ($product_types as $type => $products): ?>
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <!-- Product Card -->
                    <div class="border rounded-lg p-4 flex flex-col items-center shadow-lg">
                        <div class="overflow-hidden rounded-md w-48 h-48">
                            <img src="../uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="w-full h-full object-cover transition-transform duration-300 transform hover:scale-105 mb-2">
                        </div>
                        <h3 class="font-semibold text-black"><?php echo $product['name']; ?></h3>
                        <h3 class="text-2xl font-bold mb-4 text-black"><?php echo ucfirst($type); ?></h3>
                        <p class="text-gray-600 text-black">₱<?php echo number_format($product['price'], 2); ?></p> <!-- Updated price field -->
                        <form action="view-product.php" method="POST">
                            <!-- Use 'id' instead of 'product_id' -->
                            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                            <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded mt-2 hover:bg-yellow-500">View</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</main>

</body>
</html>
<?php include "../includes/footer.php"; ?>