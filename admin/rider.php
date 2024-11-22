<?php
session_start();
include_once "../../Controller/Database/Database.php"; 
$database = new Database(); 
$conn = $database->connect();


$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $con_password = isset($_POST['con_password']) ? $_POST['con_password'] : '';
    $role = 'rider';

    // File handling for images
    $uploadDir = '../uploads/';
    $licenseFile = $uploadDir . basename($_FILES['license']['name']);
    $orcrFile = $uploadDir . basename($_FILES['orcr']['name']);
    $validIdFile = $uploadDir . basename($_FILES['valid_id']['name']);
    $policeClearanceFile = $uploadDir . basename($_FILES['police_clearance']['name']);

    // Basic validation
    if (empty($name) || empty($email) || empty($password) || empty($_FILES['license']['name']) || empty($_FILES['orcr']['name']) || empty($_FILES['valid_id']['name']) || empty($_FILES['police_clearance']['name'])) {
        $message = "Please fill all fields and upload all required files.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } elseif ($password !== $con_password) {
        $message = "Passwords do not match.";
    } elseif (!move_uploaded_file($_FILES['license']['tmp_name'], $licenseFile) ||
              !move_uploaded_file($_FILES['orcr']['tmp_name'], $orcrFile) ||
              !move_uploaded_file($_FILES['valid_id']['tmp_name'], $validIdFile) ||
              !move_uploaded_file($_FILES['police_clearance']['tmp_name'], $policeClearanceFile)) {
        $message = "File upload failed. Please try again.";
    } else {
        // Hash the password
        $hash_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into database
        $stmt = $conn->prepare("
            INSERT INTO users (first_name, email, password, con_password, role, license, orcr, valid_id, police_clearance) 
            VALUES (:first_name, :email, :password, :con_password, :role, :license, :orcr, :valid_id, :police_clearance)
        ");
        $stmt->bindParam(':first_name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hash_password);
        $stmt->bindParam(':con_password', $con_password);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':license', $licenseFile);
        $stmt->bindParam(':orcr', $orcrFile);
        $stmt->bindParam(':valid_id', $validIdFile);
        $stmt->bindParam(':police_clearance', $policeClearanceFile);

        if ($stmt->execute()) {
            $message = "Rider added successfully!";
        } else {
            $message = "Failed to add rider. Please try again.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Rider</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.0.1/tailwind.min.css">
</head>
<body class="bg-gray-100">
<?php include "../includes/header_admin.php"; ?>
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">Add Rider</h2>
        <?php if ($message): ?>
            <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <form action="" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <div class="mb-4">
        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
        <input type="text" name="first_name" id="first_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
    </div>
    <div class="mb-4">
        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
        <input type="email" name="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
    </div>
    <div class="mb-4">
        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
        <input type="password" name="password" id="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
    </div>
    <div class="mb-4">
        <label for="con_password" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password:</label>
        <input type="password" name="con_password" id="con_password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
    </div>
    <div class="mb-4">
        <label for="license" class="block text-gray-700 text-sm font-bold mb-2">License:</label>
        <input type="file" name="license" id="license" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
    </div>
    <div class="mb-4">
        <label for="orcr" class="block text-gray-700 text-sm font-bold mb-2">OR/CR:</label>
        <input type="file" name="orcr" id="orcr" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
    </div>
    <div class="mb-4">
        <label for="valid_id" class="block text-gray-700 text-sm font-bold mb-2">Valid ID:</label>
        <input type="file" name="valid_id" id="valid_id" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
    </div>
    <div class="mb-4">
        <label for="police_clearance" class="block text-gray-700 text-sm font-bold mb-2">Police Clearance:</label>
        <input type="file" name="police_clearance" id="police_clearance" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
    </div>
    <input type="hidden" name="role" value="rider">

    <div class="flex items-center justify-between">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Rider</button>
    </div>
</form>

    </div>
</body>
</html>
