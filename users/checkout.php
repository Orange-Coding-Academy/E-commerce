<?php

session_start();

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../classes/Cart.php";
require_once __DIR__ . "/../classes/Users.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->connect();

$cart = new Cart($db);
$user = new Users($db);

$user_id = $_SESSION['user_id'];

$items = $cart->getcart($user_id);
$total = $cart->cartTotal($user_id);

$userData = $user->getUserByID($user_id);

if (empty($items)) {
    header("Location: cart.php");
    exit;
}

?>

<?php require_once __DIR__ . "/includes/header.php"; ?>
<?php require_once __DIR__ . "/includes/navbar.php"; ?>


<main class="user-page-container">

    <div class="page-heading">
        <span class="section-subtitle">ALMOST YOURS</span>
        <h1>Checkout</h1>
        <p>Complete your delivery information to place your order.</p>
    </div>

    <div class="checkout-layout">

        <section class="checkout-form-card">

            <h2>Delivery Information</h2>

            <p class="form-description">
                Please provide your delivery details.
            </p>

            <form action="Action/orderActions.php" method="POST"
                  class="checkout-form">

                <input type="hidden" name="action" value="place_order">

                <div>
                    <label for="delivery_address">Delivery Address</label>

                    <textarea
                        id="delivery_address"
                        name="delivery_address"
                        rows="4"
                        placeholder="Enter your complete address"
                        required
                    ><?= htmlspecialchars($userData['address'] ?? '') ?></textarea>
                </div>

                <div>
                    <label for="phone">Phone Number</label>

                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        placeholder="Enter your phone number"
                        value="<?= htmlspecialchars($userData['phone'] ?? '') ?>"
                        required
                    >
                </div>

                <div class="payment-method-box">
                    <span>Payment Method</span>
                    <strong>Cash On Delivery (COD)</strong>
                    <small>Pay when your order arrives.</small>
                </div>

                <button type="submit" class="place-order-btn">
                    Place Order
                </button>

            </form>

        </section>

        <aside class="checkout-summary">

            <h2>Order Summary</h2>

            <?php foreach ($items as $item): ?>

                <div class="checkout-item">

                    <div>
                        <h3><?= htmlspecialchars($item['name']) ?></h3>
                        <p>Quantity: <?= $item['quantity'] ?></p>
                    </div>

                    <strong>
                        <?= number_format($item['subtotal'], 2) ?>
                    </strong>

                </div>

            <?php endforeach; ?>

            <div class="summary-divider"></div>

            <div class="summary-total">
                <span>Total</span>
                <strong><?= number_format($total, 2) ?></strong>
            </div>

        </aside>

    </div>

</main>
