<?php

session_start();

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../classes/Users.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->connect();

$user = new Users($db);
$profile = $user->getUserByID($_SESSION['user_id']);

if (!$profile) {
    header("Location: login.php");
    exit;
}

?>

<?php require_once __DIR__ . "/includes/header.php"; ?>
<?php require_once __DIR__ . "/includes/navbar.php"; ?>

<main class="user-page-container">

    <div class="page-heading">
        <span class="section-subtitle">YOUR ACCOUNT</span>
        <h1>My Profile</h1>
        <p>Manage your personal information.</p>
    </div>

    <?php if (!empty($_SESSION['toast'])): ?>
        <div class="auth-message <?= $_SESSION['toast']['type'] === 'success' ? 'message-success' : 'message-error' ?>"
             style="max-width:700px;">
            <?= htmlspecialchars($_SESSION['toast']['message']) ?>
        </div>
        <?php unset($_SESSION['toast']); ?>
    <?php endif; ?>

    <div class="checkout-form-card" style="max-width:700px;">

        <h2>Personal Information</h2>
        <p class="form-description">Update the details used for your orders.</p>

        <form action="Action/profileActions.php" method="POST" class="checkout-form">

            <div>
                <label>Full Name</label>
                <input type="text" name="name"
                       value="<?= htmlspecialchars($profile['name']) ?>" required>
            </div>

            <div>
                <label>Email Address</label>
                <input type="email" name="email"
                       value="<?= htmlspecialchars($profile['email']) ?>" required>
            </div>

            <div>
                <label>Phone</label>
                <input type="text" name="phone"
                       value="<?= htmlspecialchars($profile['phone'] ?? '') ?>">
            </div>

            <div>
                <label>Address</label>
                <textarea name="address" rows="3"><?= htmlspecialchars($profile['address'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="place-order-btn">Save Changes</button>

        </form>

    </div>

</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
