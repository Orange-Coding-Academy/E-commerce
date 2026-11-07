
<nav class="store-navbar">
    <a href="home.php" class="store-logo">
        TIMELESS<span>.</span>
    </a>

    <div class="store-links">
        <a href="home.php">Home</a>
        <a href="products.php">Collections</a>
        <a href="cart.php">Cart</a>

        <?php if (isset($_SESSION['user_id'])): ?>

            <a href="myOrders.php">My Orders</a>

            <span class="welcome-user">
                Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>
            </span>

            <form action="Action/authActions.php" method="POST"
                  class="logout-form">
                <input type="hidden" name="action" value="logout">
                <button type="submit" class="nav-logout">Logout</button>
            </form>
          <a href="profile.php">Profile</a>
        <?php else: ?>

            <a href="login.php">Login</a>
            <a href="register.php" class="nav-register">Sign Up</a>

        <?php endif; ?>
    </div>
</nav>
