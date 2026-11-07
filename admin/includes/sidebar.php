
<aside class="admin-sidebar">
    <div class="sidebar-brand">
        TIMELESS<span>.</span>
        <small>ADMIN PANEL</small>
    </div>
    <p class="sidebar-label">MAIN MENU</p>
    <ul>
        <li><a href="Dashboard.php"><span>▦</span> Dashboard</a></li>
        <li><a href="products.php"><span>⌚</span> Products</a></li>
        <li><a href="categories.php"><span>▤</span> Categories</a></li>
        <li><a href="users.php"><span>♙</span> Users </a></li>
        <li><a href="order.php"><span>▣</span> Orders</a></li>
        <li><a href="profile.php"><span>☺</span> Profile</a></li>
    </ul>
    <div class="sidebar-bottom">
    <a href="../users/home.php">View Store</a>
    <form action="../users/Action/authActions.php" method="POST">
        <input type="hidden" name="action" value="logout">
        <button type="submit" class="sidebar-logout">Logout</button>
    </form>
</div>
</aside>
<main class="admin-content">
