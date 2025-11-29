<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/paypalApi.php';
require_once __DIR__ . '/loadEnv.php';
require_once __DIR__ . '/../backend/Database.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

$data = json_decode(file_get_contents('php://input'), true);
$currency = isset($data['currency']) ? $data['currency'] : $_ENV['CURRENCY'];

// Get cart items
$db = new Database();
$cartItems = $db->fetchAll(
    "SELECT c.*, b.title, b.price FROM cart c JOIN books b ON c.book_id = b.id WHERE c.user_id = ?", 
    [$user_id]
);

if (empty($cartItems)) {
    http_response_code(400);
    echo json_encode(['error' => 'Cart is empty']);
    exit;
}

// Calculate total
$total = 0;
$itemsDescription = [];
foreach ($cartItems as $item) {
    $subtotal = $item['price'] * $item['quantity'];
    $total += $subtotal;
    $itemsDescription[] = $item['title'] . ' x' . $item['quantity'];
}

// Convert PHP to USD (or your target currency)
// Adjust this conversion rate as needed
$conversionRate = 58; // 1 USD = 58 PHP
$amountInUSD = $total / $conversionRate;

$description = implode(', ', $itemsDescription);
if (strlen($description) > 100) {
    $description = substr($description, 0, 97) . '...';
}

$order = paypalCreateOrder($amountInUSD, $description, $currency);

if (!$order || !isset($order['id'])) {
    http_response_code(500);
    echo json_encode(['error' => 'Could not create order']);
    exit;
}

echo json_encode(['id' => $order['id']]);