<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uploader = $_SESSION['username'];
    $note_text = $_POST['note_text'];
    $filename = $_FILES["file"]["name"] ?? null;
    $file_tmp = $_FILES["file"]["tmp_name"] ?? null;
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($filename);

    // Create uploads folder if not exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // If a file is uploaded, move it to the uploads folder
    if (!empty($filename) && move_uploaded_file($file_tmp, $target_file)) {
        $stmt = $conn->prepare("INSERT INTO notes (filename, description, uploader) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $filename, $note_text, $uploader);
    } elseif (!empty($note_text)) {
        // If only text is provided, store it without a file
        $stmt = $conn->prepare("INSERT INTO notes (filename, description, uploader) VALUES (NULL, ?, ?)");
        $stmt->bind_param("ss", $note_text, $uploader);
    } else {
        echo "<script>alert('Please upload a file or enter a note.'); window.location='upload.php';</script>";
        exit();
    }

    if ($stmt->execute()) {
        echo "<script>alert('Note uploaded successfully!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Failed to save note.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Upload Note - Noteflow</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>📤 Upload or Type a Note</h1>
        <div class="form-container">
            <form method="post" enctype="multipart/form-data">
                <textarea name="note_text" placeholder="Type your note here..." rows="4"></textarea>
                <input type="file" name="file">
                <button type="submit">Upload Note</button>
            </form>
            <a href="index.php">Back to Home</a>
        </div>
    </div>
</body>
</html>
