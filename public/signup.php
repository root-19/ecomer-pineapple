<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .form-container {
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        .hidden {
            opacity: 0;
            transform: translateY(-20px);
        }
        .show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-gray-100">

    <br><br>
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-2xl"> <!-- Adjusted width here -->
            <h2 class="text-2xl font-bold text-center mb-6">Sign Up</h2>
            <?php
            if (isset($_SESSION['error'])) {
                echo '<div class="bg-red-500 text-white p-2 rounded mb-4">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
            ?>
            <!-- Sign Up Form -->
            <form id="signupForm" class="form-container show" action="../controller/Controller.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="register">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="first_name" id="first_name" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-600 focus:border-yellow-600 sm:text-sm">
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" name="last_name" id="last_name" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-600 focus:border-yellow-600 sm:text-sm">
                    </div>
                    <div>
    <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
    <input type="number" name="age" id="age" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-600 focus:border-yellow-600 sm:text-sm">
</div>

<div>
    <label for="user_image" class="block text-sm font-medium text-gray-700">Profile Image</label>
    <input type="file" name="user_image" id="user_image" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-600 focus:border-yellow-600 sm:text-sm">
</div>

                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="number" value="09" maxlenght="11" plaveholder="type your number" oninput="validateNumberInput(this) "  name="phone_number" id="phone_number" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-600 focus:border-yellow-600 sm:text-sm">
                    </div>

                    <div>
                        <label for="province" class="block text-sm font-medium text-gray-700">Province</label>
                        <input type="text" name="province" id="province" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-600 focus:border-yellow-600 sm:text-sm">
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                        <input type="text" name="city" id="city" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-600 focus:border-yellow-600 sm:text-sm">
                    </div>

                    <div>
                        <label for="baranggay" class="block text-sm font-medium text-gray-700">Barangay</label>
                        <input type="text" name="baranggay" id="baranggay" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-600 focus:border-yellow-600 sm:text-sm">
                    </div>

                    <div>
                        <label for="municipality" class="block text-sm font-medium text-gray-700">Municipality</label>
                        <input type="text" name="municipality" id="municipality" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-600 focus:border-yellow-600 sm:text-sm">
                    </div>

                    <div>
                        <label for="street_address" class="block text-sm font-medium text-gray-700">Street, House No, Floor/Unit</label>
                        <input type="text" name="str_hno_floor_unit" id="street_address" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-600 focus:border-yellow-600 sm:text-sm">
                    </div>

                    <div>
                        <label for="zip_code" class="block text-sm font-medium text-gray-700">Zip Code</label>
                        <input type="text" name="zip_code" id="zip_code" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-600 focus:border-yellow-600 sm:text-sm">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-600 focus:border-yellow-600 sm:text-sm">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-600 focus:border-yellow-600 sm:text-sm">
                    </div>

                    <div>
                        <label for="con_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" name="con_password" id="con_password" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-600 focus:border-yellow-600 sm:text-sm">
                    </div>
                </div>

                <button type="submit" class="w-full bg-yellow-600 text-white py-2 rounded-md mt-4 hover:bg-yellow-700">Sign Up</button>
            </form>

            <p class="text-center text-sm text-gray-600 mt-4">
                Already have an account? <a href="signin.php" class="text-indigo-600 hover:underline">Sign In</a>
            </p>
        </div>
    </div>
    <script> 
    function validateNumberInput(input) {


        input.value = input.value.replace(/[^0-9]/g, '');
      //ensure to 11 digit only
      if(input.value > 11) {
        input.value = input.value.slice(0,11);
      }    
    }
    </script>
</body>
</html>
