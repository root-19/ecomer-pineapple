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

    .centered-text {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
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
      <h1 class="text-6xl font-bold text-white">Nutrient Benefits</h1>
    </div>
    
  </main>
  <section class="max-w-4xl mx-auto p-6 mt-10">
  <!-- Title -->
  <p class="text-3xl text-center font-bold text-green-900 mb-6">Nutritious as Well as Delicious</p>

 <!-- First Paragraph -->
 <p class="text-lg text-gray-700 leading-relaxed mb-4">
    Fruits and vegetables are not only delicious but also essential for promoting good health and reducing the risk of chronic diseases such as stroke, type 2 diabetes, cardiovascular disease, and hypertension. At RJ Farms, we understand 
    the importance of making the right choices when it comes to the fruits you eat, and our pineapples are a top contender for their numerous health benefits.
  </p>

  <!-- Second Paragraph -->
  <p class="text-lg text-gray-700 leading-relaxed mb-4">
    RJ Farms’ pineapples are naturally low in elements that should be consumed in moderation, such as saturated fat, cholesterol, and sodium. On the other hand, they are rich in nutrients that are highly beneficial, including dietary fiber, thiamin, Vitamin B-6, and are an excellent source of antioxidant Vitamin C and manganese. These nutrients make our pineapples a wholesome addition to your diet, supporting both everyday health and the prevention of chronic conditions.
  </p>

  <!-- Super Source of Vitamin C -->
  <p class="text-lg text-gray-700 leading-relaxed mb-4">
    <span class="font-bold text-green-900">Super Source of Vitamin C</span><br>
    At RJ Farms, our pineapples are a super source of Vitamin C. Just two sweet, juicy slices provide 100% of your daily recommended value, offering an excellent dose of this vital antioxidant. Vitamin C is essential for forming collagen, the connective tissue that holds your muscles, bones, and other tissues together. It also supports healthy gums, aids in healing cuts, boosts the immune system to protect against infections, and strengthens blood vessels to prevent bruising.
  </p>

  <!-- Brimming with Bromelain -->
  <p class="text-lg text-gray-700 leading-relaxed mb-4">
    <span class="font-bold text-green-900">Brimming with Bromelain</span><br>
    At RJ Farms, our pineapples are rich in bromelain, a unique enzyme found in both the fruit and stem that aids in digestion and reduces inflammation, helping with muscle pain, arthritis, and sinus issues. Bromelain is valued for its medicinal properties, often used as a natural alternative to anti-inflammatory drugs. Additionally, our pineapples are packed with antioxidants, which protect your cells from damage, reducing the risk of aging, cancer, and various diseases, making them a healthy and nutritious choice.
  </p>

  <!-- Awesome Antioxidants -->
  <p class="text-lg text-gray-700 leading-relaxed mb-4">
    <span class="font-bold text-green-900">Awesome Antioxidants</span><br>
    Pineapple ranks as one of the top 50 foods with the highest antioxidant content per serving. Health-promoting antioxidants help protect cells from damage that may lead to cancer, aging, and a variety of diseases.
  </p>
</section>


  <!--another  Main section with full background image -->
  <main class="bg-cover-custom">
    <!-- Centered text -->
    <div class="centered-text">
      <h1 class="text-6xl font-bold text-white">GROWING A PINEAPPLE</h1>
    </div>
    
  </main>
  <section class="max-w-4xl mx-auto p-6 mt-10">
  <!-- Title -->
  <p class="text-3xlfont-bold text-green-900 mb-6">WHAT YOU’LL NEED:
  </p>

 <!-- First Paragraph -->
 <p class="text-lg text-gray-700 leading-relaxed mb-4">
•The crown (top) of the pineapple <br>
• A pint canning jar or other glass container <br>
• A 12-inch diameter pot <br>
• Potting soil that drains well

  </p>

  <!-- Second Paragraph -->
  <p class="text-lg text-gray-700 leading-relaxed mb-4">

  <span class="font-bold text-green-900">PLANTING DIRECTIONS:</span><br>
  • Cut the crown from the pineapple just above where the stem comes out of the top of the body <br>
• Remove any of the fruity part of the pineapple from the crown <br>
• Pull off the outer leaves of the crown, leaving 6 to 8 big leaves near the center <br>
• Set this shoot aside for 2-3 days to dry out <br>
• Put the shoot in water (about 1/2-inch deep; water shouldn’t touch the leaves) <br>
• Place it in a bright spot out of direct sunlight for a few weeks to encourage roots to develop <br>
• After you see the roots beginning to grow, transplant the crown to a medium-size pot containing soil that drains well <br>
• Place the pot in a bright location and water your plant daily <br>
• Once the young plant is established, pour a cupful of well-balanced, diluted, water-soluble liquid fertilizer into the top of the plant once a month.
Your plant should develop a red bud after about 12 months. From this stage, the pineapple fruit will begin to develop and in three to six months, it should be ready to enjoy!
  </p>

 
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
