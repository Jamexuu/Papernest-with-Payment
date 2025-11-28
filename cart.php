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
                        <div class="row py-3 border align-items-center cart-item" data-item-id="1">
                            <div class="col-5 d-flex align-items-center">
                                <img src="" alt="Book cover" class="me-3 flex-shrink-0 item-image bg-secondary"
                                    width="60" height="80">
                                <div>
                                    <div class="item-title">Book Title</div>
                                    <div class="text-muted small item-format">(Format)</div>
                                </div>
                            </div>
                            <div class="col-3 text-center">
                                <div class="d-inline-flex align-items-center border rounded">
                                    <button class="btn btn-sm btn-light border-0 qty-decrease">-</button>
                                    <input type="text"
                                        class="form-control form-control-sm border-0 text-center w-auto px-2 item-quantity"
                                        value="1" readonly>
                                    <button class="btn btn-sm btn-light border-0 qty-increase">+</button>
                                </div>
                            </div>
                            <div class="col-3 text-center fw-bold">
                                <h6 class="item-subtotal">₱ 0.00</h6>
                            </div>
                            <div class="col-1 text-center">
                                <button class="btn btn-sm btn-light item-remove">&times;</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-5 p-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-4 fw-bold">ORDER SUMMARY</h5>

                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                            <span>Subtotal</span>
                            <span id="orderSubtotal">₱ 0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-4 pb-3 border-bottom fw-bold">
                            <h6>Order Total</h6>
                            <h6 id="orderTotal">₱ 0.00</h6>
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

    <script>
        let cartData = [];

        function addCartItem(item) {
            const cartItems = document.getElementById('cartItems');

            const itemHTML = `
                <div class="row py-3 border-bottom align-items-center cart-item" data-item-id="${item.id}">
                    <div class="col-5 d-flex align-items-center">
                        <img src="${item.image || ''}" alt="${item.title}" class="me-3 flex-shrink-0 item-image ${!item.image ? 'bg-secondary' : ''}" width="60" height="80">
                        <div>
                            <div class="item-title">${item.title}</div>
                            <div class="text-muted small item-format">(${item.format})</div>
                        </div>
                    </div>
                    <div class="col-3 text-center">
                        <div class="d-inline-flex align-items-center border rounded">
                            <button class="btn btn-sm btn-light border-0 qty-decrease" onclick="updateQuantity(${item.id}, -1)">-</button>
                            <input type="text" class="form-control form-control-sm border-0 text-center w-auto px-2 item-quantity" value="${item.quantity}" readonly>
                            <button class="btn btn-sm btn-light border-0 qty-increase" onclick="updateQuantity(${item.id}, 1)">+</button>
                        </div>
                    </div>
                    <div class="col-3 text-center">
                        <strong class="item-subtotal">₱ ${(item.price * item.quantity).toFixed(2)}</strong>
                    </div>
                    <div class="col-1 text-center">
                        <button class="btn btn-sm btn-light item-remove" onclick="removeItem(${item.id})">&times;</button>
                    </div>
                </div>
            `;

            cartItems.innerHTML += itemHTML;
            cartData.push(item);
            updateOrderSummary();
        }

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

        function refreshCart() {
            const cartItems = document.getElementById('cartItems');
            cartItems.innerHTML = '';
            cartData.forEach(item => {
                const itemHTML = `
                    <div class="row py-3 border-bottom align-items-center cart-item" data-item-id="${item.id}">
                        <div class="col-5 d-flex align-items-center">
                            <img src="${item.image || ''}" alt="${item.title}" class="me-3 flex-shrink-0 item-image ${!item.image ? 'bg-secondary' : ''}" width="60" height="80">
                            <div>
                                <div class="item-title">${item.title}</div>
                                <div class="text-muted small item-format">(${item.format})</div>
                            </div>
                        </div>
                        <div class="col-3 text-center">
                            <div class="d-inline-flex align-items-center border rounded">
                                <button class="btn btn-sm btn-light border-0 qty-decrease" onclick="updateQuantity(${item.id}, -1)">-</button>
                                <input type="text" class="form-control form-control-sm border-0 text-center w-auto px-2 item-quantity" value="${item.quantity}" readonly>
                                <button class="btn btn-sm btn-light border-0 qty-increase" onclick="updateQuantity(${item.id}, 1)">+</button>
                            </div>
                        </div>
                        <div class="col-3 text-center">
                            <strong class="item-subtotal">₱ ${(item.price * item.quantity).toFixed(2)}</strong>
                        </div>
                        <div class="col-1 text-center">
                            <button class="btn btn-sm btn-light item-remove" onclick="removeItem(${item.id})">&times;</button>
                        </div>
                    </div>
                `;
                cartItems.innerHTML += itemHTML;
            });
            updateOrderSummary();
        }

        function updateOrderSummary() {
            const subtotal = cartData.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            document.getElementById('orderSubtotal').textContent = `₱ ${subtotal.toFixed(2)}`;
            document.getElementById('orderTotal').textContent = `₱ ${subtotal.toFixed(2)}`;
        }
        document.getElementById('checkoutBtn').addEventListener('click', function () {
            console.log('Checkout clicked', cartData);
        });
        
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>