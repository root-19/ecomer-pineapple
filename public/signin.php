<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
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

    <br>
    <br>
    <div class="flex items-center mb-30 justify-center min-h-screen">
        <div class="flex flex-col lg:flex-row bg-white p-8 rounded-lg shadow-lg max-w-4xl w-full">
            
            <!-- Sign In Form -->
            <div class="w-full lg:w-1/2 p-6">
                <h2 class="text-2xl font-bold text-center mb-6">Sign In</h2>

                <form id="signinForm" class="form-container show" action="../controller/Controller.php" method="POST">
                    <input type="hidden" name="action" value="login">

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    
                    <button type="submit" class="w-full bg-yellow-600 text-white p-2 rounded-md mt-4 hover:bg-yellow-700">Sign In</button>
                </form>
            </div>

            <!-- New Customer Section -->
            <div class="w-full lg:w-1/2 p-6 border-t lg:border-t-0 lg:border-l border-gray-300 mt-6 lg:mt-0">
                <h3 class="text-xl font-bold mb-4">NEW CUSTOMER?</h3>
                <p class="mb-4">Create an account with us and you'll be able to:</p>
                <ul class="list-disc list-inside text-sm text-gray-600 mb-6">
                    <li>Check out faster</li>
                    <li>Save multiple shipping addresses</li>
                    <li>Access your order history</li>
                    <li>Track new orders</li>
                    <li>Save items to your Wish List</li>
                </ul>
                <br>
                <button  onclick="sign()"
                class="w-full bg-yellow-600 text-white p-2 rounded-md hover:bg-yellow-700">
                   Create
</main>
       
                
            </div>
        </div>
    </div>

    <script>
    function sign() {
        window.location.href="./signup.php"
    }
     </script>
</body>
</html>
