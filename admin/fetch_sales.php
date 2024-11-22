<?php
// Start the session
session_start();

// Include your database connection file
include_once "../../Controller/Database/Database.php"; 
$database = new Database(); 
$conn = $database->connect();

$selectedMonth = $_GET['month'] ?? null;

if ($selectedMonth) {
    $query = $conn->prepare("
        SELECT SUM(price * quantity) AS total_sales 
        FROM sold 
        WHERE DATE_FORMAT(order_date, '%Y-%m') = :month
    ");
    $query->bindParam(':month', $selectedMonth);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    // Return the total sales data in JSON format
    echo json_encode(['total_sales' => (float)$result['total_sales']]);
}