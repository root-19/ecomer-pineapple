<?php
session_start();
include_once "../../Controller/Database/Database.php"; 
$database = new Database(); 
$conn = $database->connect();

// Check if admin is logged in
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'user') {
    $_SESSION['error'] = "Access denied.";
  
}// Handle message submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    // Set the user_id to 2 for the admin (user_id for admin should always be 2)
    $userId = 2;  // Admin's user_id is 2
    $message = $_POST['message'];

    // Prepare SQL statement to insert the user's message (is_admin = 0 for user messages)
    $stmt = $conn->prepare("INSERT INTO messages (user_id, message, is_admin) VALUES (?, ?, 0)"); // is_admin = 0 for user messages
    $stmt->bindValue(1, $userId, PDO::PARAM_INT); // user_id is set to 2 for admin
    $stmt->bindValue(2, $message, PDO::PARAM_STR); // user message

    if ($stmt->execute()) {
        $_SESSION['success'] = "Message sent successfully!";
        header("Location: message-admin.php"); 
        exit();
    } else {
        $_SESSION['error'] = "Failed to send message.";
    }
}

?>

<body class="bg-gray-100">
<?php include "../includes/header.php"; ?> 

<div class="flex mt-10">
    <!-- Chat Box -->
    <div class="w-full bg-white shadow-lg rounded-lg p-4">
        <div class="chat-header bg-green-700 text-white rounded-lg p-4">
            <h1 class="text-xl font-bold">Message Admin</h1>
        </div>
        
        <div class="chat-box h-64 p-4 overflow-y-auto bg-gray-100 rounded-lg" id="chat-box">
            <!-- Display User's Messages -->
            <?php 
                // Fetch the user's messages to the admin (admin has user_id = 2)
                $stmt = $conn->prepare("SELECT m.*, u.first_name FROM messages m LEFT JOIN users u ON m.user_id = u.id WHERE (m.user_id = ? AND m.is_admin = 0) OR (m.user_id = 2 AND m.is_admin = 1) ORDER BY m.created_at DESC");
                $stmt->execute([$_SESSION['id']]);
                $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (empty($messages)): 
            ?>
                <p class="text-gray-500 text-center">No messages yet.</p>
            <?php else: ?>
                <?php foreach (array_reverse($messages) as $msg): ?>
                    <div class="message mb-2 flex <?php echo $msg['is_admin'] ? 'justify-end' : 'justify-start'; ?>">
                        <div class="message-bubble <?php echo $msg['is_admin'] ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-800'; ?> rounded-lg p-2 max-w-xs">
                            <?php if ($msg['is_admin']): ?>
                                <strong class="block text-green-600">Admin:</strong>
                            <?php else: ?>
                                <strong class="block"><?php echo htmlspecialchars($msg['first_name']); ?>:</strong>
                            <?php endif; ?>
                            <p><?php echo htmlspecialchars($msg['message']); ?></p>
                            <small class="text-gray-500"><?php echo htmlspecialchars($msg['created_at']); ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Send Message Form -->
        <form method="POST" class="chat-input p-4 flex">
            <input type="text" name="message" placeholder="Type your message..." class="flex-grow p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <button type="submit" class="ml-2 bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-600">Send</button>
        </form>
    </div>
</div>
</body>

<?php include "../includes/footer.php"; ?> 