<?php

session_start();

require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../../classes/Cart.php";

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../products.php");
    exit;
}

$database = new Database();
$db = $database->connect();

$cart = new Cart($db);

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';
$product_id = $_POST['product_id'] ?? null;

$message = "";
$type = "error";

if (!$product_id || !filter_var($product_id, FILTER_VALIDATE_INT)) {
    $message = "Invalid product";
} else {

    switch ($action) {

        // Add product
        case "add":

            $quantity = filter_var(
                $_POST['quantity'] ?? 1,
                FILTER_VALIDATE_INT
            );

            if ($quantity === false || $quantity < 1) {
                $message = "Invalid quantity";
                break;
            }

            $result = $cart->addToCart(
                $user_id,
                $product_id,
                $quantity
            );

            if ($result === true) {
                $message = "Product added to cart successfully";
                $type = "success";
            } else {
                $message = is_string($result)
                    ? $result
                    : "Failed to add product";
            }

            break;


        // Update quantity
        case "update":

            $quantity = filter_var(
                $_POST['quantity'] ?? null,
                FILTER_VALIDATE_INT
            );

            if ($quantity === false || $quantity < 1) {
                $message = "Invalid quantity";
                break;
            }

            $result = $cart->updateCart(
                $user_id,
                $product_id,
                $quantity
            );

            if ($result === true) {
                $message = "Cart updated successfully";
                $type = "success";
            } else {
                $message = is_string($result)
                    ? $result
                    : "Failed to update cart";
            }

            break;


        // Delete item
        case "delete":

            $result = $cart->deleteItem(
                $user_id,
                $product_id
            );

            if ($result) {
                $message = "Product removed from cart";
                $type = "success";
            } else {
                $message = "Product not found in cart";
            }

            break;


        default:
            $message = "Invalid action";
            break;
    }
}

$_SESSION['toast'] = [
    'message' => $message,
    'type' => $type
];

// Redirect
if ($action === "add" && $product_id) {
    header("Location: ../productDetails.php?id=" . $product_id);
} else {
    header("Location: ../cart.php");
}

exit;