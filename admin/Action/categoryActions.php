<?php
session_start();

require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../../classes/Category.php";
require_once __DIR__ . "/../../classes/imageUploader.php";

$database = new Database();
$db = $database->connect();

$category = new Category($db);
$uploader = new ImageUploader();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    if ($action === 'add') {

        $name = trim($_POST['name']);
        $description = trim($_POST['description']);

        if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
            $_SESSION['message'] = "Please select a category image";
            $_SESSION['message_type'] = "error";
            header("Location: ../categories.php");
            exit;
        }

        $image = $uploader->upload($_FILES['image'], "categories");

        if (!str_starts_with($image, "uploads/")) {
            $_SESSION['message'] = $image;
            $_SESSION['message_type'] = "error";
            header("Location: ../categories.php");
            exit;
        }

        if ($category->addNewCategory($name, $description, $image)) {
            $_SESSION['message'] = "Category added successfully";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Failed to add category";
            $_SESSION['message_type'] = "error";
        }

        header("Location: ../categories.php");
        exit;
    }

    if ($action === 'edit') {

        $id = $_POST['id'];

        $oldCategory = $category->getCategoryByID($id);

        if (!$oldCategory) {
            $_SESSION['message'] = "Category not found";
            $_SESSION['message_type'] = "error";
            header("Location: ../categories.php");
            exit;
        }

        $name = trim($_POST['name']);
        $description = trim($_POST['description']);

        $image = $oldCategory['image'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {

            $newImage = $uploader->upload($_FILES['image'], "categories");

            if (!str_starts_with($newImage, "uploads/")) {
                $_SESSION['message'] = $newImage;
                $_SESSION['message_type'] = "error";
                header("Location: ../categories.php?edit=" . $id . "#editCategoryForm");
                exit;
            }

            $image = $newImage;
        }

        if ($category->updateCategory($id, $name, $description, $image)) {
            $_SESSION['message'] = "Category updated successfully";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Failed to update category";
            $_SESSION['message_type'] = "error";
        }

        header("Location: ../categories.php");
        exit;
    }

    if ($action === 'delete') {

        $id = $_POST['id'];

        if ($category->deleteCategory($id)) {
            $_SESSION['message'] = "Category deleted successfully";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Failed to delete category";
            $_SESSION['message_type'] = "error";
        }

        header("Location: ../categories.php");
        exit;
    }
}

header("Location: ../categories.php");
exit;