
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

        <span class="section-subtitle">JOIN OUR COLLECTION</span>

        <h1>Create Account</h1>

        <p class="auth-description">
            Create your account and discover timeless elegance.
        </p>

        <?php if (!empty($_SESSION['toast'])): ?>
            <div class="auth-message <?= $_SESSION['toast']['type'] === 'success' ? 'message-success' : 'message-error' ?>">
                <?= htmlspecialchars($_SESSION['toast']['message']) ?>
            </div>
            <?php unset($_SESSION['toast']); ?>
        <?php endif; ?>

        <form action="Action/authActions.php" method="POST"
              class="auth-form">

            <input type="hidden" name="action" value="register">

            <div>
                <label>Full Name</label>
                <input
                    type="text"
                    name="name"
                    placeholder="Enter your full name"
                    required
                >
            </div>

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
                    placeholder="Create a password"
                    minlength="6"
                    required
                >
            </div>

            <div>
                <label>Phone Number</label>
                <input
                    type="tel"
                    name="phone"
                    placeholder="Enter your phone number"
                    required
                >
            </div>

            <div>
                <label>Address</label>
                <textarea
                    name="address"
                    rows="3"
                    placeholder="Enter your address"
                    required
                ></textarea>
            </div>

            <button type="submit" class="auth-submit">
                Create Account
            </button>

        </form>

        <p class="auth-switch">
            Already have an account?
            <a href="login.php">Login</a>
        </p>

    </div>

</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
