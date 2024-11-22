<?php
// Start the session
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database connection file
include_once "../../Controller/Database/Database.php"; 
$database = new Database(); 
$conn = $database->connect();

// Check if item_id is provided
if (isset($_POST['item_id'])) {
    $item_id = $_POST['item_id'];

    try {
        // Prepare the delete query using PDO
        $query = "DELETE FROM cart WHERE id = :item_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // If deletion is successful, redirect back to the order summary page
            header("Location: checkout.php?"); // Replace with the actual filename
            exit();
        } else {
            echo "Error: Could not delete the item.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}