<?php
require_once "../config/Database.php";
require_once "../classes/Dashboard.php";
$database = new Database();
$db = $database->connect();
$dashboard = new Dashboard($db);
$statistics = $dashboard->getStatistics();
$orderStatus = $dashboard->getOrderByStatus();
$monthlyRevenue = $dashboard->getMonthlyRevenue();
$products = $dashboard->getTotaltProduct();
$categories = $dashboard->getTotaltCategories();
?>
<?php require_once __DIR__ . "/includes/header.php"; ?>
<?php require_once __DIR__ . "/includes/sidebar.php"; ?>
    <h1>Admin Dashboard</h1>
    <div class="stats-container">
        <div class="stat-card"><h3>Total Orders</h3>
            <p><?= $statistics['Total_Orders'] ?? 0 ?></p></div>
        <div class="stat-card"><h3>Total Revenue</h3>
            <p><?= $statistics['Total_Revenue'] ?? 0 ?> JD</p></div>
        <div class="stat-card"><h3>Total Customers</h3>
            <p><?= $statistics['Total_Customers'] ?? 0 ?></p></div>
        <div class="stat-card"><h3>Total Products</h3>
            <p><?= $products['TotalProduct'] ?? 0 ?></p>
        </div>
        <div class="stat-card"><h3>Total Categories</h3>
            <p><?= $categories['TotalCategory'] ?? 0 ?></p></div>
    </div>
    <div class="orders-status">
    <h2>Orders By Status</h2>
    <table>
        <thead>
            <tr>
                <th>Order Status</th>
                <th>Total Orders</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orderStatus as $status): ?>
                <tr>
                    <td><?= htmlspecialchars($status['order_status']) ?></td>
                    <td><?= $status['Total'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="monthly-revenue">
    <h2>Monthly Revenue</h2>
    <table>
        <thead>
            <tr>
                <th>Year</th>
                <th>Month</th>
                <th>Revenue</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($monthlyRevenue as $revenue): ?>
                <tr>
                    <td><?= htmlspecialchars($revenue['Year']) ?></td>
                    <td><?= htmlspecialchars($revenue['Month']) ?></td>
                    <td><?= $revenue['Revenue'] ?> JD</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . "/includes/footer.php"; ?>
