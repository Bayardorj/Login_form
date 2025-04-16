<?php
// Database connection details
session_start();
include("./config.php");

// Function to securely hash the password
function hashPassword($user_password) {
    return password_hash($user_password, PASSWORD_DEFAULT);
}

// Variable to hold the success message
$successMessage = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from the form
    $username = $_POST['username'];
    $user_password = $_POST['password'];

    // Hash the password
    $hashedPassword = hashPassword($user_password);
    // register date of registration
    $registerDate = date('Y-m-d H:i:s');

    // Insert username and hashed password into the database
    $stmt = $conn->prepare("INSERT INTO user (username, user_password, register_date) VALUES (:username, :user_password, :register_date)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':user_password', $hashedPassword);
    $stmt->bindParam(':register_date', $registerDate);
    try {
        $stmt->execute();
        $_SESSION['username'] = $username; // Set session variable
        $_SESSION['successMessage'] = "User created successfully";
        header("Location: login_form.php");
        exit;
    } catch(PDOException $e) {
        $successMessage = "Error: " . $e->getMessage();
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
    <title>User Registration</title>
    <style>
        .center-container {
            height: 100vh;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center">
    <div class="container center-container d-flex justify-content-center align-items-center">
        <div class="card p-4">
            <h2 class="mb-4">User Registration</h2>
            <?php if ($successMessage): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $successMessage; ?>
                </div>
            <?php endif; ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <input type="submit" value="Register" class="btn btn-primary">
                
            </form>
            <p style="color:blue;">If you have an account <a href="login_form.php">Login</a></p>
       
        </div>
    </div>
</body>
</html>