
<?php

header("Content-Type: application/json");

require_once "../config/Database.php";
require_once "../classes/Order.php";

$database = new Database();
$db = $database->connect();

$order = new Order($db);

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "POST") {

    $data = json_decode(file_get_contents("php://input"), true);

    $user_id = $data['user_id'] ?? null;
    $delivery_address = $data['delivery_address'] ?? null;
    $phone = $data['phone'] ?? null;

    if (!$user_id || !$delivery_address || !$phone) {

        echo json_encode([
            "success" => false,
            "message" => "User ID, address and phone are required"
        ]);

        exit;
    }

    $result = $order->newOrder(
        $user_id,
        $delivery_address,
        $phone
    );

    if (is_array($result) && $result['success'] === true) {

        echo json_encode([
            "success" => true,
            "message" => "Order created successfully",
            "data" => $result
        ]);

    } else {

        echo json_encode([
            "success" => false,
            "message" => $result
        ]);
    }

} else {

    echo json_encode([
        "success" => false,
        "message" => "Method not allowed"
    ]);
}

?>
