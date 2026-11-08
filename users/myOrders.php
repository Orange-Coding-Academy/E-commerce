<?php
session_start();
require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../classes/Order.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$database = new Database();
$db = $database->connect();

$order = new Order($db);

$user_id = $_SESSION['user_id'];

$orders = $order->getUsersOrders($user_id);
?>

<?php require_once __DIR__ . "/includes/header.php"; ?>
<?php require_once __DIR__ . "/includes/navbar.php"; ?>

<main class="user-page-container">

    <div class="page-heading">
        <span class="section-subtitle">YOUR PURCHASES</span>
        <h1>My Orders</h1>
        <p>Track and manage your watch orders.</p>
    </div>
    <?php if (!empty($orders)): ?>
        <div class="orders-table-wrapper">
            <table class="orders-table">

                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
                        <th>Date</th>
                        <th>Details</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($orders as $item): ?>

                        <tr>
                            <td>
                                #<?= $item['id'] ?>
                            </td>
                            <td class="order-price">
                                <?= $item['total'] ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($item['payment_method']) ?>
                            </td>
                            <td>
                                <span class="order-status <?= strtolower($item['payment_status']) ?>">
                                    <?= htmlspecialchars($item['payment_status']) ?>
                                </span>
                            </td>

                            <td>
                                <span class="order-status <?= strtolower($item['order_status']) ?>">
                                    <?= htmlspecialchars($item['order_status']) ?>
                                </span>
                            </td>

                            <td>
                                <?= htmlspecialchars($item['created_at']) ?>
                            </td>

                            <td>
                                <a
                                    href="orderDetails.php?id=<?= $item['id'] ?>"
                                    class="table-details-link"
                                >
                                    View Details →
                                </a>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php else: ?>

        <div class="empty-state">
            <div class="empty-icon">⌚</div>
            <h2>No Orders Yet</h2>
            <p>Your purchases will appear here once you place an order.</p>
            <a href="products.php" class="btn btn-gold">
                Explore Watches
            </a>
        </div>

    <?php endif; ?>

</main>
