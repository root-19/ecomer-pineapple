<?php
session_start(); 

require_once '../../Controller/Database/Database.php'; 

// Check if user is logged in and is an admin

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied.']);
    exit;
}

try {
    $db = new Database(); 
    $pdo = $db->connect(); 

    // Count total users
    $stmt = $pdo->query("SELECT COUNT(*) AS user_count FROM users"); 
    $userCount = $stmt->fetchColumn();

    // Count total visitors
    $stmt = $pdo->query("SELECT COUNT(*) AS visitor_count FROM visitors");
    $visitorCount = $stmt->fetchColumn();

    // Return the data as JSON
    echo json_encode([
        'user_count' => (int)$userCount,
        'visitor_count' => (int)$visitorCount,
    ]);
} catch (PDOException $e) {
    // Handle connection errors
    echo json_encode(['error' => "Database error: " . $e->getMessage()]);
}