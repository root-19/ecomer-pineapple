<?php
// Start the session
session_start();



include_once "../../Controller/Database/Database.php"; 
$database = new Database(); 
$conn = $database->connect();

// Check if the user is logged in
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'user') {
    $_SESSION['error'] = "Access denied.";
    exit;
}

$user_id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];
    $productType = $_POST['product_type']; // Get the product type from the form

    // Insert review into the database
    $stmt = $conn->prepare("INSERT INTO reviews (user_id, product_id, rating, review, review_date, product_type) VALUES (?, ?, ?, ?, NOW(), ?)");
    $result = $stmt->execute([$user_id, $productId, $rating, $review, $productType]);

    echo json_encode(['success' => $result]);
    exit;
}
// error_log($_POST);
// Product types
$productTypes = ["Kwarta", "Primera", "segunda", "tercera", "semilya", "ano paba haha"];
include_once "../includes/header.php"; 
?>

<title>My Product Reviews</title>

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
</head>
<br><br><br><br><br><br>

<div class="container mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Loop through six boxes with product type for each -->
    <?php for ($i = 1; $i <= 6; $i++): ?>
    <div class="bg-white rounded-lg shadow-lg p-4 flex flex-col items-center">
    <h3 class="text-lg font-semibold mb-2 text-black"><?= $productTypes[$i - 1] ?></h3>
        <!-- Image Placeholder -->
        <img src="../uploads/kwatra.PNG" alt="Product Image" class="w-32 h-32 object-cover rounded-full mb-4">
        <!-- Hidden input for product type -->
        <input type="hidden" id="product-type-<?= $i ?>" value="<?= $productTypes[$i - 1] ?>">
        <!-- Star Rating -->
        <div class="flex space-x-1 mb-4" id="star-rating-<?= $i ?>">
            <?php for ($j = 1; $j <= 5; $j++): ?>
            <button type="button" class="star" onclick="setRating(<?= $i ?>, <?= $j ?>)">
                ★
            </button>
            <?php endfor; ?>
        </div>
        <!-- Textarea for Review -->
        <textarea id="review-<?= $i ?>" class="w-full border rounded-lg p-2 mb-4 text-black" placeholder="Write your review..."></textarea>
        <!-- Submit Button -->
        <button onclick="submitReview(<?= $i ?>)" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600">Send Review</button>
    </div>
    <?php endfor; ?>
</div>
<h1>jsjsjsj</h1>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">

<script>
    function setRating(productId, rating) {
        const stars = document.querySelectorAll(`#star-rating-${productId} .star`);
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('selected');
            } else {
                star.classList.remove('selected');
            }
        });
        document.querySelector(`#review-${productId}`).dataset.rating = rating;
    }

    function submitReview(productId) {
        const rating = document.querySelector(`#review-${productId}`).dataset.rating;
        const review = document.querySelector(`#review-${productId}`).value;
        const productType = document.querySelector(`#product-type-${productId}`).value;

        if (rating && review && productType) {
            fetch(window.location.href, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `product_id=${productId}&rating=${rating}&review=${encodeURIComponent(review)}&product_type=${encodeURIComponent(productType)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    swal("Success!", "Review submitted successfully!", "success");
                    document.querySelector(`#review-${productId}`).value = '';
                    document.querySelector(`#review-${productId}`).dataset.rating = '';
                    setRating(productId, 0); 
                } else {
                    swal("Error!", "Error submitting review.", "error");
                }
            });
        } else {
            swal("Warning!", "Please select a rating, write a review, and ensure product type is defined.", "warning");
        }
    }
</script>

<?php include "../includes/footer.php"; ?>
