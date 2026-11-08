
<?php

session_start();

require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../../classes/Auth.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.php");
    exit;
}

$action = $_POST['action'] ?? '';
$database = new Database();
$db = $database->connect();
$auth = new Auth($db);
$message = "";
$type = "error";
$redirect = "../login.php";
switch ($action) {
    case "register":

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $redirect = "../register.php";
        if (
            $name === '' ||
            $email === '' ||
            $password === '' ||
            $phone === '' ||
            $address === ''
        ) {
            $message = "Please fill in all fields";

        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Please enter a valid email address";

        } elseif (strlen($password) < 6) {
            $message = "Password must be at least 6 characters";

        } else {
            $result = $auth->Register(
                $name,
                $email,
                $password,
                $phone,
                $address
            );
            if ($result) {
                $message = "Account created successfully. Please login.";
                $type = "success";
                $redirect = "../login.php";
            } else {
                $message = "Email already exists or registration failed";
            }
        }
        break;

   case "login":
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $redirect = "../login.php";

    if (empty($email) || empty($password)) {

        $message = "Please enter email and password.";

    } else {

        $result = $auth->Login($email, $password);

        if ($result) {

            $_SESSION['toast'] = [
                'message' => 'Welcome back!',
                'type' => 'success'
            ];

            if ($_SESSION['user_role'] === 'admin') {

                header("Location: ../../admin/Dashboard.php");
                exit;

            } else {

                header("Location: ../home.php");
                exit;

            }
        } else {

            $message = $result['message'] ?? "Invalid email or password.";
        }
    }
    break;

    case "logout":

        $auth->Logout();

    default:

        $message = "Invalid action";
        break;
}

$_SESSION['toast'] = [
    'message' => $message,
    'type' => $type
];

header("Location: " . $redirect);
exit;
