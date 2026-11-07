
<?php
session_start();

require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../../classes/Users.php";

$database = new Database();
$db = $database->connect();

$user = new Users($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $action = $_POST['action'];

    if ($action == 'edit') {

        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        $result = $user->updateUser($id, $name, $email, $phone, $address);

        if ($result) {
            $_SESSION['message'] = "User updated successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Failed to update user!";
            $_SESSION['message_type'] = "error";
        }

        header("Location: ../users.php");
        exit;
    }

    if ($action == 'delete') {

        $id = $_POST['id'];

        $result = $user->deleteUser($id);

        if ($result) {
            $_SESSION['message'] = "User deleted successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Failed to delete user!";
            $_SESSION['message_type'] = "error";
        }

        header("Location: ../users.php");
        exit;
    }
}
