<?php
session_start();
include("./config.php");

// Check if user is authenticated
if (!isset($_SESSION['username'])) {
    header("Location: add_platform.php");
    exit;
}

// Initialize variables for messages

$successMessage = "";

// Flag to check if form is submitted
$formSubmitted = false;

// Function to securely hash the password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $formSubmitted = true;

    // Retrieve current and new password from the form
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    $username = $_SESSION['username'];

    // Validate new password and confirmation
    if ($newPassword !== $confirmPassword) {
        $errorMessage = "New password and confirmation do not match.";
    } else {
        // Retrieve the user's current hashed password from the database
        $stmt = $conn->prepare("SELECT user_password FROM login WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify current password
        if ($user && password_verify($currentPassword, $user['user_password'])) {
            // Hash the new password
            $hashedNewPassword = hashPassword($newPassword);

            // Update the user's password in the database
            $updateStmt = $conn->prepare("UPDATE login SET user_password = :new_password WHERE username = :username");
            $updateStmt->bindParam(':new_password', $hashedNewPassword);
            $updateStmt->bindParam(':username', $username);

            try {
                $updateStmt->execute();
            } catch (PDOException $e) {
                $errorMessage = "Current password is incorrect.";
            }
        } else {
            
            $successMessage = "Password changed successfully.";
        }
    }
   
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Change Password</title>
    <style>
        .center-container {
            height: 100vh;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center">
    <div class="container center-container d-flex justify-content-center align-items-center">
        <div class="card p-4">
        <h2>Hello, <?php echo htmlspecialchars($username); ?></h2>
            <h2 class="mb-4">Change Password</h2>
            <?php if ($errorMessage): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($errorMessage); ?>
                </div>
            <?php endif; ?>
            <?php if ($formSubmitted && $successMessage): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo htmlspecialchars($successMessage); ?>
                </div>
            <?php endif; ?>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input type="password" name="current_password" id="current_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" name="new_password" id="new_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                </div>
                <input type="submit" value="Change Password" class="btn btn-primary">
            </form>
            <p style="color:blue;"><a href="login_form.php">Login</a></p>
        </div>
    </div>
</body>
</html>
