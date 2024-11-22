<?php
session_start();
require_once '../../Controller/Database/Database.php';

$database = new Database();
$conn = $database->connect(); 

// Check if user is logged in and is a regular user
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'user') {
    $_SESSION['error'] = "Access denied.";
 
}

$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

if (empty($email)) {
    // $user_id = $_SESSION['id'];

    // Fetch email from the database
    $query = "SELECT email FROM users WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $email = $stmt->fetchColumn();

    if (!$email) {
        $email = 'Unknown email';
    } else {
        $_SESSION['email'] = $email; // Store email in session
    }
}

$email = htmlspecialchars($email);
?>
<br><br>
<br><br>
<br><br>

<?php include_once "../includes/header.php"; ?>
<main class="mt-16 p-6 text-green-800">
    <h1 class="text-green-900 text-center text-6xl">YOUR ACCOUNT HAS BEEN CREATED</h1>
    <p class="text-green-900 text-center">Thank you for creating your account at RJ Farm. Your account details have been emailed <br> to <?php echo $email; ?>
    Your account is ready to use!</p>
    <br>
    <button onclick="sign()"
    class="w-[150px] mx-auto flex ml-50 bg-yellow-600 text-white py-1 px-4 text-sm rounded-md hover:bg-yellow-700">
    Continue Shopping
</button>
</main>

<script> 
    function sign(){
        window.location.href="./products.php"
    }
</script>
