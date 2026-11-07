<?php

session_start();

require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../../classes/Order.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../checkout.php");
    exit;
}

$database = new Database();
$db = $database->connect();

$order = new Order($db);

$user_id = $_SESSION['user_id'];

$action = $_POST['action'] ?? '';

if ($action === "place_order") {

    $delivery_address = trim($_POST['delivery_address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($delivery_address === '' || $phone === '') {

        $_SESSION['toast'] = [
            'message' => 'Please fill in all delivery information',
            'type' => 'error'
        ];

        header("Location: ../checkout.php");
        exit;
    }

    $result = $order->newOrder(
        $user_id,
        $delivery_address,
        $phone
    );

    if (is_array($result) && !empty($result['success'])) {

        $_SESSION['toast'] = [
            'message' => 'Your order has been placed successfully',
            'type' => 'success'
        ];

        header("Location: ../orderDetails.php?id=" . $result['order_id']);
        exit;

    } else {

        $_SESSION['toast'] = [
            'message' => is_string($result) ? $result : 'Failed to place order',
            'type' => 'error'
        ];

        header("Location: ../cart.php");
        exit;
    }
}

header("Location: ../checkout.php");
exit;