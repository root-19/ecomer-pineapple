<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar {
            transition: transform 0.3s ease;
        }
        .sidebar.hidden {
            transform: translateX(-100%);
        }
        .dropdown-content {
            display: none;
        }
        /* Display the dropdown on hover */
        .hover-parent:hover .dropdown-content {
            display: block;
        }
    </style>
</head>
<body class="bg-slate-100 font-sans leading-normal tracking-normal">
    <!-- Toggle Button -->
    <div class="flex justify-between items-center p-4 bg-green-800 text-white gap-10">
        <h2 class="text-lg font-semibold"></h2>
        <button id="toggle-button" class="focus:outline-none ">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Sidebar -->
    <div id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-green-800 text-white p-4 sidebar hidden">
    <div class="flex justify-center items-center">
    <img src="../../Assets/images/RJ FARM.jpeg" alt="Dashboard Icon" class="h-15 w-15 mr-2"> <!-- Adjust the path and size as needed -->
    <!-- <h2 class="text-lg font-semibold">User Dashboard</h2> -->
</div>

        <ul class="mt-6">
            <li>
                <a href="../admin/overview.php" class="block px-4 py-2 mt-2 hover:bg-green-500 flex items-center">
                    <i class="fas fa-tachometer-alt text-white mr-4"></i> Overview
                </a>
            </li>
            <li>
                <a href="../admin/index.php" class="block px-4 py-2 mt-2 hover:bg-green-500 flex items-center">
                    <i class="fas fa-shopping-cart text-white mr-4"></i> Add Products
                </a>
            </li>
            <li class="relative hover-parent">
                <a href="../admin/orders.php" class="block px-4 py-2 mt-2 hover:bg-green-500 flex items-center">
                    <i class="fas fa-box-open text-white mr-4"></i> Orders
                </a>
                <!-- Dropdown -->
                <ul class="dropdown-content absolute left-full top-0 mt-2 w-40 bg-white hover:bg-green-500 rounded-lg shadow-lg">
                    <li class="border-b hover:bg-green-100">
                        <a href="../admin/shipitem.php" class="block px-4 py-2 text-blck">Ship</a>
                    </li>
                    <li class="hover:bg-gray-100">
                        <a href="../admin/delivered.php" class="block px-4 py-2 text-black">Delivered</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="../admin/product.php" class="block px-4 py-2 mt-2 hover:bg-green-500 flex items-center">
                    <i class="fas fa-shopping-cart text-white mr-4"></i> Products
                </a>
            </li>
            <li>
    <a href="../admin/rider.php" class="block px-4 py-2 mt-2 hover:bg-green-500 flex items-center">
        <i class="fas fa-motorcycle text-white mr-4"></i> Rider
    </a>
</li>

            <li>
                <a href="../admin/message.php" class="block px-4 py-2 mt-2 hover:bg-green-500 flex items-center">
                    <i class="fas fa-envelope text-white mr-4"></i>Message
                </a>
            </li>

            <li>
    <a href="../admin/announcement.php" class="block px-4 py-2 mt-2 hover:bg-green-500 flex items-center">
        <i class="fas fa-bullhorn text-white mr-4"></i> Announcement
    </a>
</li>


            <li>
                <a href="../admin/feedback.php" class="block px-4 py-2 mt-2 hover:bg-green-500 flex items-center">
                    <i class="fas fa-comments text-white mr-4"></i> Feedbacks
                </a>
            </li>
            <li>
                <a href="../admin/logout.php" class="block px-4 py-2 hover:bg-green-500 flex items-center">
                    <i class="fas fa-sign-out-alt text-white mr-4"></i> Logout
                </a>
            </li>
        </ul>
    </div>

    <script>
  
        const toggleButton = document.getElementById('toggle-button');
        const sidebar = document.getElementById('sidebar');

        toggleButton.addEventListener('click', () => {
            sidebar.classList.toggle('hidden');
        });
    
    </script>
</body>
</html>
