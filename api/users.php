<?php

header("Content-Type: application/json");

require_once "../config/Database.php";
require_once "../classes/Users.php";

$database = new Database();
$db = $database->connect();

$user = new Users($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

   
    case 'GET':

        if (isset($_GET['id'])) {
            $data = $user->getUserByID($_GET['id']);
        } else {
            $data = $user->getusers();
        }

        echo json_encode([
            "success" => true,
            "data" => $data
        ]);

        break;


  
    case 'PUT':

        $input = json_decode(file_get_contents("php://input"), true);

        $result = $user->updateUser(
            $input['id'],
            $input['name'],
            $input['email'],
            $input['phone'],
            $input['address']
        );

        echo json_encode([
            "success" => $result,
            "message" => $result
                ? "User Updated Successfully"
                : "Failed to Update User"
        ]);

        break;


    case 'DELETE':

        $id = $_GET['id'] ?? null;

        if (!$id) {
            http_response_code(400);

            echo json_encode([
                "success" => false,
                "message" => "User ID is Required"
            ]);

            break;
        }

        $result = $user->deleteUser($id);

        echo json_encode([
            "success" => $result,
            "message" => $result
                ? "User Deleted Successfully"
                : "Failed to Delete User"
        ]);

        break;


    default:

        http_response_code(405);

        echo json_encode([
            "success" => false,
            "message" => "Method Not Allowed"
        ]);

        break;
}

?>