<?php
session_start();
include "./Controller/Database/Database.php";

// Initialize database connection
$database = new Database();
$conn = $database->connect();

class User {
  private $userId;
  private $role;

  public function __construct() {
      if (isset($_SESSION['user_id'])) {
          $this->userId = $_SESSION['user_id'];
          // Check if 'role' is set in the session before assigning it
          $this->role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
      }
  }

  public function isAuthenticated() {
      return isset($this->userId) && $this->role === 'user';
  }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    
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
      height: 80%;
      width: 100%;
  
    }
    .overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0, 0, 0, 0.5); 
      opacity: 0;
      transition: opacity 0.5s ease; 
    }
    .image-container:hover .overlay {
      opacity: 1; 
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
        }

        @media (min-width: 640px) {
            .carousel-image {
                min-width: 25%; 
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
</head>
<body class="bg-slate-50 text-white">
  <!-- Header -->
 
  <header class="fixed w-full top-0 bg-black bg-opacity-0 z-10">
  <div class="max-w-7xl mx-auto flex justify-between items-center p-4">
    <!-- Logo (aligned to the left) -->
    <div class="flex items-center space-x-2">
      <img src="./Assets/images/RJ FARM.jpeg" alt="RJ Farm" class="w-12 h-12 rounded-full">
      <span class="text-2xl font-bold text-white">RJ Farm</span>
    </div>

    <!-- Toggle button (for mobile) -->
    <button id="menu-toggle" class="lg:hidden text-white focus:outline-none">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
      </svg>
    </button>

    <!-- Nav links (Hidden by default on mobile) -->
    <nav class="hidden lg:flex space-x-6 font-bold" id="nav-menu">
      <a href=".index.php" class="hover:text-yellow-400">Home</a>
      <a href="./Views/about.php" class="hover:text-yellow-400">About</a>
      <a href="./Views/contact.php" class="hover:text-yellow-400">Contact</a>
      <a href="./public/signup.php" class="hover:text-yellow-400">Account</a>
    </nav>
  </div>

  <!-- Mobile menu (Hidden by default) -->
  <nav id="mobile-menu" class="lg:hidden hidden bg-green-900 bg-opacity-80 font-bold">
    <ul class="space-y-4 p-4">
      <li><a href=".index.php" class="hover:text-yellow-400">Home</a></li>
      <li><a href="./Views/about.php" class="hover:text-yellow-400">About</a></li>
      <li><a href="./Views/contact.php" class="hover:text-yellow-400">Contact</a></li>
      <li><a href="./public/signup.php" class="hover:text-yellow-400">Account</a></li>
    </ul>
  </nav>
</header>

<script>
  // Toggle visibility of mobile menu when hamburger icon is clicked
  const menuToggle = document.getElementById('menu-toggle');
  const mobileMenu = document.getElementById('mobile-menu');
  const navMenu = document.getElementById('nav-menu');

  menuToggle.addEventListener('click', function() {
    // Toggle the visibility of the mobile menu
    mobileMenu.classList.toggle('hidden');
    // Hide the desktop nav menu in case it is showing
    navMenu.classList.add('hidden');
  });
</script>





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
<button id="chatbot-button" class="absolute bottom-8 right-8  text-white rounded-full p-3 shadow-lg hover:bg-green-600 transition duration-300">
    <i class="fas fa-robot text-3xl"></i> Chatbot
</button>

<!-- Chatbot Modal -->
<div id="chatbot-modal" class="modal hidden">
    <div class="modal-content">
        <span id="close-modal" class="text-green-500 cursor-pointer float-right">&times;</span>
        <div id="chatbot-content">
            <div class="p-4 border-b border-gray-200 flex items-center justify-center">
            <i class="fas fa-robot text-3xl text-green-600 mr-2"></i>
            <h2 class="text-2xl font-bold text-center text-green-600">Product Chatbot</h2>
            </div>
            <div class="chat-box p-4 mb-4 border border-gray-300 text-black rounded-lg bg-gray-50">
            <div id="chat-output" class="space-y-2">Hi! How can I assist you today?</div>

            </div>
            <div class="p-4">
                <div class="flex">
                    <input type="text" id="user-input" class="border border-gray-300 rounded-l-lg p-2 w-full text-black" placeholder="Ask about our products...">
                    <button id="send-btn" class="bg-green-700 text-white rounded-r-lg p-2 hover:bg-green-800 transition duration-300">Send</button>
                </div>
            </div>
        </div>
    </div>
</div>
    
  </main>



  <section class="max-w-7xl mx-auto p-8 flex flex-col md:flex-row items-center">
    <!-- Left Text Content -->
    <div class="w-full md:w-1/2 pr-8 mb-4 md:mb-0">
      <h2 class="text-3xl font-bold text-green-800 mb-4">PINEAPPLE PLEDGE</h2>
      <p class="text-gray-700 mb-4">
        The Maui community needs our help. The recent wildfires that devastated Maui have left thousands of people displaced, without food, water, and shelter. Join us in support of our beloved Maui community as we band together to uplift those who have been impacted. With every purchase of a Maui Gold Pineapple, we will donate to relief organizations providing aid. Your support will also help protect farm jobs and pineapple production operations. Mahalo for your support.
      </p>
    
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
    
    </div>
  
   
  </section>
  <br>
  <br>
  <br>
  <section class="image-container w-full h-auto">
  <img src="./Assets/images/RJ FARM.jpeg" alt="RJ Farm" class="w-3/4 h-auto rounded-lg shadow-lg">

    
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

<div class="carousel-images flex space-x-4 overflow-hidden">
    <img src="./Assets/images/RJ FARM.jpeg" class="carousel-image object-cover rounded-lg shadow-lg transition-transform duration-300 hover:scale-110 " alt="Image 1">
    <img src="./Assets/images/RJ FARM.jpeg" class="carousel-image object-cover rounded-lg shadow-lg transition-transform duration-300 hover:scale-110 " alt="Image 2">
    <img src="./Assets/images/RJ FARM.jpeg" class="carousel-image object-cover rounded-lg shadow-lg transition-transform duration-300 hover:scale-1150 " alt="Image 3">
    <img src="./Assets/images/RJ FARM.jpeg" class="carousel-image object-cover rounded-lg shadow-lg transition-transform duration-300 hover:scale-105 " alt="Image 4">
    <img src="./Assets/images/RJ FARM.jpeg" class="carousel-image object-cover rounded-lg shadow-lg transition-transform duration-300 hover:scale-110 " alt="Image 5">
    <img src="./Assets/images/RJ FARM.jpeg" class="carousel-image object-cover rounded-lg shadow-lg transition-transform duration-300 hover:scale-110 " alt="Image 6">
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
        slideIndex = n;
        showSlides();
    }

    // Initial call to show the first slide
    showSlides();

    const menuToggle = document.getElementById('menu-toggle');
const navMenu = document.getElementById('nav-menu');

// Toggle the visibility of the mobile menu
menuToggle.addEventListener('click', () => {
    navMenu.classList.toggle('hidden'); 
});



const chatbotButton = document.getElementById('chatbot-button');
    const chatbotModal = document.getElementById('chatbot-modal');
    const closeModal = document.getElementById('close-modal');

    // Function to open the modal
    chatbotButton.addEventListener('click', () => {
        chatbotModal.classList.remove('hidden');
    });

    // Function to close the modal
    closeModal.addEventListener('click', () => {
        chatbotModal.classList.add('hidden');
    });

    // Optional: Close the modal when clicking outside of it
    window.addEventListener('click', (event) => {
        if (event.target === chatbotModal) {
            chatbotModal.classList.add('hidden');
        }
    });

    
        // Sample product data
const products = 
[
    { name: "Pineapple", price: "60 pesos", description: "High-performance laptop for work and play." },
    { name: "Smartphone", price: "60 pesos", description: "Latest model with advanced features." },
    { name: "Premepra", price: "60 pesos", description: "A unique product for various uses." },
    { name: "Secnda", price: "60 pesos", description: "Innovative design for everyday tasks." },
    { name: "Tresa", price: "60 pesos", description: "Top-quality product for professionals." },
    { name: "Punla", price: "60 pesos", description: "Premium item for high-end users." },
    { name: "NewType1", price: "60 pesos", description: "Latest model with cutting-edge features." },
    { name: "NewType2", price: "60 pesos", description: "State-of-the-art technology for advanced users." }
]


// Add event listener for the send button
document.getElementById("send-btn").addEventListener("click", function() {
    const userInput = document.getElementById("user-input").value.toLowerCase();
    addChatMessage("You: " + userInput);

    const response = getResponse(userInput);
    addChatMessage("Bot: " + response);

    document.getElementById("user-input").value = "";
});

// Function to add chat messages
function addChatMessage(message) {
    const chatOutput = document.getElementById("chat-output");
    const messageElement = document.createElement("div");
    messageElement.textContent = message;
    chatOutput.appendChild(messageElement);
    chatOutput.scrollTop = chatOutput.scrollHeight; 
}

// Function to get responses based on user input
function getResponse(userInput) {
    // Define keywords for specific product inquiries
    const productKeywords = {
    pineapple: `${products[0].name}: ${products[0].price} - ${products[0].description}`,
    premepra: `${products[2].name}: ${products[1].price} - ${products[2].description}`,
    secnda: `${products[3].name}: ${products[2].price} - ${products[3].description}`,
    tresa: `${products[4].name}: ${products[3].price} - ${products[4].description}`,
    punla: `${products[5].name}: ${products[4].price} - ${products[5].description}`,
    newtype1: `${products[6].name}: ${products[5].price} - ${products[6].description}`,
    newtype2: `${products[7].name}: ${products[6].price} - ${products[7].description}`
};

    // Check for specific product inquiries first
    for (const keyword in productKeywords) {
        if (userInput.includes(keyword)) {
            return productKeywords[keyword];
        }
    }

    // If no specific product matched, check for a general inquiry
    if (userInput.includes("products") || userInput.includes("available")) {
    return "We have the following products available: Pineapple, Secunda, Tresa, Punla, ";
}


    // Fallback response for unrecognized queries
    return "I'm sorry, I don't have information about that product. Please ask about our available products.";
}
  </script>
</body>
</html>
<?php include "./Views/includes/footer.php"; ?>