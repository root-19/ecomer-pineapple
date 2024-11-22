<?php
session_start();
include_once "../../Controller/Database/Database.php"; 
$database = new Database(); 
$conn = $database->connect();

// Check if admin is logged in
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Access denied.";
}

// Fetch all users
$users = $conn->query("SELECT id, first_name FROM users")->fetchAll(PDO::FETCH_ASSOC);

// Fetch messages if a user is selected
$messages = [];

try {
    $stmt = $conn->prepare("SELECT id, first_name FROM users WHERE id = 2");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = "Error fetching users: " . $e->getMessage();
}

// Handle reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply'])) {
    $userId = $_POST['user_id']; // Get the user ID from the hidden field
    $replyMessage = $_POST['reply_message'];

    // Prepare SQL statement to insert the admin reply
    $stmt = $conn->prepare("INSERT INTO messages (user_id, message, is_admin) VALUES (?, ?, 1)"); // is_admin = 1 for admin replies
    $stmt->bindValue(1, $userId, PDO::PARAM_INT); // user ID
    $stmt->bindValue(2, $replyMessage, PDO::PARAM_STR); // reply message

    if ($stmt->execute()) {
        $_SESSION['success'] = "Reply sent successfully!";
        header("Location: message-admin.php?user_id=" . $userId); // Redirect to view messages after reply
        exit();
    } else {
        $_SESSION['error'] = "Failed to send reply.";
    }
}
?>

<body class="bg-gray-100">
<?php include "../includes/header_admin.php"; ?> 

<div class="flex mt-10">
    <!-- Users List -->
    <div class="w-1/4 bg-white shadow-lg rounded-lg p-4">
    <h2 class="text-lg font-bold mb-4">Users</h2>
        <ul>
            <?php foreach ($users as $user): ?>
                <li class="mb-2 border-b border-gray-300 pb-2">
                    <a href="admin_messages.php?user_id=<?php echo $user['id']; ?>" class="text-green-600 font-bold">
                        <?php echo htmlspecialchars($user['first_name']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Chat Box -->
    <div class="w-3/4 bg-white shadow-lg rounded-lg p-4 ml-4">
        <div class="chat-header bg-green-700 text-white rounded-lg p-4">
            <h1 class="text-xl font-bold">Chat Messages</h1>
        </div>
        <div class="chat-box h-64 p-4 overflow-y-auto bg-gray-100 rounded-lg" id="chat-box">
    <!-- Display Messages -->
    <?php if (empty($messages)): ?>
        <p class="text-gray-500 text-center">No messages to display.</p>
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

        <form method="POST" class="chat-input p-4 flex">
            <input type="hidden" name="user_id" value="<?php echo isset($userId) ? $userId : ''; ?>">
            <input type="text" name="reply_message" placeholder="Type your reply..." class="flex-grow p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            <button type="submit" name="reply" class="ml-2 bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-600">Send</button>
        </form>
    </div>
</div>
</body>
