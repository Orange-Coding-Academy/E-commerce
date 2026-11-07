<?php

session_start();

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../classes/Users.php";


if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    header("Location: ../users/login.php");
    exit;
}

$database = new Database();
$db = $database->connect();

$user = new Users($db);
$profile = $user->getUserByID($_SESSION['user_id']);

if (!$profile) {
    header("Location: ../users/login.php");
    exit;
}

?>
<?php require_once __DIR__ . "/includes/header.php"; ?>
<?php require_once __DIR__ . "/includes/sidebar.php"; ?>

<?php if (isset($_SESSION['message'])): ?>

    <div id="toast" class="<?= htmlspecialchars($_SESSION['message_type']) ?>">
        <?= htmlspecialchars($_SESSION['message']) ?>
    </div>

    <?php
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
    ?>

<?php endif; ?>

<h1>My Profile</h1>

<div id="editUserForm">

    <h2>Personal Information</h2>

    <form action="../users/Action/profileActions.php" method="POST">

        <label>Name</label>
        <input type="text" name="name"
               value="<?= htmlspecialchars($profile['name']) ?>" required>

        <label>Email</label>
        <input type="email" name="email"
               value="<?= htmlspecialchars($profile['email']) ?>" required>

        <label>Phone</label>
        <input type="text" name="phone"
               value="<?= htmlspecialchars($profile['phone'] ?? '') ?>">

        <label>Address</label>
        <textarea name="address"><?= htmlspecialchars($profile['address'] ?? '') ?></textarea>

        <button type="submit">Save Changes</button>

    </form>

</div>

<script>
    const toast = document.getElementById('toast');

    if (toast) {
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 400);
        }, 3000);
    }
</script>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
