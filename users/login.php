
<?php

session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit;
}

?>

<?php require_once __DIR__ . "/includes/header.php"; ?>
<?php require_once __DIR__ . "/includes/navbar.php"; ?>

<main class="auth-page">

    <div class="auth-card">

        <div class="auth-brand">
            TIMELESS<span>.</span>
        </div>

        <span class="section-subtitle">WELCOME BACK</span>

        <h1>Login</h1>

        <p class="auth-description">
            Sign in to continue your shopping experience.
        </p>

        <?php if (!empty($_SESSION['toast'])): ?>
            <div class="auth-message <?= $_SESSION['toast']['type'] === 'success' ? 'message-success' : 'message-error' ?>">
                <?= htmlspecialchars($_SESSION['toast']['message']) ?>
            </div>
            <?php unset($_SESSION['toast']); ?>
        <?php endif; ?>

        <form action="Action/authActions.php" method="POST"
              class="auth-form">

            <input type="hidden" name="action" value="login">

            <div>
                <label>Email Address</label>
                <input
                    type="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                >
            </div>

            <div>
                <label>Password</label>
                <input
                    type="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                >
            </div>

            <button type="submit" class="auth-submit">
                Login to Account
            </button>

        </form>

        <p class="auth-switch">
            Don't have an account?
            <a href="register.php">Create Account</a>
        </p>

    </div>

</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
