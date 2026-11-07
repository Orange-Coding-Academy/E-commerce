
<?php
session_start();
require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../classes/Users.php";

$database = new Database();
$db = $database->connect();

$user = new Users($db);

$users = $user->getusers();

$editUser = null;

if (isset($_GET['edit'])) {
    $editUser = $user->getUserByID($_GET['edit']);
}
?>
<?php require_once __DIR__ . "/includes/header.php"; ?>
<?php require_once __DIR__ . "/includes/sidebar.php"; ?>

     <?php if (isset($_SESSION['message'])): ?>

    <div id="toast" class="<?= $_SESSION['message_type'] ?>">
        <?= htmlspecialchars($_SESSION['message']) ?>
    </div>

    <?php
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
    ?>

<?php endif; ?>

<h1>Manage Users</h1>

<?php if ($editUser): ?>

    <div id="editUserForm" style="display: none;">

    <h2>Edit User</h2>

    <form action="Action/userActions.php" method="POST">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" value="<?= $editUser['id'] ?>">

        <label>Name</label>
        <input type="text" name="name"
               value="<?= htmlspecialchars($editUser['name']) ?>" required>

        <label>Email</label>
        <input type="email" name="email"
               value="<?= htmlspecialchars($editUser['email']) ?>" required>

        <label>Phone</label>
        <input type="text" name="phone"
               value="<?= htmlspecialchars($editUser['phone'] ?? '') ?>">

        <label>Address</label>
        <textarea name="address"><?= htmlspecialchars($editUser['address'] ?? '') ?></textarea>

        <button type="submit">Update User</button>
        <a href="users.php">Cancel</a>
    </form>

</div>

<?php endif; ?>

<hr>

<h2>Users List</h2>

<table border="1" cellpadding="10">

    <tr>
        <th>Name</th>
        <th>Address</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($users as $item): ?>

        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= htmlspecialchars($item['address'] ?? '') ?></td>

            <td>

              <a href="users.php?edit=<?= $item['id'] ?>#editUserForm"><i class="fa-solid fa-pen"></i></a>

                <form action="Action/userActions.php" method="POST"
                      style="display:inline;">

                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">

                    <button type="submit"><i class="fa-solid fa-trash-can"></i></button>

                </form>

            </td>
        </tr>

    <?php endforeach; ?>

</table>
<script>
    const editForm = document.getElementById('editUserForm');

    if (editForm) {
        editForm.style.display = 'block';
    }
</script>
<?php require_once __DIR__ . "/includes/footer.php"; ?>

