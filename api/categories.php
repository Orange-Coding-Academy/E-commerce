<?php

header("Content-Type: application/json");

require_once "../config/Database.php";
require_once "../classes/Category.php";

$database = new Database();
$db = $database->connect();

$category = new Category($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

   
    case 'GET':

        if (isset($_GET['id'])) {
            $data = $category->getCategoryByID($_GET['id']);
        } else {
            $data = $category->getAllCategories();
        }

        echo json_encode([
            "success" => true,
            "data" => $data
        ]);

        break;


   
    case 'POST':

        $input = json_decode(file_get_contents("php://input"), true);

        $result = $category->addNewCategory(
            $input['name'],
            $input['description'] ?? null,
            $input['image'] ?? null
        );

        echo json_encode([
            "success" => $result,
            "message" => $result
                ? "Category Added Successfully"
                : "Failed to Add Category"
        ]);

        break;


  
    case 'PUT':

        $input = json_decode(file_get_contents("php://input"), true);

        $result = $category->updateCategory(
            $input['id'],
            $input['name'],
            $input['description'] ?? null,
            $input['image'] ?? null
        );

        echo json_encode([
            "success" => $result,
            "message" => $result
                ? "Category Updated Successfully"
                : "Failed to Update Category"
        ]);

        break;



    case 'DELETE':

        $id = $_GET['id'] ?? null;

        if (!$id) {
            http_response_code(400);

            echo json_encode([
                "success" => false,
                "message" => "Category ID is Required"
            ]);

            break;
        }

        $result = $category->deleteCategory($id);

        echo json_encode([
            "success" => $result,
            "message" => $result
                ? "Category Deleted Successfully"
                : "Failed to Delete Category"
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