<?php
session_start();
require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../classes/Order.php";
$database = new Database();
$db = $database->connect();
$order = new Order($db);
$search = trim($_GET['search'] ?? '');
$order_status = $_GET['order_status'] ?? '';
$payment_status = $_GET['payment_status'] ?? '';
$orders = $order->filterOrders(
    $search,
    $order_status,
    $payment_status);
$orderDetails = null;
if (isset($_GET['view'])) {
    $orderDetails = $order->getOrderDetailsForAdmin($_GET['view']);
}
?>
<?php require_once __DIR__ . "/includes/header.php"; ?>
<?php require_once __DIR__ . "/includes/sidebar.php"; ?>
<?php if (isset($_SESSION['message'])): ?>
    <div id="toast" class="<?= htmlspecialchars($_SESSION['message_type']) ?>">
        <?= htmlspecialchars($_SESSION['message']) ?></div>
    <?php
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);?>
<?php endif; ?>
<h1>Manage Orders</h1>
<hr>
<h2>Filter Orders</h2>
<form method="GET" action="order.php">
    <input type="text"name="search"placeholder="Search by order ID or customer"value="<?= htmlspecialchars($search) ?>">
    <select name="order_status">
        <option value="">All Order Statuses</option>
        <?php
        $orderStatuses = ["Pending", "Processing", "Delivered", "Cancelled"];
        foreach ($orderStatuses as $status):?>
            <option
                value="<?= $status ?>"
                <?= $order_status === $status ? 'selected' : '' ?>>
                <?= $status ?>
            </option>
        <?php endforeach; ?>
    </select>
    <select name="payment_status">

        <option value="">All Payment Statuses</option>
        <option value="Paid"
            <?= $payment_status === 'Paid' ? 'selected' : '' ?>>Paid</option>
        <option value="Unpaid"<?= $payment_status === 'Unpaid' ? 'selected' : '' ?>>Unpaid </option>
    </select>

    <button type="submit">Filter</button>
    <a href="order.php">Reset</a>
</form>
<hr>
<h2>Orders List</h2>
<?php if (empty($orders)): ?>
    <p>No orders found.</p>
<?php else: ?>
<table border="1">
    <tr>
        <th>Customer</th>
        <th>Total</th>
        <th>Payment Method</th>
        <th>Payment Status</th>
        <th>Order Status</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($orders as $item): ?>
        <tr>

            <td><?= htmlspecialchars($item['CustomerName'] ?? 'Unknown') ?></td>
            <td><?= number_format($item['total'], 2) ?></td>
            <td><?= htmlspecialchars($item['payment_method']) ?></td>
            <td><?= htmlspecialchars($item['payment_status']) ?></td>
            <td><?= htmlspecialchars($item['order_status']) ?></td>
            <td>
            <a href="order.php?<?= htmlspecialchars(http_build_query(array_merge($_GET, ['view' => $item['id']]))) ?>#orderDetails">View Details</a></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>
<?php if ($orderDetails): ?>
<hr>
<div id="orderDetails">
    <h2>Order Details #<?= $orderDetails[0]['Order_ID'] ?></h2>
    <p>
        Customer:<?= htmlspecialchars($orderDetails[0]['CustomerName'] ?? 'Unknown') ?></p>
    <p>
        Delivery Address:<?= htmlspecialchars($orderDetails[0]['delivery_address']) ?>
    </p>

    <p>
        Phone:<?= htmlspecialchars($orderDetails[0]['phone']) ?>
    </p>

    <p>
        Order Date:<?= htmlspecialchars($orderDetails[0]['created_at']) ?>
    </p>

    <p>
        Total:<?= number_format($orderDetails[0]['total'], 2) ?>
    </p>

    <h3>Products</h3>

    <table border="1">

        <tr>
            <th>Product</th>
            <th>Image</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>

        <?php foreach ($orderDetails as $detail): ?>
            <tr>
                <td><?= htmlspecialchars($detail['ProductName'] ?? 'Product deleted') ?>
                </td>
                <td>
                    <?php if (!empty($detail['ProductImage'])): ?>
                        <img src="../<?= htmlspecialchars($detail['ProductImage']) ?>"width="60"alt="Product">
                    <?php endif; ?>
                </td>
                <td><?= $detail['price'] ?></td>

                <td><?= $detail['quantity'] ?></td>

                <td>
                    <?= $detail['price'] * $detail['quantity'] ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <h3>Update Order Status</h3>

    <form action="Action/orderActions.php" method="POST">

        <input type="hidden" name="action" value="update_order_status">

        <input type="hidden"name="order_id"value="<?= $orderDetails[0]['Order_ID'] ?>">
        <select name="order_status" required>

            <?php foreach ($orderStatuses as $status): ?>
                <option value="<?= $status ?>"
                    <?= $orderDetails[0]['order_status'] === $status ? 'selected' : '' ?>>
                    <?= $status ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Update Order Status</button>
    </form>
    <h3>Update Payment Status</h3>

    <form action="Action/orderActions.php" method="POST">

        <input type="hidden" name="action" value="update_payment_status">
        <input type="hidden"name="order_id"value="<?= $orderDetails[0]['Order_ID'] ?>">
        <select name="payment_status" required>
            <option value="Unpaid"
                <?= $orderDetails[0]['payment_status'] === 'Unpaid' ? 'selected' : '' ?>>
                Unpaid
            </option>
            <option value="Paid"
                <?= $orderDetails[0]['payment_status'] === 'Paid' ? 'selected' : '' ?>>
                Paid
            </option>
        </select>
        <button type="submit">Update Payment Status</button>
    </form>
    <br>
    <a href="order.php">Back to Orders</a>
</div>
<?php elseif (isset($_GET['view'])): ?>
    <p>Order not found.</p>
<?php endif; ?>
<script>
const toast = document.getElementById('toast');
if (toast) {
    setTimeout(() => {
        toast.style.opacity = '0';

        setTimeout(() => {
            toast.remove();
        }, 400);

    }, 3000);
}

</script>
<?php require_once __DIR__ . "/includes/footer.php"; ?>