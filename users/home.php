<?php

session_start();

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../classes/Product.php";
require_once __DIR__ . "/../classes/Category.php";

$database = new Database();
$db = $database->connect();

$product = new Product($db);
$category = new Category($db);

$products = $product->getAllProduct();
$categories = $category->getAllCategories();

?>

<?php require_once __DIR__ . "/includes/header.php"; ?>
<?php require_once __DIR__ . "/includes/navbar.php"; ?>

<main class="home-page">

    <!-- Hero Section -->

    <section class="hero-section" id="hero">
        <div class="hero-glow hero-glow-1"></div>
        <div class="hero-glow hero-glow-2"></div>
        <div class="hero-particles" aria-hidden="true"></div>

        <div class="hero-content">
            <span class="hero-tag reveal-up" style="--d:0.1s">TIMELESS COLLECTION</span>

            <h1>
                <span class="line"><span class="line-inner" style="--d:0.25s">Time Is Your</span></span>
                <span class="line"><span class="line-inner gold-text" style="--d:0.45s">Greatest Luxury.</span></span>
            </h1>

            <p class="reveal-up" style="--d:0.75s">
                Discover our exclusive collection of premium watches.
                Crafted for those who value elegance and precision.
            </p>

            <div class="hero-actions reveal-up" style="--d:0.95s">
                <a href="products.php" class="btn btn-gold btn-shine">
                    Explore Collection
                </a>
                <a href="#categories" class="hero-link">
                    Shop By Category <span>↓</span>
                </a>
            </div>
        </div>

        <div class="hero-decoration" aria-hidden="true">
            <div class="watch-dial">
                <span class="ring ring-1"></span>
                <span class="ring ring-2"></span>
                <svg viewBox="0 0 200 200" class="dial-svg">
                    <defs>
    <linearGradient id="goldGradient" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#9e7623"/>
        <stop offset="50%" stop-color="#fff0b0"/>
        <stop offset="100%" stop-color="#d4af37"/>
    </linearGradient>
</defs>

<circle cx="100" cy="100" r="92" class="dial-border"/>
                    <g class="dial-ticks">
                        <?php for ($i = 0; $i < 60; $i++): ?>
                            <line
                                x1="100" y1="12"
                                x2="100" y2="<?= $i % 5 === 0 ? 24 : 18 ?>"
                                transform="rotate(<?= $i * 6 ?> 100 100)"
                                class="<?= $i % 5 === 0 ? 'tick-big' : 'tick' ?>"
                            />
                        <?php endfor; ?>
                    </g>
                    <line x1="100" y1="100" x2="100" y2="55" class="hand hand-hour"/>
                    <line x1="100" y1="100" x2="100" y2="35" class="hand hand-min"/>
                    <line x1="100" y1="112" x2="100" y2="28" class="hand hand-sec"/>
                    <circle cx="100" cy="100" r="5" class="dial-center"/>
                </svg>
            </div>
            <span>TIMELESS</span>
            <span>EST. 2026</span>
        </div>

        <a href="#marquee" class="scroll-indicator" aria-label="Scroll down"><i></i></a>
    </section>


    <!-- Marquee -->

    <div class="marquee" id="marquee" aria-hidden="true">
        <div class="marquee-track">
            <?php for ($i = 0; $i < 2; $i++): ?>
                <span>SWISS PRECISION</span><b>◆</b>
                <span>TIMELESS DESIGN</span><b>◆</b>
                <span>PREMIUM CRAFTSMANSHIP</span><b>◆</b>
                <span>LIMITED EDITIONS</span><b>◆</b>
                <span>WORLDWIDE SHIPPING</span><b>◆</b>
            <?php endfor; ?>
        </div>
    </div>


    <!-- Categories -->

    <section class="home-section" id="categories">

        <div class="section-heading reveal">
            <div>
                <span class="section-subtitle">EXPLORE</span>
                <h2>Shop By Category</h2>
            </div>

            <a href="products.php" class="view-all">
                View All <span>→</span>
            </a>
        </div>

        <div class="categories-grid">

            <?php if (!empty($categories)): ?>

                <?php foreach ($categories as $index => $cat): ?>

                    <a
                        href="products.php?category_id=<?= $cat['id'] ?>"
                        class="category-card reveal tilt"
                        style="--i:<?= $index ?>"
                    >

                        <?php if (!empty($cat['image'])): ?>

                            <img
                                src="../<?= htmlspecialchars($cat['image']) ?>"
                                alt="<?= htmlspecialchars($cat['name']) ?>"
                            >

                        <?php else: ?>

                            <div class="category-placeholder">
                                TIMELESS
                            </div>

                        <?php endif; ?>

                        <div class="category-overlay">
                            <h3>
                                <?= htmlspecialchars($cat['name']) ?>
                            </h3>

                            <span>Explore Collection →</span>
                        </div>

                    </a>

                <?php endforeach; ?>

            <?php else: ?>

                <p>No categories available.</p>

            <?php endif; ?>

        </div>

    </section>


    <!-- Features / Stats -->

    <section class="features-strip">
        <div class="features-inner">
            <div class="feature reveal" style="--i:0">
                <strong class="counter" data-target="<?= max(count($products ?? []), 1) ?>">0</strong>
                <span>Exclusive Watches</span>
            </div>
            <div class="feature reveal" style="--i:1">
                <strong class="counter" data-target="<?= max(count($categories ?? []), 1) ?>">0</strong>
                <span>Curated Collections</span>
            </div>
            <div class="feature reveal" style="--i:2">
                <strong class="counter" data-target="2">0</strong>
                <span>Years Warranty</span>
            </div>
            <div class="feature reveal" style="--i:3">
                <strong class="counter" data-target="100" data-suffix="%">0</strong>
                <span>Authentic Quality</span>
            </div>
        </div>
    </section>


    <!-- Latest Products -->

    <section class="home-section">

        <div class="section-heading reveal">
            <div>
                <span class="section-subtitle">OUR SELECTION</span>
                <h2>Latest Arrivals</h2>
            </div>

            <a href="products.php" class="view-all">
                View All Watches <span>→</span>
            </a>
        </div>

        <div class="products-grid">

            <?php if (!empty($products)): ?>

                <?php foreach (array_slice($products, 0, 8) as $index => $item): ?>

                    <div class="product-card reveal" style="--i:<?= $index % 4 ?>">

                        <a
                            href="productDetails.php?id=<?= $item['id'] ?>"
                            class="product-image"
                        >

                            <?php if (!empty($item['image'])): ?>

                                <img
                                    src="../<?= htmlspecialchars($item['image']) ?>"
                                    alt="<?= htmlspecialchars($item['name']) ?>"
                                    loading="lazy"
                                >

                            <?php else: ?>

                                <div class="product-placeholder">
                                    NO IMAGE
                                </div>

                            <?php endif; ?>

                            <span class="product-label">
                                NEW ARRIVAL
                            </span>

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
                                <?= number_format($item['price'], 2) ?> JOD
                                </span>

                                <a
                                    href="productDetails.php?id=<?= $item['id'] ?>"
                                    class="product-view"
                                >
                                    View Details <span>→</span>
                                </a>

                            </div>

                            <span class="<?= $item['stock'] > 0 ? 'stock-available' : 'stock-unavailable' ?>">
                                <?= $item['stock'] > 0 ? '● In Stock' : '● Out of Stock' ?>
                            </span>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <p>No products available.</p>

            <?php endif; ?>

        </div>

    </section>

    <!-- Brand Message -->

    <section class="brand-message">
        <div class="brand-glow" aria-hidden="true"></div>
        <span class="reveal">TIMELESS QUALITY</span>
        <h2 class="reveal" style="--i:1">Elegance Never Goes Out Of Style.</h2>
        <a href="products.php" class="btn btn-gold btn-shine reveal" style="--i:2">
            Discover Our Watches
        </a>
    </section>

</main>

<script src="../assets/js/home.js" defer></script>

<?php require_once __DIR__ . "/includes/footer.php"; ?>