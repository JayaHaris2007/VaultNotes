<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Username already exists! Try a different one.";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            echo "<script>alert('Signup successful! Please login.'); window.location='login.php';</script>";
        } else {
            $error = "Signup failed! Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Signup - Noteflow</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>📝 Signup for Noteflow</h1>
        <div class="form-container">
            <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <form method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Sign Up</button>
            </form>
            <a href="login.php">Already have an account? Login</a>
        </div>
    </div>
</body>
</html>
