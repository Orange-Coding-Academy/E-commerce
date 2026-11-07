<?php

session_start();

require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../../classes/Users.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$isAdmin  = ($_SESSION['user_role'] ?? '') === 'admin';
$redirect = $isAdmin ? "../../admin/profile.php" : "../profile.php";

$database = new Database();
$db = $database->connect();
$user = new Users($db);

$id      = $_SESSION['user_id'];   
$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');

$type = "error";

if ($name === '' || $email === '') {
    $message = "Name and email are required";

} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = "Please enter a valid email address";

} else {

    try {
        $result = $user->updateUser($id, $name, $email, $phone, $address);
    } catch (PDOException $e) {
  
        $result = false;
    }

    if ($result) {
        $_SESSION['user_name']  = $name;
        $_SESSION['user_email'] = $email;
        $message = "Profile updated successfully";
        $type = "success";
    } else {
        $message = "Failed to update profile (email may already be used)";
    }
}


if ($isAdmin) {
    $_SESSION['message']      = $message;
    $_SESSION['message_type'] = $type;
} else {
    $_SESSION['toast'] = ['message' => $message, 'type' => $type];
}

header("Location: " . $redirect);
exit;
