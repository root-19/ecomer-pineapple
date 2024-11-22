<?php
session_start();
include "./Controller/Database/Database.php";

// Initialize database connection
$database = new Database();
$conn = $database->connect();

// User authentication class
class User {
    private $userId;
    private $role;

    public function __construct() {
        if (isset($_SESSION['user_id'])) {
            $this->userId = $_SESSION['user_id'];
            $this->role = $_SESSION['role'];
        }
    }

    public function isAuthenticated() {
        return isset($this->userId) && $this->role === 'user';
    }
}

// // Class for managing the pineapple pledge
// class Pineapple {
//     private $pledgeText;

//     public function __construct() {
//         $this->pledgeText = "The Maui community needs our help...";
//     }

//     public function getPledgeText() {
//         return $this->pledgeText;
//     }
// }

// // Class for handling the image carousel
// class Carousel {
//     private $images;

//     public function __construct($images) {
//         $this->images = $images;
//     }

//     public function getImages() {
//         return $this->images;
//     }
// }



$user = new User();
if (!$user->isAuthenticated()) {

        $ipAddress = $_SERVER['REMOTE_ADDR']; // Get the IP address of the visitor
    $visitTime = date('Y-m-d H:i:s'); 

    $stmt = $conn->prepare("INSERT INTO visitors (ip_address, visit_time) VALUES (:ip_address, :visit_time)");

    $stmt->bindParam(':ip_address', $ipAddress);
    $stmt->bindParam(':visit_time', $visitTime);


    if ($stmt->execute()) {
      
    } else {
        echo "Failed to record visitor.";
    }


    // header("Location: ./public/signin.php");
    // exit();
}


$conn = null;




?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Responsive Website with Video Background</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
     .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 50; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
            border-radius: 8px;
        }
    video {
      object-fit: cover;
    }
    .bg-green-900 {
      background-color: #14532d; /* Tailwind green */
    }
    .image-container {
      position: relative;
      overflow: hidden; /* Hide overflow for the dark overlay */
      height: 50vh;
    }
    .image-container img {
      object-fit: cover;
      transition: transform 0.5s ease; 
      height: 100%; /* Ensure the image covers the container's height */
      width: 100%;
  
    }
    .overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black overlay */
      opacity: 0; /* Initially hidden */
      transition: opacity 0.5s ease; /* Smooth transition for overlay */
    }
    .image-container:hover .overlay {
      opacity: 1; /* Show overlay on hover */
    }
    .buy-now {
     /* width: 30px; */
      position: absolute;
      bottom: 20%;
      left: 50%;
      transform: translateX(-50%); 
      background-color: #fbbf24; 
      color: black;
      padding: 0.5rem 1rem; 
      border-radius: 0.375rem; 
      opacity: 0;
      transition: opacity 0.5s ease;
    }
    .image-container:hover .buy-now {
      opacity: 1; 
    }

            
            .carousel-container {
            position: relative;
            overflow: hidden; 
        }

        .carousel-images {
            display: flex;
            transition: transform 0.5s ease; 
        }

        .carousel-image {
            min-width: 25%; 
            height: auto;
            transition: transform 1.3s ease;
        }
      
        .carousel-image:hover {
            transform: scale(1.25);
          
        }

        @media (min-width: 640px) { /* md: breakpoint */
            .carousel-image {
                min-width: 25%; /* Each image takes up 25% on medium and larger screens */
            }
        }


        .dot {
            height: 12px;
            width: 12px;
            margin: 0 4px;
            background-color: #bbb; 
            border-radius: 50%;
            display: inline-block;
            cursor: pointer; 
        }

        .dot.active {
            background-color: #fbbf24; 
        }
  </style>
</head>
<body class="bg-slate-50 text-white">
  <!-- Header -->
  <header class="fixed w-full top-0 bg-black bg-opacity-0 z-10">
    <div class="max-w-7xl mx-auto flex justify-between items-center p-4">
      <!-- Logo (aligned to the left) -->
      <div class="flex items-center space-x-2">
        <img src="./Assets/images/RJ FARM.jpeg" alt="RJ Farm" class="w-12 h-12 rounded-full">
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
        <a href="index.php" class="hover:text-yellow-400">Home</a>
        <a href="./Views/about.php" class="hover:text-yellow-400">About</a>
        <a href="./Views/contact.php" class="hover:text-yellow-400">Contact</a>
        <a href="./public/signin.php" class="hover:text-yellow-400">Account</a>
      </nav>
    </div>

    <!-- Mobile menu -->
    <nav id="mobile-menu" class="lg:hidden hidden flex items-center">
      <ul class="bg-green-900 bg-opacity-80 font-bold ">
      <a href="index.php" class="block py-2 px-4 hover:bg-yellow-400 text-white">Home</a>

        <a href="./Views/about.php" class="block py-2 px-4 hover:bg-yellow-400 text-white">About</a>

        <a href="./Views/contact.php" class="block py-2 px-4 hover:bg-yellow-400 text-white">Contact</a>

        <a href="./public/signin.php" class="block py-2 px-4 hover:bg-yellow-400 text-white">Account</a>
    
      </ul>
    </nav>
  </header>

  <main class="h-screen relative flex justify-center items-center">
    <!-- Video Background -->
    <video class="absolute top-0 left-0 w-full h-full" autoplay muted loop>
      <source src="./Assets/images/FULL VIDEO.mp4" type="video/mp4">
      Your browser does not support the video tag.
    </video>

    <!-- Text Overlay on Left Side -->
    <div class="absolute left-8 top-1/2 transform -translate-y-1/2 text-white text-5xl font-bold">
      RJ Farm
      <p class="text-2xl font-bold">Hawai’i’s Famously Sweet Pineapple Grown on the <br> Slopes of Haleakala for more than 50 years</p>
    </div>

   <!-- Chatbot Button -->
<button id="chatbot-button" class="absolute bottom-8 right-8 bg-blue-500 text-white rounded-full p-3 shadow-lg hover:bg-blue-600 transition duration-300">
    <i class="fas fa-robot"></i> Chatbot
</button>

<!-- Chatbot Modal -->
<div id="chatbot-modal" class="modal hidden">
    <div class="modal-content">
        <span id="close-modal" class="text-red-500 cursor-pointer float-right">&times;</span>
        <div id="chatbot-content">
            <div class="p-4 border-b border-gray-200 flex items-center justify-center">
            <i class="fas fa-robot text-3xl text-blue-600 mr-2"></i>
            <h2 class="text-2xl font-bold text-center text-blue-600">Product Chatbot</h2>
            </div>
            <div class="chat-box p-4 mb-4 border border-gray-300 text-black rounded-lg bg-gray-50">
                <div id="chat-output" class="space-y-2"></div>
            </div>
            <div class="p-4">
                <div class="flex">
                    <input type="text" id="user-input" class="border border-gray-300 rounded-l-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 text-black" placeholder="Ask about our products...">
                    <button id="send-btn" class="bg-blue-500 text-white rounded-r-lg p-2 hover:bg-blue-600 transition duration-300">Send</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="./Assets/js/ch">

</script>
<style>
    .modal {
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background-color: white;
        border-radius: 8px;
        max-width: 500px;
        width: 100%;
    }

    .hidden {
        display: none;
    }
</style>




  </main>

  <section class="max-w-7xl mx-auto p-8 flex flex-col md:flex-row items-center">
    <!-- Left Text Content -->
    <div class="w-full md:w-1/2 pr-8 mb-4 md:mb-0">
      <h2 class="text-3xl font-bold text-green-800 mb-4">PINEAPPLE PLEDGE</h2>
      <p class="text-gray-700 mb-4">
        The Maui community needs our help. The recent wildfires that devastated Maui have left thousands of people displaced, without food, water, and shelter. Join us in support of our beloved Maui community as we band together to uplift those who have been impacted. With every purchase of a Maui Gold Pineapple, we will donate to relief organizations providing aid. Your support will also help protect farm jobs and pineapple production operations. Mahalo for your support.
      </p>
      <div class="flex space-x-4">
        <a href="#" class="bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-600 transition duration-300">SUPPORT</a>
      </div>
    </div>
  
    <!-- Right Image -->
    <div class="w-full md:w-1/2">
      <img src="./Assets/images/RJ FARM.jpeg" alt="Pineapple Harvest" class="w-full h-auto rounded-lg shadow-lg">
    </div>
  </section>

  
  <section class="max-w-7xl mx-auto p-8 flex flex-col md:flex-row items-center">

     <!-- Right Image -->
     <div class="w-full md:w-1/2">
        <img src="./Assets/images/RJ FARM.jpeg" alt="Pineapple Harvest" class="w-full h-auto rounded-lg shadow-lg">
      </div>

    <!-- Left Text Content -->
    <div class="w-full md:w-1/2 pr-8 mb-4 md:mb-0">
      <h2 class="text-3xl font-bold text-green-800 mb-4 ml-4">PINEAPPLE PLEDGE</h2>
      <p class="text-gray-700 mb-4 ml-4">
        The Maui community needs our help. The recent wildfires that devastated Maui have left thousands of people displaced, without food, water, and shelter. Join us in support of our beloved Maui community as we band together to uplift those who have been impacted. With every purchase of a Maui Gold Pineapple, we will donate to relief organizations providing aid. Your support will also help protect farm jobs and pineapple production operations. Mahalo for your support.
      </p>
      <div class="flex space-x-4 ml-4">
        <a href="#" class="bg-yellow-500 text-white ml-4 py-2 px-4 rounded hover:bg-yellow-600 transition duration-300">SUPPORT</a>
      </div>
    </div>
  
   
  </section>
  <br>
  <br>
  <br>
  <section class="image-container w-full h-auto">
    <img src="./Assets/images/RJ FARM.jpeg" alt="RJ Farm" class="w-full h-auto rounded-lg shadow-lg">
    
    <!-- Overlay for darkening effect -->
    <div class="overlay"></div>

    <!-- Buy Now Button -->
    <a href="#" class="buy-now">
      Buy Now
    </a>
  </section>
  <section class="carousel-container mt-10 relative mx-auto max-w-5xl">
    <h2 class="text-xl sm:text-2xl mb-4 text-black">Follow Us @mauigoldpineapple</h2>

    <br>
    <br>
    <br>
    <br>
    <br>
    <!-- Carousel Images -->
    <div class="carousel-images">
        <img src="./Assets/images/RJ FARM.jpeg" class="carousel-image object-cover rounded-lg shadow-lg" alt="Image 1">
        <img src="./Assets/images/RJ FARM.jpeg" class="carousel-image object-cover rounded-lg shadow-lg" alt="Image 2">
        <img src="./Assets/images/RJ FARM.jpeg" class="carousel-image object-cover rounded-lg shadow-lg" alt="Image 3">
        <img src="./Assets/images/RJ FARM.jpeg" class="carousel-image object-cover rounded-lg shadow-lg" alt="Image 4">
        <img src="./Assets/images/RJ FARM.jpeg" class="carousel-image object-cover rounded-lg shadow-lg" alt="Image 5">
        <img src="./Assets/images/RJ FARM.jpeg" class="carousel-image object-cover rounded-lg shadow-lg" alt="Image 6">
    </div>

    <!-- Navigation Dots -->
    <div class="flex justify-center mt-4 z-10">
        <span class="dot active" onclick="currentSlide(0)"></span>
        <span class="dot" onclick="currentSlide(1)"></span>
        <span class="dot" onclick="currentSlide(2)"></span>
        <span class="dot" onclick="currentSlide(3)"></span>
        <span class="dot" onclick="currentSlide(4)"></span>
        <span class="dot" onclick="currentSlide(5)"></span>
    </div>

    <!-- Navigation buttons -->
    <button class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-700 px-4 py-2 text-white rounded-l hover:bg-gray-600" onclick="plusSlides(-1)">❮</button>
    <button class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-700 px-4 py-2 text-white rounded-r hover:bg-gray-600" onclick="plusSlides(1)">❯</button>
    <br>
    <br>
    <br>
    <br>
    <br>
</section>


  <script>
    // Toggle mobile menu
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    menuToggle.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });

    // Reference to the header
    const header = document.querySelector('header');

    // Add scroll event listener
    window.addEventListener('scroll', () => {
      if (window.scrollY > 50) { 
        header.classList.add('bg-green-900', 'bg-opacity-100'); 
      } else {
        header.classList.remove('bg-green-900', 'bg-opacity-100'); 
      }
    });


   
    let slideIndex = 0;

    function showSlides() {
        const slides = document.querySelectorAll(".carousel-image");
        const dots = document.querySelectorAll(".dot");

        if (slideIndex >= slides.length) slideIndex = 0; // Reset to first slide
        if (slideIndex < 0) slideIndex = slides.length - 1; // Go to last slide if below 0

        const offset = -((slideIndex) * 25); // Calculate offset based on 25% width of each image
        document.querySelector('.carousel-images').style.transform = `translateX(${offset}%)`;

        dots.forEach((dot, index) => {
            dot.classList.remove("active"); 
        });

        dots[slideIndex].classList.add("active"); // Add active class to the current dot
    }

    function plusSlides(n) {
        slideIndex += n; // Change slide index
        showSlides(); // Update slides
    }

    function currentSlide(n) {
        slideIndex = n; // Set slide index to the clicked dot
        showSlides(); // Update slides
    }

    // Initial call to show the first slide
    showSlides();

</script>
</body>
</html>
