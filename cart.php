<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'backend/Database.php';
$db = new Database();
$user_id = $_SESSION['user_id'];
$cartItems = $db->fetchAll("SELECT c.*, b.title, b.front_image, b.author, b.price FROM cart c JOIN books b ON c.book_id = b.id WHERE c.user_id = ?", [$user_id]);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Papernest | Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="container-fluid">
        <?php include 'components/navbar.php'; ?>
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-7 p-3">
                    <div class="h3">Your Shopping Cart</div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-7 p-3">
                    <div id="cartContainer">
                        <div class="row bg-light py-2 border">
                            <div class="col-5 fw-bold">
                                <h6>Item</h6>
                            </div>
                            <div class="col-3 text-center">
                                <h6>Quantity</h6>
                            </div>
                            <div class="col-3 text-center">
                                <h6>Subtotal</h6>
                            </div>
                            <div class="col-1"></div>
                        </div>
                        <div id="cartItems">
                            <?php foreach ($cartItems as $item): ?>
                                <div class="row py-3 border align-items-center cart-item"
                                    data-item-id="<?php echo $item['id']; ?>">
                                    <div class="col-5 d-flex align-items-center">
                                        <img src="assets/img/books/<?php echo htmlspecialchars($item['front_image']); ?>.jpg"
                                            alt="Book cover" class="me-3 flex-shrink-0 item-image bg-secondary" width="60"
                                            height="80">
                                        <div>
                                            <div class="item-title"><?php echo htmlspecialchars($item['title']); ?></div>
                                            <div class="text-muted small item-format">
                                                (<?php echo htmlspecialchars($item['author']); ?>)</div>
                                        </div>
                                    </div>
                                    <div class="col-3 text-center">
                                        <div class="d-inline-flex align-items-center border rounded">
                                            <input type="text"
                                                class="form-control form-control-sm border-0 text-center w-auto px-2 item-quantity"
                                                value="<?php echo $item['quantity']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-3 text-center fw-bold">
                                        <h6 class="item-subtotal">₱ <?php echo number_format($item['price'] * $item['quantity'], 2); ?></h6>
                                    </div>
                                    <div class="col-1 text-center">
                                        <button class="btn btn-sm btn-light item-remove">&times;</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-5 p-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center mb-4 fw-bold">ORDER SUMMARY</h5>

                            <?php
                            $total = 0;
                            foreach ($cartItems as $item) {
                                $total += $item['price'] * $item['quantity'];
                            }
                            ?>
                            <div class="d-flex justify-content-between mb-4 pb-3 border-bottom fw-bold">
                                <h6>Order Total</h6>
                                <h6 id="orderTotal">₱ <?php echo number_format($total, 2); ?></h6>
                            </div>
                            <button class="btn btn-warning w-100 mb-4 text-white fw-bold" id="checkoutBtn">
                                CHECKOUT
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'components/footer.php'; ?>
    </div>
    <script>
        function updateQuantity(itemId, change) {
            const item = cartData.find(i => i.id === itemId);
            if (item) {
                item.quantity = Math.max(1, item.quantity + change);
                refreshCart();
            }
        }

        function removeItem(itemId) {
            cartData = cartData.filter(i => i.id !== itemId);
            refreshCart();
        }

        function updateOrderSummary() {
            const subtotal = cartData.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            document.getElementById('orderSubtotal').textContent = `₱ ${subtotal.toFixed(2)}`;
            document.getElementById('orderTotal').textContent = `₱ ${subtotal.toFixed(2)}`;
        }
        document.getElementById('checkoutBtn').addEventListener('click', function () {
            window.location.href = 'checkOut.php';
        });

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>