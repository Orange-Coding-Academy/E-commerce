<?php

session_start();

require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../../classes/Order.php";

$database = new Database();
$db = $database->connect();

$order = new Order($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);
    $action = $_POST['action'] ?? '';

    if (!$id || !$order-> getOrderDetailsForAdmin($id)) {
        $_SESSION['message'] = "Order not found";
        $_SESSION['message_type'] = "error";
        header("Location: ../orders.php");
        exit;
    }

    if ($action === 'update_order_status') {

        $status = $_POST['order_status'] ?? '';

        $result = $order->updateOrderStatus($id, $status);

        if ($result === true) {
            $_SESSION['message'] = "Order status updated successfully";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Invalid order status";
            $_SESSION['message_type'] = "error";
        }

    } elseif ($action === 'update_payment_status') {

        $status = $_POST['payment_status'] ?? '';

        $result = $order->updatePaymentStatus($id, $status);

        if ($result === true) {
            $_SESSION['message'] = "Payment status updated successfully";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Invalid payment status";
            $_SESSION['message_type'] = "error";
        }

    } else {
        $_SESSION['message'] = "Invalid action";
        $_SESSION['message_type'] = "error";
    }

    header("Location: ../order.php?view=" . $id);
    exit;
}

header("Location: ../order.php");
exit;