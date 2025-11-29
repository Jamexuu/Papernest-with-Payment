<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'backend/Database.php';
require_once 'helpers/loadEnv.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$db = new Database();
$user_id = $_SESSION['user_id'];
$cartItems = $db->fetchAll("SELECT c.*, b.title, b.front_image, b.author, b.price FROM cart c 
                            JOIN books b ON c.book_id = b.id WHERE c.user_id = ?", [$user_id]);

// Calculate total
$total = 0;
foreach ($cartItems as $item) {
    $subtotal = $item['price'] * $item['quantity'];
    $total += $subtotal;
}

// Prepare cart data for JavaScript
$cartData = [];
foreach ($cartItems as $item) {
    $cartData[] = [
        'id' => $item['book_id'],
        'title' => $item['title'],
        'quantity' => $item['quantity'],
        'price' => $item['price']
    ];
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Papernest | Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="container-fluid">
        <?php include 'components/navbar.php'; ?>
        <div class="container mt-3">
            <div class="row">
                <div class="col-6 p-3 shadow">
                    <div class="h2">Payment Details</div> <br>
                    <div class="h4">Select Payment Method</div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="paymentMethod" id="paypalPayment"
                            value="paypal">
                        <label class="form-check-label h4" for="paypalPayment">
                            <i class="bi bi-paypal p-3"></i>PayPal
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="paymentMethod" id="cashOnDelivery"
                            value="cash_on_delivery">
                        <label class="form-check-label h4" for="cashOnDelivery">
                            <i class="bi bi-cash-coin p-3"></i>Cash on Delivery
                        </label>
                    </div>

                    <div id="paypal-button-container" class="mt-3 d-none"></div>

                    <button id="placeOrderBtn" class="btn text-white px-5 py-2 mt-5 d-block"
                        style="background-color: var(--secondary-color);">
                        Place Order
                    </button>

                    <div id="orderMessage" class="mt-3"></div>
                </div>
                <div class="col-1"></div>
                <div class="col-5 shadow px-0 d-flex flex-column h-100">
                    <div class="h3 p-3" style="background-color: #E3E4E6">Order Summary</div>
                    <?php foreach ($cartItems as $item):
                        $subtotal = $item['price'] * $item['quantity'];
                        ?>
                        <div class="row px-5 mb-3">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4">
                                        <img src="assets/img/books/<?php echo htmlspecialchars($item['front_image']); ?>.jpg"
                                            alt="Book cover" class="me-3 flex-shrink-0 item-image bg-secondary" width="70"
                                            height="90">
                                    </div>
                                    <div class="col-8 text-start">
                                        <div class=""><?php echo htmlspecialchars($item['title']); ?></div>
                                        <div class="small">Quantity: <?php echo htmlspecialchars($item['quantity']); ?>
                                        </div><br>
                                        <div class="small">₱ <?php echo number_format($item['price'], 2); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="row px-5 mt-auto py-3">
                        <div class="col-6 border-top py-2">
                            <div class="h5">Order Total</div>
                        </div>
                        <div class="col-6 text-end border-top py-2">
                            <div class="h5">₱<?php echo number_format($total, 2); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'components/footer.php'; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script
        src="https://www.paypal.com/sdk/js?client-id=<?php echo htmlspecialchars($_ENV['PAYPAL_CLIENT_ID']); ?>&currency=<?php echo htmlspecialchars($_ENV['CURRENCY']); ?>"
        data-sdk-integration-source="button-factory"></script>

    <script>

        function createOrderOnServer() {
            return fetch('helpers/createOrder.php', {
                method: 'post',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    currency: <?php echo json_encode($_ENV['CURRENCY']); ?>
                })
            }).then(function (res) {
                return res.json();
            }).then(function(data) {
                if (data.error) {
                    showStatus('Error: ' + data.error, 'error');
                    return Promise.reject(new Error(data.error));
                }
                return data;
            }).catch(function (err) {
                showStatus('Could not create order: ' + err.message, 'error');
                return Promise.reject(err);
            });
        }

        function captureOrderOnServer(orderId) {
            hideStatus();

            return fetch('helpers/captureOrder.php', {
                method: 'post',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    orderID: orderId
                })
            }).then(function (res) {
                return res.json();
            }).catch(function (err) {
                showStatus('Could not capture order on server. ' + err.message, 'error');
                return Promise.reject(err);
            });
        }

        paypal.Buttons({
            createOrder: function (data, actions) {
                return createOrderOnServer().then(function (data) {
                    if (data && data.id) return data.id;
                    return Promise.reject(new Error('Order creation failed'));
                });
            },
            onApprove: function (data, actions) {
                return captureOrderOnServer(data.orderID).then(function (details) {
                    if (details && details.success && details.order_id) {
                        showStatus('Payment completed! Redirecting...', 'success');

                        setTimeout(function () {
                            window.location.href = 'success.php?id=' + encodeURIComponent(details.order_id);
                        }, 1000);
                    } else {
                        showStatus('Transaction could not be completed.', 'error');
                    }
                }).catch(function (err) {
                    showStatus('Transaction error: ' + err.message, 'error');
                })
            },
            onError: function (err) {
                showStatus('An error occurred during the transaction. ' + err.message, 'error');
            }
        }).render('#paypal-button-container');

        function showStatus(message, type) {
            const el = document.getElementById('status-message');
            if (!el) return;

            el.textContent = message;
            el.style.display = 'block';
            el.style.padding = '10px';
            el.style.marginBottom = '15px';
            el.style.borderRadius = '4px';

            if (type === 'error') {
                el.style.background = '#ffe6e6';
                el.style.color = '#a00';
                el.style.border = '1px solid #f5c2c2';
            } else if (type === 'success') {
                el.style.background = '#e6ffea';
                el.style.color = '#047a12';
                el.style.border = '1px solid #bfe6c8';
            } else {
                el.style.background = '#eef3ff';
                el.style.color = '#0b3d91';
                el.style.border = '1px solid #c9d9ff';
            }
        }

        function hideStatus() {
            const el = document.getElementById('status-message');
            if (!el) return;

            el.style.display = 'none';
        }

        const paypalRadio = document.getElementById('paypalPayment');
        const codRadio = document.getElementById('cashOnDelivery');
        const paypalBtnContainer = document.getElementById('paypal-button-container');
        const placeOrderBtn = document.getElementById('placeOrderBtn');

        function updatePaymentUI() {
            if (paypalRadio.checked) {
                paypalBtnContainer.classList.remove('d-none');
                placeOrderBtn.classList.add('d-none');
            } else {
                paypalBtnContainer.classList.add('d-none');
                placeOrderBtn.classList.remove('d-none');
            }
        }

        paypalRadio.addEventListener('change', updatePaymentUI);
        codRadio.addEventListener('change', updatePaymentUI);

        updatePaymentUI();

    </script>
</body>

</html>