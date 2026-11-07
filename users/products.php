<?php

session_start();

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../classes/Product.php";
require_once __DIR__ . "/../classes/Category.php";

$database = new Database();
$db = $database->connect();

$product = new Product($db);
$category = new Category($db);

// Get filter values
$search = trim($_GET['search'] ?? '');
$category_id = $_GET['category_id'] ?? '';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';

// Get products
$products = $product->filterProducts(
    $search,
    $category_id,
    $min_price,
    $max_price
);


// Get categories
$categories = $category->getAllCategories();

?>

<?php require_once __DIR__ . "/includes/header.php"; ?>
<?php require_once __DIR__ . "/includes/navbar.php"; ?>


<main>

    <section class="products-page-heading">
        <span class="section-subtitle">TIMELESS COLLECTION</span>
        <h1>Discover Our Watches</h1>
        <p>Find the perfect watch that matches your style.</p>
    </section>

    <!-- Filters -->

    <section class="filter-section">

        <form method="GET" action="products.php" class="products-filter">

            <div class="filter-field">
                <label>Search</label>
                <input
                    type="text"
                    name="search"
                    placeholder="Search watches..."
                    value="<?= htmlspecialchars($search) ?>"
                >
            </div>

            <div class="filter-field">
                <label>Category</label>

                <select name="category_id">
                    <option value="">All Categories</option>

                    <?php foreach ($categories as $cat): ?>
                        <option
                            value="<?= $cat['id'] ?>"
                            <?= (string)$category_id === (string)$cat['id'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-field">
                <label>Minimum Price</label>
                <input
                    type="number"
                    name="min_price"
                    placeholder="Min"
                    min="0"
                    step="0.01"
                    value="<?= htmlspecialchars($min_price) ?>"
                >
            </div>

            <div class="filter-field">
                <label>Maximum Price</label>
                <input
                    type="number"
                    name="max_price"
                    placeholder="Max"
                    min="0"
                    step="0.01"
                    value="<?= htmlspecialchars($max_price) ?>"
                >
            </div>

            <div class="filter-actions">
                <button type="submit">Apply Filters</button>
                <a href="products.php">Clear</a>
            </div>

        </form>

    </section>


    <!-- Products -->

    <section class="products-results">

        <div class="section-heading">
            <div>
                <span class="section-subtitle">OUR COLLECTION</span>
                <h2>All Watches</h2>
            </div>
        </div>

        <div class="products-grid">

            <?php if (!empty($products)): ?>

                <?php foreach ($products as $item): ?>

                    <div class="product-card">

                        <a
                            href="productDetails.php?id=<?= $item['id'] ?>"
                            class="product-image"
                        >

                            <?php if (!empty($item['image'])): ?>

                                <img
                                    src="../<?= htmlspecialchars($item['image']) ?>"
                                    alt="<?= htmlspecialchars($item['name']) ?>"
                                >

                            <?php else: ?>

                                <div class="product-placeholder">
                                    NO IMAGE
                                </div>

                            <?php endif; ?>

                            <span class="product-label">TIMELESS</span>

                        </a>

                        <div class="product-card-content">

                            <span class="product-category">
                                <?= htmlspecialchars($item['Category_name'] ?? 'Watches') ?>
                            </span>

                            <h3>
                                <?= htmlspecialchars($item['name']) ?>
                            </h3>

                            <p class="product-description">
                                <?= htmlspecialchars($item['description']) ?>
                            </p>

                            <div class="product-card-bottom">

                                <span class="product-price">
                                    <?= number_format($item['price'], 2) ?>
                                </span>

                                <a
                                    href="productDetails.php?id=<?= $item['id'] ?>"
                                    class="product-view"
                                >
                                    View Details →
                                </a>

                            </div>

                            <span class="<?= $item['stock'] > 0 ? 'stock-available' : 'stock-unavailable' ?>">
                                <?= $item['stock'] > 0 ? '● In Stock' : '● Out of Stock' ?>
                            </span>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="empty-products">
                    <h3>No Watches Found</h3>
                    <p>Try changing your search or filters.</p>
                    <a href="products.php" class="btn btn-gold">
                        View All Watches
                    </a>
                </div>

            <?php endif; ?>

        </div>

    </section>

</main>
