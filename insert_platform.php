<?php
//checking error
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//start
session_start();
include("./config.php");
include './platform_password.php';
if (!isset($_SESSION["username"]) || empty($_SESSION["username"])) {
    header("Location: login_form.php");
}
$username = $_SESSION["username"];
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $plateform = $_POST['plateform'];
    $password = $_POST['password'];
     // register date of platform
    $register_date = date('Y-m-d H:i:s');

    // Create Password Plateforms object
    $passwordPlateform = new PasswordPlatforms($conn);

    // Attempt to store password
    if ($passwordPlateform->savePasswordRecord($username, $plateform,$password,$register_date)) {
        // Password Stored Successfully, redirect to success page
        header("Location: add_platform.php?msgtype=success&message=Password Stored Successfully");
        exit;
    } else {
        // Password stored failed redirect with error
        header("Location: add_platform.php?msgtype=error&message=Oops Something went wrong");
    }
}

