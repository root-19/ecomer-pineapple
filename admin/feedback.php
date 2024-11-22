<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once "../../Controller/Database/Database.php"; 
$database = new Database(); 
$conn = $database->connect();

// Check if the user is logged in as admin
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Access denied.";
    exit;
}

// Fetch all reviews
$query = "
    SELECT 
        id AS review_id, 
        user_id, 
        product_id, 
        rating, 
        review, 
        review_date 
    FROM 
        reviews 
    ORDER BY 
        review_date DESC";

try {
    $result = $conn->query($query);
    $reviews = $result->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching reviews: " . $e->getMessage();
    exit;
}


?>
<?php include "../includes/header_admin.php"; ?> 
 <!-- <h1 class="text-center text-5xl mt-10 bold"> yamate kudasai</h1> -->
 <title>User Feedback</title> 

 <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">User Feedback</h1>

    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Review ID</th>
                <th class="py-2 px-4 border-b">User ID</th>
                <th class="py-2 px-4 border-b">Product ID</th>
                <th class="py-2 px-4 border-b">Rating</th>
                <th class="py-2 px-4 border-b">Review</th>
                <th class="py-2 px-4 border-b">Review Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reviews as $review): ?>
            <tr>
                <td class="py-2 px-4 border-b"><?= htmlspecialchars($review['review_id']) ?></td>
                <td class="py-2 px-4 border-b"><?= htmlspecialchars($review['user_id']) ?></td>
                <td class="py-2 px-4 border-b"><?= htmlspecialchars($review['product_id']) ?></td>
                <td class="py-2 px-4 border-b"><?= htmlspecialchars($review['rating']) ?></td>
                <td class="py-2 px-4 border-b"><?= htmlspecialchars($review['review']) ?></td>
                <td class="py-2 px-4 border-b"><?= htmlspecialchars($review['review_date']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div> 


