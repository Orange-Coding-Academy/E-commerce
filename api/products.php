
<?php

header("Content-Type: application/json");

require_once "../config/Database.php";
require_once "../classes/Product.php";

$database = new Database();
$db = $database->connect();

$product = new Product($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    case 'GET':

        if (isset($_GET['id'])) {
            $data = $product->getProductByID($_GET['id']);
        } elseif (isset($_GET['search'])) {
            $data = $product->search($_GET['search']);
        } else {
            $data = $product->getAllProduct();
        }

        echo json_encode([
            "success" => true,
            "data" => $data
        ]);
        break;

        case 'POST':

        $input = json_decode(file_get_contents("php://input"), true);

        $result = $product->addNewProduct(
            $input['name'],
            $input['description'],
            $input['price'],
            $input['stock'],
            $input['category_id'],
            $input['image'] ?? null
        );

        echo json_encode([
            "success" => $result,
            "message" => $result ? "Product Added Successfully" : "Failed to Add Product"
        ]);
        break;

        case 'PUT':

        $input = json_decode(file_get_contents("php://input"), true);

        $result = $product->updateProduct(
            $input['id'],
            $input['name'],
            $input['description'],
            $input['price'],
            $input['stock'],
            $input['category_id'],
            $input['image'] ?? null
        );

        echo json_encode([
            "success" => $result,
            "message" => $result ? "Product Updated Successfully" : "Failed to Update Product"
        ]);
        break;
        case 'DELETE':

        $id = $_GET['id'];

        $result = $product->deleteProduct($id);

        echo json_encode([
            "success" => $result,
            "message" => $result ? "Product Deleted Successfully" : "Failed to Delete Product"
        ]);
        break;

         default:

        http_response_code(405);

        echo json_encode([
            "success" => false,
            "message" => "Method Not Allowed"
        ]);



}
