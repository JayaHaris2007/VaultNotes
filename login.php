<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - Noteflow</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>🔑 Login to Noteflow</h1>
        <div class="form-container">
            <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <form method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            <a href="signup.php">Don't have an account? Sign up</a>
        </div>
    </div>
</body>
</html>
