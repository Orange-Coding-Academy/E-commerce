
<?php
session_start();

require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../../classes/Product.php";
require_once __DIR__ . "/../../classes/imageUploader.php";

$database = new Database();
$db = $database->connect();

$product = new Product($db);
$uploader = new ImageUploader();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $action = $_POST['action'];

    if ($action == 'add') {

        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $category_id = $_POST['category_id'];

        $image = $uploader->upload($_FILES['image'], "products");

        if (str_starts_with($image, "uploads/")) {

            $result = $product->addNewProduct(
                $name,
                $description,
                $price,
                $stock,
                $category_id,
                $image
            );

            if ($result) {
                $_SESSION['message'] = "Product added successfully!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Failed to add product!";
                $_SESSION['message_type'] = "error";
            }

        } else {
            $_SESSION['message'] = $image;
            $_SESSION['message_type'] = "error";
        }

        header("Location: ../products.php");
        exit;
    }

    if ($action == 'edit') {

        $id = $_POST['id'];

        $oldProduct = $product->getProductByID($id);

        if (!$oldProduct) {
            $_SESSION['message'] = "Product not found!";
            $_SESSION['message_type'] = "error";

            header("Location: ../products.php");
            exit;
        }

        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $category_id = $_POST['category_id'];

        $image = $oldProduct['image'];

        if (isset($_FILES['image']) &&
            $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {

            $newImage = $uploader->upload(
                $_FILES['image'],
                "products"
            );

            if (str_starts_with($newImage, "uploads/")) {
                $image = $newImage;
            } else {
                $_SESSION['message'] = $newImage;
                $_SESSION['message_type'] = "error";

                header("Location: ../products.php");
                exit;
            }
        }

        $result = $product->updateProduct(
            $id,
            $name,
            $description,
            $price,
            $stock,
            $category_id,
            $image
        );

        if ($result) {
            $_SESSION['message'] = "Product updated successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Failed to update product!";
            $_SESSION['message_type'] = "error";
        }

        header("Location: ../products.php");
        exit;
    }

    if ($action == 'delete') {

        $id = $_POST['id'];

        $result = $product->deleteProduct($id);

        if ($result) {
            $_SESSION['message'] = "Product deleted successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Failed to delete product!";
            $_SESSION['message_type'] = "error";
        }

        header("Location: ../products.php");
        exit;
    }
}
