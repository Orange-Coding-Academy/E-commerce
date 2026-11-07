<?php

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../classes/Auth.php";

$db = new Database();
$conn = $db->connect();
$auth = new Auth($conn);
if (!$auth->isAdmin()) {
    header("Location: ../login.php");
    exit;
}