<?php
session_start();
require_once '../Controller/Database/Database.php';

$database = new Database();
$conn = $database->connect();

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];  // User ID from session
    $verification_code = $_POST['code1'] . $_POST['code2'] . $_POST['code3'] . $_POST['code4'] . $_POST['code5'] . $_POST['code6'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['verify_code'] === $verification_code) {
        // Verification successful, update user's status and clear the verification code
        $update_stmt = $conn->prepare("UPDATE users SET verify_code = NULL WHERE id = :id");
        $update_stmt->bindParam(':id', $user_id);
        $update_stmt->execute();

        // Redirect to the login page
        header("Location: ../Views/user/index.php");
        exit();
    } else {
        // Verification failed
        $message = "Invalid verification code. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function handleInput(event, nextInputId) {
            // Check if the input value is valid (0-9)
            if (event.target.value.match(/^[0-9]$/)) {
                // If valid, focus on the next input
                if (nextInputId) {
                    document.getElementById(nextInputId).focus();
                }
            } else {
                // If invalid, clear the input
                event.target.value = '';
            }
        }
    </script>
</head>
<body class="bg-gray-100">
<?php include "./header.php"; ?>
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold text-center mb-6">Verify Account</h2>
            <?php if ($message) { ?>
                <div class="bg-red-500 text-white p-2 rounded mb-4">
                    <?php echo $message; ?>
                </div>
            <?php } ?>
            <form action="verify.php" method="POST">
                <div class="flex justify-center space-x-2">
                    <input type="text" maxlength="1" name="code1" id="code1" class="w-10 h-10 text-center border border-gray-300 rounded-md shadow-sm" required oninput="handleInput(event, 'code2')">
                    <input type="text" maxlength="1" name="code2" id="code2" class="w-10 h-10 text-center border border-gray-300 rounded-md shadow-sm" required oninput="handleInput(event, 'code3')">
                    <input type="text" maxlength="1" name="code3" id="code3" class="w-10 h-10 text-center border border-gray-300 rounded-md shadow-sm" required oninput="handleInput(event, 'code4')">
                    <input type="text" maxlength="1" name="code4" id="code4" class="w-10 h-10 text-center border border-gray-300 rounded-md shadow-sm" required oninput="handleInput(event, 'code5')">
                    <input type="text" maxlength="1" name="code5" id="code5" class="w-10 h-10 text-center border border-gray-300 rounded-md shadow-sm" required oninput="handleInput(event, 'code6')">
                    <input type="text" maxlength="1" name="code6" id="code6" class="w-10 h-10 text-center border border-gray-300 rounded-md shadow-sm" required>
                </div>
                <button type="submit" class="w-full bg-yellow-600 text-white py-2 rounded-md mt-4 hover:bg-yellow-700">Verify</button>
            </form>
        </div>
    </div>
</body>
</html>
