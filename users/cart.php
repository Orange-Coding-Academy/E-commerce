<?php

session_start();

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../classes/Cart.php";

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Database
$database = new Database();
$db = $database->connect();

$cart = new Cart($db);

$user_id = $_SESSION['user_id'];

// Get cart items and total
$items = $cart->getcart($user_id);
$total = $cart->cartTotal($user_id);

?>

<?php require_once __DIR__ . "/includes/header.php"; ?>
<?php require_once __DIR__ . "/includes/navbar.php"; ?>


<main class="user-page-container">

    <div class="page-heading">
        <span class="section-subtitle">YOUR SELECTION</span>
        <h1>Shopping Cart</h1>
        <p>Review your selected watches before checkout.</p>
    </div>

    <?php if (!empty($items)): ?>

        <div class="cart-layout">

            <div class="cart-items">

                <?php foreach ($items as $item): ?>

                    <div class="cart-item-card">

                        <div class="cart-item-image">
                            <?php if (!empty($item['image'])): ?>
                                <img
                                    src="../<?= htmlspecialchars($item['image']) ?>"
                                    alt="<?= htmlspecialchars($item['name']) ?>"
                                >
                            <?php else: ?>
                                <div class="product-placeholder">TIMELESS</div>
                            <?php endif; ?>
                        </div>

                        <div class="cart-item-info">

                            <h3><?= htmlspecialchars($item['name']) ?></h3>

                            <p class="cart-item-price">
                                <?= number_format($item['price'], 2) ?>
                            </p>

                            <p class="cart-item-subtotal">
                                Subtotal:
                                <strong><?= number_format($item['subtotal'], 2) ?></strong>
                            </p>

                            <div class="cart-item-actions">

                                <form action="Action/cartActions.php" method="POST"
                                      class="cart-update-form">

                                    <input type="hidden" name="action" value="update">

                                    <input
                                        type="hidden"
                                        name="product_id"
                                        value="<?= $item['product_id'] ?>"
                                    >

                                    <input
                                        type="number"
                                        name="quantity"
                                        value="<?= $item['quantity'] ?>"
                                        min="1"
                                        required
                                        aria-label="Quantity"
                                    >

                                    <button type="submit" class="btn-dark">
                                        Update
                                    </button>

                                </form>

                                <form action="Action/cartActions.php" method="POST">

                                    <input type="hidden" name="action" value="delete">

                                    <input
                                        type="hidden"
                                        name="product_id"
                                        value="<?= $item['product_id'] ?>"
                                    >

                                    <button type="submit" class="remove-btn">
                                        Remove
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

            <aside class="cart-summary">

                <h2>Order Summary</h2>

                <div class="summary-row">
                    <span>Subtotal</span>
                    <strong><?= number_format($total, 2) ?></strong>
                </div>

                <div class="summary-row">
                    <span>Shipping</span>
                    <strong>Calculated at delivery</strong>
                </div>

                <div class="summary-divider"></div>

                <div class="summary-total">
                    <span>Total</span>
                    <strong><?= number_format($total, 2) ?></strong>
                </div>

                <a href="checkout.php" class="btn checkout-btn">
                    Proceed to Checkout
                </a>

                <a href="products.php" class="continue-shopping">
                    ← Continue Shopping
                </a>

            </aside>

        </div>

    <?php else: ?>

        <div class="empty-state">
            <div class="empty-icon">⌚</div>
            <h2>Your Cart Is Empty</h2>
            <p>You haven't added any watches to your collection yet.</p>
            <a href="products.php" class="btn btn-gold">
                Explore Collection
            </a>
        </div>

    <?php endif; ?>

</main>
