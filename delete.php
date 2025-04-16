<?php
session_start();
if (!isset($_SESSION["username"]) || empty($_SESSION["username"])) {
    header("Location: login_form.php");
}
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header("Location: add_platform.php?msgtype=warning&message=Invalid Password Id!");
}
$username = $_SESSION["username"];
$id = null;
if (isset($_GET["id"]) && !empty($_GET["id"])) {
    $id = $_GET["id"];
}
//
include './config.php';
include './platform_password.php';

// Create Password Plateforms object
$passwordPlateform = new PasswordPlatforms($conn);

$password = $passwordPlateform->deletePasswordRecord($id);
if (empty($password)) {
    header("Location: add_platform.php?msgtype=error&message=Password not found!");
}

if($password){
    header("Location: add_platform.php?msgtype=success&message=Password deleted successfully!");
}

