
<?php
session_start();

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../classes/Product.php";
require_once __DIR__ . "/../classes/Category.php";

$database = new Database();
$db = $database->connect();

$product = new Product($db);
$category = new Category($db);

$search = trim($_GET['search'] ?? '');
$category_id = $_GET['category_id'] ?? '';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';

$products = $product->filterProducts(
    $search,
    $category_id,
    $min_price,
    $max_price
);
$categories = $category->getAllCategories();

$editProduct = null;

if (isset($_GET['edit'])) {
    $editProduct = $product->getProductByID($_GET['edit']);
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
<h1>Manage Products</h1>

<button type="button" onclick="showAddForm()">Add Product</button>
<div id="addProductForm" style="display: none;">
    <h2>Add New Product</h2>
    <form action="Action/productActions.php" method="POST"enctype="multipart/form-data">
        <input type="hidden" name="action" value="add">
        <label>Product Name</label>
        <input type="text" name="name" required>
        <br><br>
        <label>Description</label>
        <textarea name="description"></textarea>
        <br><br>
        <label>Price</label>
        <input type="number" name="price" step="0.01" min="0" required>
        <br><br>
        <label>Stock</label>
        <input type="number" name="stock" min="0" required>
        <br><br>
        <label>Category</label>
        <select name="category_id" required>
            <option value="">Select Category</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <label>Product Image</label>
        <input type="file" name="image"accept="image/jpeg,image/png,image/webp" required>
        <br><br>
        <button type="submit">Save Product</button>
        <button type="button" onclick="hideAddForm()">Cancel</button>
    </form>
</div>
<hr>
<?php if ($editProduct): ?>
<div id="editProductForm" style="display: none;">
    <h2>Edit Product</h2>
    <form action="Action/productActions.php" method="POST"enctype="multipart/form-data">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" value="<?= $editProduct['id'] ?>">
        <label>Product Name</label>
        <input type="text" name="name"value="<?= htmlspecialchars($editProduct['name']) ?>" required>
        <br><br>
        <label>Description</label>
        <textarea name="description"><?= htmlspecialchars($editProduct['description'] ?? '') ?></textarea>
        <br><br>
        <label>Price</label>
        <input type="number" name="price" value="<?= htmlspecialchars($editProduct['price']) ?>"step="0.01" min="0" required>
        <br><br>
        <label>Stock</label>
        <input type="number" name="stock"value="<?= htmlspecialchars($editProduct['stock']) ?>" min="0" required>
        <br><br>
        <label>Category</label>
        <select name="category_id" required>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"
                    <?= $editProduct['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <p>Current Image:</p>
        <?php if (!empty($editProduct['image'])): ?>
            <img src="../<?= htmlspecialchars($editProduct['image']) ?>" width="100" alt="Product Image">
        <?php endif; ?>
        <br><br>
        <label>Choose New Image</label>
        <input type="file" name="image"accept="image/jpeg,image/png,image/webp">
        <br><br>
        <button type="submit">Update Product</button>
        <a href="products.php">Cancel</a>
    </form>
</div>
<?php endif; ?>
<hr>
<h2>Products List</h2>
<form method="GET" action="products.php">

    <input
        type="text"name="search"placeholder="Search by product name"value="<?= htmlspecialchars($search) ?>">
    <select name="category_id">
        <option value="">All Categories</option>
        <?php foreach ($categories as $cat): ?>
            <option
                value="<?= $cat['id'] ?>"
                <?= $category_id == $cat['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <input
        type="number"name="min_price"placeholder="Minimum Price" min="0"step="0.01"value="<?= htmlspecialchars($min_price) ?>">
    <input
        type="number"name="max_price"placeholder="Maximum Price"min="0"step="0.01" value="<?= htmlspecialchars($max_price) ?>">
    <button type="submit">Filter</button>
    <a href="products.php">Reset</a>
</form>
<br>
<table border="1" cellpadding="10">
    <tr>
        <th>Name</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Category</th>
        <th>Image</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($products as $item): ?>

        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= $item['price'] ?></td>
            <td><?= $item['stock'] ?></td>
            <td><?= htmlspecialchars($item['Category_name'] ?? 'No Category') ?></td>
            <td>
                <?php if (!empty($item['image'])): ?>
                    <img src="../<?= htmlspecialchars($item['image']) ?>"
                         width="60" alt="Product Image"><?php endif; ?>
            </td>
            <td>
                <a href="products.php?<?= htmlspecialchars(http_build_query(array_merge($_GET, ['edit' => $item['id']]))) ?>#editProductForm">
                   <i class="fa-solid fa-pen"></i></a>
                <form action="Action/productActions.php" method="POST"style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                    <button type="submit"><i class="fa-solid fa-trash-can"></i></button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<script>
    function showAddForm() {
        document.getElementById('addProductForm').style.display = 'block';
        document.getElementById('editProductForm')?.style.setProperty('display', 'none');}

    function hideAddForm() {
        document.getElementById('addProductForm').style.display = 'none';}
    const editForm = document.getElementById('editProductForm');
    if (editForm) {
        editForm.style.display = 'block';}
    const toast = document.getElementById('toast');
    if (toast) {
        setTimeout(() => {
            toast.style.opacity = '0';

            setTimeout(() => {
                toast.remove();
            }, 400);

        }, 3000);}
</script>
<?php require_once __DIR__ . "/includes/footer.php"; ?>

