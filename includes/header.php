<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is a regular user
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'user') {
    $_SESSION['error'] = "Access denied.";

}

// Include the database connection file
include_once "../../Controller/Database/Database.php"; 
$database = new Database(); 
$conn = $database->connect();

// Get the user ID from session
$userId = $_SESSION['id']; 

// Fetch the cart count for the user
$cartQuery = "SELECT COUNT(*) AS cart_count FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($cartQuery);
$stmt->execute([$userId]);
$cartResult = $stmt->fetch(PDO::FETCH_ASSOC);
$cartCount = $cartResult ? $cartResult['cart_count'] : 0;

// Query to count unread messages for a specific user where the sender is an admin
$query = "SELECT COUNT(*) AS unread_count 
          FROM messages 
          WHERE recipient_id = :user_id 
            AND is_read = 0 
            AND is_admin = 1";

// Prepare the statement
$stmt = $conn->prepare($query);

// Bind the user_id to the query
$stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);

// Execute the query
$stmt->execute();

// Fetch the result
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Get the unread message count
$unreadCount = $result ? $result['unread_count'] : 0;


// Fetch the user's image and name from the users table
$userQuery = "SELECT first_name, last_name, user_image FROM users WHERE id = ?";
$stmt = $conn->prepare($userQuery);
$stmt->execute([$userId]);
$userResult = $stmt->fetch(PDO::FETCH_ASSOC);


$userName = $userResult ? $userResult['first_name'] . ' ' . $userResult['last_name'] : 'Guest';
$userImage = $userResult && $userResult['user_image'] ? $userResult['user_image'] : 'default-avatar.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RJ Farm</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- SweetAlert CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
  <!-- SweetAlert JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
</head>
<body class="bg-slate-50 text-white">
  <!-- Header -->
  <header class="fixed w-full top-0 bg-black bg-green-900 z-10">
    <div class="max-w-7xl mx-auto flex justify-between items-center p-4">
      <!-- Logo (aligned to the left) -->
      <div class="flex items-center space-x-2">
        <!-- <img src="../../../Assets/images/RJ FARM.jpeg" alt="RJ Farm" class="w-12 h-12 rounded-full"> -->
        <img src="../uploads/<?php echo htmlspecialchars($userImage); ?>" alt="user_image" class="w-12 h-12 rounded-full">

        
        <span class="text-2xl font-bold text-white"><?php echo htmlspecialchars($userName); ?></span>
      </div>

      <!-- Toggle button (for mobile) -->
      <button id="menu-toggle" class="lg:hidden text-white focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
        </svg>
      </button>

      <!-- Nav links -->
      <nav class="hidden lg:flex space-x-6 font-bold text-white" id="nav-menu">
        <a href="../user/products.php" class="hover:text-yellow-400 text-white">Home</a>

        <div class="relative inline-block">
          <a href="../user/message.php" class="hover:text-yellow-400 text-white">
            Message
            <?php if ($unreadCount > 0): ?>
                <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                    <?php echo $unreadCount; ?>
                </span>
            <?php endif; ?>
          </a>
        </div>


        <a href="../user/packing.php" class="hover:text-yellow-400 text-white">Purchase</a>
        <!-- <a href="../user/review.php" class="hover:text-yellow-400 text-white">Feedback</a> -->
        <a href="../user/account.php" class="hover:text-yellow-400 text-white">Account</a>
        
        <!-- Cart icon with notification badge -->
        <div class="relative inline-block">
          <a href="../user/cart-box.php" class="hover:text-yellow-400 text-white flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mb-1 h-6 w-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
            </svg>
            <?php if ($cartCount > 0): ?>
              <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                <?= $cartCount; ?>
              </span>
            <?php endif; ?>
          </a>
        </div>

        <!-- Logout Icon -->
        <a href="../user/logout.php" class="flex items-center hover:text-yellow-400 text-white">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mb-4 h-6 w-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 3v3m0-3H5.25A2.25 2.25 0 003 5.25v13.5A2.25 2.25 0 005.25 21h5.25m4.5-6l6-6m0 0l-6-6m6 6H9" />
          </svg>
        </a>
      </nav>
    </div>
  </header>

  <!-- Mobile Menu -->
  <nav class="lg:hidden bg-green-900 fixed top-0 left-0 w-full h-full z-20 hidden" id="mobile-nav">
    <div class="flex justify-end p-4">
      <button id="close-menu" class="text-white">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>
    <div class="p-6">
      <a href="../user/products.php" class="block text-white py-2">Home</a>
      <a href="../user/message.php" class="block text-white py-2">Message</a>
      <a href="../user/packing.php" class="block text-white py-2">Purchase</a>
      <!-- <a href="../user/review.php" class="block text-white py-2">Feedback</a> -->
      <a href="../user/account.php" class="block text-white py-2">Account</a>
    </div>
  </nav>

  <script>
    // Toggle mobile menu visibility
    const menuToggle = document.getElementById("menu-toggle");
    const mobileNav = document.getElementById("mobile-nav");
    const closeMenu = document.getElementById("close-menu");

    menuToggle.addEventListener("click", () => {
      mobileNav.classList.toggle("hidden");
    });

    closeMenu.addEventListener("click", () => {
      mobileNav.classList.add("hidden");
    });
  </script>
</body>
</html>
