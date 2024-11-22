<?php
// Start the session
session_start();

include_once "../../Controller/Database/Database.php"; 
$database = new Database(); 
$conn = $database->connect();

// Check user session
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'user') {
    $_SESSION['error'] = "Access denied.";
}

// Fetch user ID
$user_id = $_SESSION['id'];

// Fetch user details
$query = "SELECT first_name, last_name, email, phone_number, street_address, baranggay, city, province FROM users WHERE id = :id LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['user-input']) && !empty(trim($_POST['user-input']))) {
        $userId = $_SESSION['id']; 
        $firstName = $user['first_name'];
        $message = trim($_POST['user-input']); 
        
        $recipientId = 2; // Assuming recipient ID is Admin (2)
        
        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO messages (user_id, recipient_id, first_name, message, is_admin) VALUES (?, ?, ?, ?, ?)");
        
        // Bind the parameters
        $stmt->bindValue(1, $userId, PDO::PARAM_INT); 
        $stmt->bindValue(2, $recipientId, PDO::PARAM_INT);
        $stmt->bindValue(3, $firstName, PDO::PARAM_STR);
        $stmt->bindValue(4, $message, PDO::PARAM_STR); 
        $stmt->bindValue(5, 0, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Message sent successfully!";
            header("Location: message.php");
            exit();
        } else {
            $_SESSION['error'] = "Failed to send message.";
        }
    } else {
        $_SESSION['error'] = "Message cannot be empty.";
    }
}

$messages = [];

// Fetch messages for the current user
try {
    $stmt = $conn->prepare("
        SELECT id, first_name, message, created_at, user_id, is_admin, is_read 
        FROM messages 
        WHERE recipient_id = ? OR user_id = ? 
        ORDER BY created_at DESC");
    $stmt->bindValue(1, $_SESSION['id'], PDO::PARAM_INT);
    $stmt->bindValue(2, $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Mark messages as read when the user views them
    $stmt = $conn->prepare("UPDATE messages SET is_read = 1 WHERE recipient_id = ? AND is_read = 0");
    $stmt->bindValue(1, $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();

} catch (PDOException $e) {
    $_SESSION['error'] = "Error fetching messages: " . $e->getMessage();
}
?>

<body class="bg-gray-100">
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">

<?php include "../includes/header.php"; ?> 
<br><br>
<br><br>
<div class="main bg-white shadow-lg rounded-lg w-full max-w-md mx-auto mt-10">
    <div class="chat-header bg-green-800 text-white rounded-t-lg p-4">
        <h1 class="text-xl font-bold">Message</h1>
    </div>
    <div class="chat-box h-64 p-4 overflow-y-auto" id="chat-box">
        <!-- Display Messages -->
        <?php if (!empty($messages)): ?>
            <?php foreach (array_reverse($messages) as $msg): ?>

                <div class="message mb-2 flex <?php echo $msg['is_admin'] == 1 ? 'justify-start' : 'justify-end'; ?>">

                    <div class="p-2 rounded-lg <?php echo $msg['is_admin'] == 1 ? 'bg-green-100 text-left' : 'bg-white text-right'; ?> max-w-xs">
                        <strong class="font-bold text-green-500">
                            <?php echo htmlspecialchars($msg['is_admin'] == 1 ? 'Admin' : $msg['first_name']); ?>
                        </strong>
                        <p class="whitespace-pre-wrap"><?php echo htmlspecialchars($msg['message']); ?></p>
                        <small class="text-gray-500"><?php echo $msg['created_at']; ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Message Input -->
    <form method="POST" class="chat-input p-4 flex">
        <input type="text" name="user-input" id="user-input" placeholder="Type your message here..." class="flex-grow p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        <button type="submit" class="ml-2 bg-green-800 text-white px-4 py-2 rounded-lg hover:bg-green-600">Send</button>
    </form>
</div>

  
</body> 
