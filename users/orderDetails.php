<?php

session_start();

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../classes/Order.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$order_id = $_GET['id'] ?? null;
if (!$order_id || !filter_var($order_id, FILTER_VALIDATE_INT)) {
    header("Location: myOrders.php");
    exit;
}
$database = new Database();
$db = $database->connect();
$order = new Order($db);
$user_id = $_SESSION['user_id'];
$items = $order->getOrderDetails($order_id, $user_id);
if (empty($items)) {
    header("Location: myOrders.php");
    exit;
}
$orderInfo = $items[0];
?>
<?php require_once __DIR__ . "/includes/header.php"; ?>
<?php require_once __DIR__ . "/includes/navbar.php"; ?>

<main class="user-page-container">

    <div class="page-heading">
        <span class="section-subtitle">PURCHASE INFORMATION</span>
        <h1>Order Details</h1>
        <p>Review the details of your purchase.</p>
    </div>

    <section class="order-info-card">

        <div class="order-info-heading">
            <div>
                <span class="section-subtitle">ORDER REFERENCE</span>
                <h2>Order #<?= $orderInfo['Order_ID'] ?></h2>
            </div>
            <span class="order-status unpaid">
                <?= htmlspecialchars($orderInfo['payment_status']) ?>
            </span>
        </div>
        <div class="order-info-grid">
            <div>
                <span>Order Date</span>
                <strong><?= htmlspecialchars($orderInfo['created_at']) ?></strong>
            </div>

            <div>
                <span>Payment Method</span>
                <strong><?= htmlspecialchars($orderInfo['payment_method']) ?></strong>
            </div>

            <div>
                <span>Delivery Address</span>
                <strong><?= htmlspecialchars($orderInfo['delivery_address']) ?></strong>
            </div>

            <div>
                <span>Phone Number</span>
                <strong><?= htmlspecialchars($orderInfo['phone']) ?></strong>
            </div>

        </div>
    </section>
    <section class="ordered-products-card">
        <h2>Ordered Products</h2>
        <?php foreach ($items as $item): ?>
            <div class="ordered-product">

                <div class="ordered-product-image">

                    <?php if (!empty($item['ProductImage'])): ?>

                        <img
                            src="../<?= htmlspecialchars($item['ProductImage']) ?>"
                            alt="<?= htmlspecialchars($item['ProductName'] ?? 'Product') ?>"
                        >
                    <?php else: ?>

                        <div class="product-placeholder">TIMELESS</div>

                    <?php endif; ?>

                </div>

                <div class="ordered-product-info">

                    <h3>
                        <?= htmlspecialchars($item['ProductName'] ?? 'Deleted Product') ?>
                    </h3>
                    <p>Quantity: <?= $item['quantity'] ?></p>
                    <p> Unit Price: <?= $item['price']?></p>
                </div>

                <strong class="ordered-product-total">
                    <?= $item['price'] * $item['quantity'] ?>
                </strong>

            </div>

        <?php endforeach; ?>

        <div class="order-grand-total">
            <span>Order Total</span>
            <strong><?= $orderInfo['total'] ?></strong>
        </div>

    </section>

    <a href="myOrders.php" class="back-products">
        ← Back to My Orders
    </a>

</main>
