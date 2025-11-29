<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

require_once __DIR__ . '/paypalApi.php';
require_once __DIR__ . '/loadEnv.php';
require_once __DIR__ . '/../backend/Database.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    ob_clean();
    http_response_code(401);
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);

if (!isset($input['orderID'])) {
    ob_clean();
    http_response_code(400);
    echo json_encode(['error' => 'Missing orderID']);
    exit;
}

$orderId = $input['orderID'];

$capture = paypalCaptureOrder($orderId);

if (!$capture) {
    ob_clean();
    http_response_code(500);
    echo json_encode(['error' => 'Could not capture order']);
    exit;
}

$status = $capture['status'] ?? null;

if ($status !== 'COMPLETED') {
    ob_clean();
    http_response_code(400);
    echo json_encode(['error' => 'Transaction not completed', 'status' => $status]);
    exit;
}

// Extract transaction data
$payer = $capture['payer'] ?? [];
$payer_email = $payer['email_address'] ?? null;
$payer_name = trim(($payer['name']['given_name'] ?? '') . ' ' . ($payer['name']['surname'] ?? ''));

$purchase_unit = $capture['purchase_units'][0] ?? null;
$capture_data = $purchase_unit['payments']['captures'][0] ?? null;

$transaction_id = $capture_data['id'] ?? null;
$amount = $capture_data['amount']['value'] ?? null;
$currency = $capture_data['amount']['currency_code'] ?? 'USD';

// Save to database
try {
    $db = new Database();
    
    // Get cart items for this user
    $cartItems = $db->fetchAll(
        "SELECT c.*, b.price FROM cart c JOIN books b ON c.book_id = b.id WHERE c.user_id = ?", 
        [$user_id]
    );
    
    if (empty($cartItems)) {
        throw new Exception('Cart is empty');
    }
    
    // Calculate total in PHP
    $totalPHP = 0;
    foreach ($cartItems as $item) {
        $totalPHP += $item['price'] * $item['quantity'];
    }
    
    // Start transaction
    $db->getConnection()->beginTransaction();
    
    // Insert into orders table
    $sql = "INSERT INTO orders (user_id, total_amount, paypal_transaction_id, payment_status) 
            VALUES (?, ?, ?, ?)";
    $db->execute($sql, [$user_id, $totalPHP, $transaction_id, 'paid']);
    $order_id = $db->getConnection()->lastInsertId();
    
    // Insert order items
    foreach ($cartItems as $item) {
        $sql = "INSERT INTO order_items (order_id, book_id, price, quantity) 
                VALUES (?, ?, ?, ?)";
        $db->execute($sql, [$order_id, $item['book_id'], $item['price'], $item['quantity']]);
    }
    
    // Insert into transactions table (for PayPal record)
    $sql = "INSERT INTO transactions (paypal_order_id, paypal_transaction_id, amount, currency, status, payer_email, payer_name) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $db->execute($sql, [$orderId, $transaction_id, $amount, $currency, $status, $payer_email, $payer_name]);
    
    // Clear user's cart
    $sql = "DELETE FROM cart WHERE user_id = ?";
    $db->execute($sql, [$user_id]);
    
    // Commit transaction
    $db->getConnection()->commit();
    
    ob_clean();
    echo json_encode([
        'success' => true,
        'transaction_id' => $transaction_id,
        'order_id' => $order_id
    ]);
    
} catch (Exception $e) {
    // Rollback on error
    if ($db->getConnection()->inTransaction()) {
        $db->getConnection()->rollBack();
    }
    
    error_log('Database error: ' . $e->getMessage());
    ob_clean();
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}