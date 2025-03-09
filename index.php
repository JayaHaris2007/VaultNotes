<?php
include 'db.php';
session_start();

$logged_in = isset($_SESSION['user_id']);
$result = $conn->query("SELECT * FROM notes ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Noteflow</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>📖 Noteflow</h1>

        <div class="nav">
            <?php if ($logged_in): ?>
                <a href="upload.php" class="btn">Upload Note</a>
                <a href="logout.php" class="btn logout">Logout</a>
            <?php else: ?>
                <a href="signup.php" class="btn">Sign Up</a>
                <a href="login.php" class="btn">Login</a>
            <?php endif; ?>
        </div>

        <h2>Uploaded Notes</h2>
        <div class="notes-list">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="note-item">
                    <a href="uploads/<?= $row['filename'] ?>" target="_blank" class="note-btn"><?= $row['title'] ?></a>
                    <p class="uploader">Uploaded by: <b><?= htmlspecialchars($row['uploader']) ?></b></p>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
