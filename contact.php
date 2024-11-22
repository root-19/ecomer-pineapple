<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Responsive Website with Video Background</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Custom styles for the background image and centered text */
    .bg-cover-custom {
      background-image: url('../Assets/images/IMG_1090.jpg');
      background-size: cover;
      background-position: center;
      height: 100vh; /* Full viewport height */
      position: relative; /* Required for positioning the text */
    }

    .centered-text  {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
      
    }
    p{
        opacity: 1.7;
    }
  </style>
</head>
<body class="bg-slate-50 text-white">
  <!-- Header -->
  <header class="fixed w-full top-0 bg-green-900 z-10">
    <div class="max-w-7xl mx-auto flex justify-between items-center p-4">
      <!-- Logo (aligned to the left) -->
      <div class="flex items-center space-x-2">
        <img src="../Assets/images/RJ FARM.jpeg" alt="RJ Farm" class="w-12 h-12 rounded-full">
        <span class="text-2xl font-bold">RJ Farm</span>
      </div>

      <!-- Toggle button (for mobile) -->
      <button id="menu-toggle" class="lg:hidden text-white focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
        </svg>
      </button>

      <!-- Nav links -->
      <nav class="hidden lg:flex space-x-6 font-bold" id="nav-menu">
        <a href="../index.php" class="hover:text-yellow-400">Home</a>
        <a href="about.php" class="hover:text-yellow-400">About</a>
        <a href="contact.php" class="hover:text-yellow-400">Contact</a>
        <a href="../public/signin.php" class="hover:text-yellow-400">Account</a>
      </nav>
    </div>

    <!-- Mobile menu -->
    <nav id="mobile-menu" class="lg:hidden hidden">
      <ul class="bg-green-900 bg-opacity-80 font-bold">
        <li><a href="../index.php" class="block py-2 px-4 hover:bg-yellow-400">Home</a></li>
        <li><a href="about.php" class="block py-2 px-4 hover:bg-yellow-400">About</a></li>
        <li><a href="contact.php" class="block py-2 px-4 hover:bg-yellow-400">Contact</a></li>
        <li><a href="../public/signin.php" class="block py-2 px-4 hover:bg-yellow-400">Account</a></li>
      </ul>
    </nav>
  </header>

  <!-- Main section with full background image -->
  <main class="bg-cover-custom">
    <!-- Centered text -->
    <div class="centered-text">
      <h1 class="text-6xl font-bold text-white">Contact Us</h1>
    </div>
    
  </main>
  <section class="bg-green-200 text-gray-900 p-8 rounded-lg shadow-lg max-w-3xl mx-auto mt-8">
  <h2 class="text-xl font-bold mb-4">Contact Us</h2>
  <p class="text-sm mb-2">
    Our customer service office hours are from 8:00 AM to 4:00 PM HST daily. Please allow 48 hours for our staff to investigate your query and get back to you. If you have already placed an order, please include your order number with your message.
  </p>
  
  <div class="mb-4">
    <span class="font-bold">Phone:</span> 0991 414 8965<br>
    <span class="font-bold">Email:</span> info@rjfarm.com
  </div>

  <div class="mb-4">
    <span class="font-bold">For Media Inquiries:</span><br>
    Jenerate PR<br>
    Jennifer Polito
  </div>
  
  <div>
    <span class="font-bold">Email:</span> jennifer@jeneratepr.com<br>
    <span class="font-bold">Phone:</span> +639 914148965
  </div>
</section>

  <script>
    // Toggle mobile menu
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    menuToggle.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });
</script>
</body>
</html>