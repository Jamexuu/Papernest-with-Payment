<?php
require_once '../backend/Database.php';

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$book_id = isset($_POST['book_id']) ? $_POST['book_id'] : null;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

try {
    $db = new Database();
    $db->execute(
        "INSERT INTO cart (user_id, book_id, quantity, added_at) VALUES (?, ?, ?, datetime('now'))",
        [$user_id, $book_id, $quantity]
    );
    ob_end_clean();
    echo json_encode(['success' => true, 'message' => 'Item added to cart!']);
} catch (Exception $e) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}