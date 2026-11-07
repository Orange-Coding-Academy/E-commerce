
<?php

session_start();

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../classes/Category.php";

$database = new Database();
$db = $database->connect();

$category = new Category($db);

$categories = $category->getAllCategories();

$editCategory = null;

if (isset($_GET['edit'])) {
    $editCategory = $category->getCategoryByID($_GET['edit']);
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

<h1>Manage Categories</h1>

<button type="button" onclick="showCategoryForm()">
    Add Category
</button>

<div id="categoryForm" style="display:none;">

    <h2><?= $editCategory ? 'Edit Category' : 'Add Category' ?></h2>

    <form action="Action/categoryActions.php"
          method="POST"
          enctype="multipart/form-data">

        <?php if ($editCategory): ?>

            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" value="<?= $editCategory['id'] ?>">

        <?php else: ?>

            <input type="hidden" name="action" value="add">

        <?php endif; ?>

        <label>Category Name</label>

        <input
            type="text"
            name="name"
            value="<?= htmlspecialchars($editCategory['name'] ?? '') ?>"
            required
        >

        <br><br>

        <label>Description</label>

        <textarea name="description"><?= htmlspecialchars($editCategory['description'] ?? '') ?></textarea>

        <br><br>

        <?php if ($editCategory): ?>

            <label>Current Image</label>

            <br>

            <img
                src="../<?= htmlspecialchars($editCategory['image']) ?>"
                width="100"
                alt="Category Image"
            >

            <br><br>

            <label>Change Image (Optional)</label>

            <input
                type="file"
                name="image"
                accept="image/*"
            >

        <?php else: ?>

            <label>Category Image</label>

            <input
                type="file"
                name="image"
                accept="image/*"
                required
            >

        <?php endif; ?>

        <br><br>

        <button type="submit">
            <?= $editCategory ? 'Update Category' : 'Add Category' ?>
        </button>

        <?php if ($editCategory): ?>

            <a href="categories.php">Cancel</a>

        <?php endif; ?>

    </form>

</div>

<hr>

<h2>Categories List</h2>

<table border="1" cellpadding="10">

    <tr>
       
        <th>Image</th>
        <th>Name</th>
        <th>Description</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($categories as $item): ?>

        <tr>
            <td>
                <?php if (!empty($item['image'])): ?>

                    <img
                        src="../<?= htmlspecialchars($item['image']) ?>"
                        width="80"
                        alt="Category Image"
                    >

                <?php endif; ?>
            </td>

            <td><?= htmlspecialchars($item['name']) ?></td>

            <td><?= htmlspecialchars($item['description']) ?></td>

            <td>

                <a href="categories.php?edit=<?= $item['id'] ?>">
                     <i class="fa-solid fa-pen"></i>
                </a>

                <form
                    action="actions/categoryActions.php"
                    method="POST"
                    style="display:inline;"
                >

                    <input type="hidden" name="action" value="delete">

                    <input type="hidden" name="id" value="<?= $item['id'] ?>">

                    <button type="submit"><i class="fa-solid fa-trash-can"></i></button>

                </form>

            </td>

        </tr>

    <?php endforeach; ?>

</table>

<script>

function showCategoryForm() {
    document.getElementById("categoryForm").style.display = "block";
}

<?php if ($editCategory): ?>
    showCategoryForm();
<?php endif; ?>

const toast = document.getElementById("toast");

if (toast) {
    setTimeout(function() {
        toast.style.display = "none";
    }, 3000);
}

</script>
<?php require_once __DIR__ . "/includes/footer.php"; ?>
