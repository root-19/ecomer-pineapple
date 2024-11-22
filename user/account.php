<?php
session_start();
include '../../Controller/Database/Database.php';
include '../../Model/Account.php';

$database = new Database();
$conn = $database->connect();
$user = new Account($conn);

// Check user authentication
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'user') {
    $_SESSION['error'] = "Access denied.";

}

$userData = [];
$response = [
    'status' => 'error',
    'message' => 'Something went wrong.'
];

// Handle GET request to fetch user data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = $_SESSION['id'];
    $userData = $user->getUserData($userId);

    // Check if user data is fetched
    if (!$userData) {
        echo json_encode(['error' => 'User not found']);
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update user settings in the database
    $userId = $_SESSION['id'];
    $newData = json_decode(file_get_contents('php://input'), true);
    $currentPassword = $newData['current_password'];

    // Verify the current password before updating
    if ($user->verifyPassword($userId, $currentPassword)) {
        unset($newData['current_password']);
        if ($user->updateUserData($userId, $newData)) {
            $response['status'] = 'success';
            // $response['message'] = 'Account settings updated successfully!';
        } else {
            $response['message'] = 'Failed to update account settings.';
        }
    } else {
        $response['message'] = 'Current password is incorrect.';
    }
    echo json_encode($response);
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <style> 
        .aww {
            margin-bottom: -200px;
            position: absolute;
        }
    </style>
</head>
<br><br>
<body class="bg-gray-100">
    <?php include "../includes/header.php"; ?>
    <br><br>

    <!-- <span>Account details | Payment method</span>-->
    <!-- <h2 class="aww text-3xl font-bold text-left">Account Settings</h2> -->
    <div class="flex items-center justify-center h-screen">
        <form id="settingsForm" class="bg-white p-6 rounded shadow-md w-1/2">
        
            <img src="../uploads/<?php echo htmlspecialchars($userData['user_image'] ?? 'default-image.jpg'); ?>" alt="User Image" class="w-32 h-32 rounded-full mx-auto">

        
           
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- User Details Input Fields -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($userData['first_name'] ?? ''); ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-600 focus:border-yellow-600 sm:text-sm">
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($userData['last_name'] ?? ''); ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-600 focus:border-yellow-600 sm:text-sm">
                </div>
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" value="<?php echo htmlspecialchars($userData['phone_number'] ?? ''); ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-600 focus:border-yellow-600 sm:text-sm">
                </div>
                <div>
                    <label for="province" class="block text-sm font-medium text-gray-700">Province</label>
                    <input type="text" name="province" id="province" value="<?php echo htmlspecialchars($userData['province'] ?? ''); ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-600 focus:border-yellow-600 sm:text-sm">
                </div>
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                    <input type="text" name="city" id="city" value="<?php echo htmlspecialchars($userData['city'] ?? ''); ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-600 focus:border-yellow-600 sm:text-sm">
                </div>
                <div>
                    <label for="baranggay" class="block text-sm font-medium text-gray-700">Barangay</label>
                    <input type="text" name="baranggay" id="baranggay" value="<?php echo htmlspecialchars($userData['baranggay'] ?? ''); ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-600 focus:border-yellow-600 sm:text-sm">
                </div>
                <div>
                    <label for="municipality" class="block text-sm font-medium text-gray-700">Municipality</label>
                    <input type="text" name="municipality" id="municipality" value="<?php echo htmlspecialchars($userData['municipality'] ?? ''); ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-600 focus:border-yellow-600 sm:text-sm">
                </div>
                <div>
                    <label for="street_address" class="block text-sm font-medium text-gray-700">Street, House No, Floor/Unit</label>
                    <input type="text" name="street_address" id="street_address" value="<?php echo htmlspecialchars($userData['street_address'] ?? ''); ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-600 focus:border-yellow-600 sm:text-sm">
                </div>
                <div>
                    <label for="zip_code" class="block text-sm font-medium text-gray-700">Zip Code</label>
                    <input type="text" name="zip_code" id="zip_code" value="<?php echo htmlspecialchars($userData['zip_code'] ?? ''); ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-600 focus:border-yellow-600 sm:text-sm">
                </div>
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                    <input type="password" name="current_password" id="current_password" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-600 focus:border-yellow-600 sm:text-sm">
                </div>
            </div>
            <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 rounded">Update Details</button>
            <br>
        </form>
        <div id="responseMessage" class="mt-4 text-center"></div>
    </div>

    <script>
         document.getElementById('settingsForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            
            const formData = new FormData(this);
            const jsonData = Object.fromEntries(formData.entries());

            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(jsonData),
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    swal("Success!", "Settings updated successfully!", "success");
                } else {
                    swal("Error!", data.message, "error");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                swal("Error!", "An unexpected error occurred.", "error");
            });
        });
    </script>
</body>
</html>

<?php include "../includes/footer.php"; ?>
