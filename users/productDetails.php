<?php

session_start();

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../classes/Product.php";
$database = new Database();
$db = $database->connect();
$product = new Product($db);
$id = $_GET['id'] ?? null;
if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    header("Location: products.php");
    exit;}
$item = $product->getProductByID($id);
if (!$item) {
    header("Location: products.php");
    exit;}
?>
<?php require_once __DIR__ . "/includes/header.php"; ?>
<?php require_once __DIR__ . "/includes/navbar.php"; ?>
<section class="product-details-container">
<h1>Product Details</h1>
    <div class="product-details-card">
        <div class="product-details-image">
            <?php if (!empty($item['image'])): ?>
                <img src="../<?= htmlspecialchars($item['image']) ?>"alt="<?= htmlspecialchars($item['name']) ?>">
            <?php endif; ?>
        </div>
        <div class="product-details-info">
            <h1><?= htmlspecialchars($item['name']) ?></h1>
            <div class="details-divider"></div>
            <p class="details-description">
                <?= htmlspecialchars($item['description']) ?></p>
            <p class="details-price">
                <?= $item['price'] ?></p>
            <div class="details-stock">
                <?php if ($item['stock'] > 0): ?>
                    <span class="stock-quantity"> Available Stock: <?= $item['stock'] ?> </span>
                <?php else: ?>
                    <span class="stock-quantity">Out of Stock</span>
                <?php endif; ?>
            </div>
            <?php if ($item['stock'] > 0): ?>

                <?php if (isset($_SESSION['user_id'])): ?>

                    <form action="Action/cartActions.php"
                          method="POST"
                          class="add-cart-form">

                        <input type="hidden" name="action" value="add">

                        <input type="hidden"
                               name="product_id"
                               value="<?= $item['id'] ?>">

                        <label>Quantity:</label>

                        <input
                            type="number"
                            name="quantity"
                            value="1"
                            min="1"
                            max="<?= $item['stock'] ?>"
                            required
                        >

                        <button type="submit" class="add-cart-btn">
                            Add to Cart
                        </button>

                    </form>

                <?php else: ?>

                    <div class="login-to-buy">
                        <p>Please login to add products to your cart.</p>
                        <a href="login.php">Login</a>
                    </div>

                <?php endif; ?>

            <?php endif; ?>

            <div class="product-benefits">
                <div>
                    <span>✓</span>
                    <p>Quality Watches</p>
                </div>

                <div>
                    <span>✓</span>
                    <p>Secure Shopping</p>
                </div>
            </div>

            <a href="products.php" class="back-products">
                Back to Products
            </a>

        </div>

    </div>

</section>