<?php

header("Content-Type: application/json");

require_once "../config/Database.php";
require_once "../classes/Cart.php";

$database = new Database();
$db = $database->connect();

$cart = new Cart($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "POST") {

    $data = json_decode(file_get_contents("php://input"), true);

    $user_id = $data['user_id'] ?? null;
    $product_id = $data['product_id'] ?? null;
    $quantity = $data['quantity'] ?? 1;

    if (!$user_id || !$product_id) {
        echo json_encode([
            "success" => false,
            "message" => "User ID and Product ID are required"
        ]);
        exit;
    }

    $result = $cart->addToCart(
        $user_id,
        $product_id,
        $quantity
    );

    if ($result === true) {
        echo json_encode([
            "success" => true,
            "message" => "Product added to cart successfully"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => is_string($result) ? $result : "Failed to add product"
        ]);
    }

} else {

    echo json_encode([
        "success" => false,
        "message" => "Method not allowed"
    ]);
}

if ($method == "GET") {

    $user_id = $_GET['user_id'] ?? null;

    if (!$user_id) {
        echo json_encode([
            "success" => false,
            "message" => "User ID is required"
        ]);
        exit;
    }

    $items = $cart->getCart($user_id);

    echo json_encode([
        "success" => true,
        "data" => $items
    ]);

}

elseif ($method == "PUT") {

    $data = json_decode(file_get_contents("php://input"), true);

    $user_id = $data['user_id'] ?? null;
    $product_id = $data['product_id'] ?? null;
    $quantity = $data['quantity'] ?? null;

    if (!$user_id || !$product_id || $quantity === null) {
        echo json_encode([
            "success" => false,
            "message" => "User ID, Product ID and Quantity are required"
        ]);
        exit;
    }

    $result = $cart->updateQuantity(
        $user_id,
        $product_id,
        $quantity
    );

    echo json_encode([
        "success" => $result === true,
        "message" => $result === true
            ? "Quantity updated successfully"
            : $result
    ]);
}
elseif ($method == "DELETE") {

    $data = json_decode(file_get_contents("php://input"), true);

    $user_id = $data['user_id'] ?? null;
    $product_id = $data['product_id'] ?? null;

    if (!$user_id || !$product_id) {
        echo json_encode([
            "success" => false,
            "message" => "User ID and Product ID are required"
        ]);
        exit;
    }

    $result = $cart->deleteItem($user_id, $product_id);

    echo json_encode([
        "success" => $result,
        "message" => $result
            ? "Product removed from cart successfully"
            : "Product not found in cart"
    ]);
}

