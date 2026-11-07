<?php

header("Content-Type: application/json");

session_start();

require_once "../config/Database.php";
require_once "../classes/Auth.php";

$database = new Database();
$db = $database->connect();

$auth = new Auth($db);

$method = $_SERVER['REQUEST_METHOD'];

$action = $_GET['action'] ?? '';
switch ($action) {

  
    case 'Register':
      $input = json_decode(file_get_contents("php://input"), true);  
         if (empty($input['name']) ||empty($input['email']) ||empty($input['password']))
            { http_response_code(400);echo json_encode(["success" => false,"message" => "Name, Email and Password are Required"]);
            
         break;}
             $result = $auth->register($input['name'],$input['email'],$input['password'],
            $input['phone'] ?? null,$input['address'] ?? null);
              echo json_encode(["success" => $result,"message" => $result? "Registration Successful": "Email Already Exists"]);
               
              break;

     case 'Login':
         $input = json_decode(file_get_contents("php://input"), true);

       if (empty($input['email']) || empty($input['password'])) {
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "message" => "Email and Password are Required"]);
            break;
        }

        $result = $auth->login(
            $input['email'],
            $input['password']);

        echo json_encode($result);

        break;
     case 'Profile':
         $data = $auth->profile();

        if ($data) {echo json_encode(["success" => true,"data" => $data]);
        } else {
            http_response_code(401);
            echo json_encode(["success" => false,"message" => "Please Login First"]);
        }
        break;
 
        case 'Logout':
           $auth->logout();
          echo json_encode(["success" => true,"message" => "Logged Out Successfully"]);
           break;



  default:

        http_response_code(404);
        echo json_encode(["success" => false,"message" => "Invalid Action"]);
        break;

        }



?>