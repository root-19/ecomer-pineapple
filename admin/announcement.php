<?php
session_start();
include_once "../../Controller/Database/Database.php"; 
$database = new Database(); 
$conn = $database->connect();

// Check if admin is logged in
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Access denied.";

}
// Initialize variables
$errors = [];
$successMessage = "";

// Handle form submission for posting an announcement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    if (empty($title) || empty($content)) {
        $errors[] = "Title and content cannot be empty.";
    } else {
        $stmt = $conn->prepare("INSERT INTO announcements (title, content) VALUES (?, ?)");
        if ($stmt->execute([$title, $content])) {
            $successMessage = "Announcement posted successfully!";
        } else {
            $errors[] = "Failed to post the announcement.";
        }
    }
}

// Handle update request
if (isset($_POST['update'])) {
    $id = $_POST['announcement_id'];
    $updatedTitle = $_POST['updated_title'];
    $updatedContent = $_POST['updated_content'];

    $stmt = $conn->prepare("UPDATE announcements SET title = ?, content = ? WHERE id = ?");
    if ($stmt->execute([$updatedTitle, $updatedContent, $id])) {
        $successMessage = "Announcement updated successfully!";
    } else {
        $errors[] = "Failed to update the announcement.";
    }
}

// Fetch all announcements
$announcements = $conn->query("SELECT * FROM announcements ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);


if(isset($_POST['delete'])) {
    $id = $_POST['announcement_id'];

    $stmt = $conn->prepare("DELETE FROM  announcements WHERE id = ?");
    if($stmt->execute([$id])) {
        $sucessMessage = "ANNOUNCEMENT Deleted Succesfully";
    } else {
        $errors[] = "Failed to delete the announcement.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">
<?php include "../includes/header_admin.php"; ?>

<div class="max-w-2xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Announcements</h1>

    <!-- Display Errors -->
    <?php if ($errors): ?>
        <div class="bg-red-200 text-red-700 p-4 mb-4 rounded">
            <?php foreach ($errors as $error): ?>
                <p><?= $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Display Success Message -->
    <?php if ($successMessage): ?>
        <div class="bg-green-200 text-green-700 p-4 mb-4 rounded"><?= $successMessage; ?></div>
    <?php endif; ?>

    <!-- Announcement Form -->
    <form method="POST" class="bg-white p-6 rounded shadow-md mb-6">
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Title</label>
            <input type="text" name="title" class="w-full p-2 border rounded" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Content</label>
            <textarea name="content" rows="4" class="w-full p-2 border rounded" required></textarea>
        </div>
        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">Post Announcement</button>
    </form>

    <!-- Announcements List -->
    <div>
        <?php foreach ($announcements as $announcement): ?>
            <div class="bg-white p-4 rounded shadow-md mb-4">
                <h2 class="text-xl font-bold"><?= htmlspecialchars($announcement['title']); ?></h2>
                <p class="text-gray-700"><?= htmlspecialchars($announcement['content']); ?></p>
                <p class="text-gray-500 text-sm">Posted on <?= date("F j, Y, g:i a", strtotime($announcement['created_at'])); ?></p>
                
                <!-- Update Button -->
                <button onclick="openUpdateForm(<?= $announcement['id']; ?>, '<?= htmlspecialchars($announcement['title']); ?>', '<?= htmlspecialchars($announcement['content']); ?>')" 
                        class="text-green-500 hover:underline mt-2">Update</button>
           
            <form method="POST" class="inline-block">
                    <input type="hidden" name="announcement_id" value="<?= $announcement['id']; ?>">
                    <button type="submit" name="delete" class="text-red-500 hover:underline mt-2 ml-4">Delete</button>
                </form>
        <?php endforeach; ?>
    </div>
</div></div>

<!-- Update Modal -->
<div id="update-modal" class="fixed inset-0 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow-md max-w-md w-full">
        <h2 class="text-xl font-bold mb-4">Update Announcement</h2>
        <form method="POST">
            <input type="hidden" name="announcement_id" id="announcement_id">
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Title</label>
                <input type="text" name="updated_title" id="updated_title" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Content</label>
                <textarea name="updated_content" id="updated_content" rows="4" class="w-full p-2 border rounded" required></textarea>
            </div>
            <button type="submit" name="update" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">Update Announcement</button>
            <button type="button" onclick="closeUpdateForm()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded ml-2">Cancel</button>
        </form>
    </div>
</div>

<script>
    function openUpdateForm(id, title, content) {
        document.getElementById('announcement_id').value = id;
        document.getElementById('updated_title').value = title;
        document.getElementById('updated_content').value = content;
        document.getElementById('update-modal').classList.remove('hidden');
    }

    function closeUpdateForm() {
        document.getElementById('update-modal').classList.add('hidden');
    }
</script>

</body>
</html>
