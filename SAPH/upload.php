<?php
session_start();

// Define the folder where uploaded images will be stored
$uploadDir = "includes/assets/images/"; // corrected path

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fileName = basename($_FILES["productImage"]["name"]);
    $targetFile = $uploadDir . $fileName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedTypes = ["jpg", "jpeg", "png", "gif"];

    if (in_array($imageFileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFile)) {
            echo "<div class='upload-container'><p style='color:green;'>Image uploaded successfully: $fileName</p></div>";
        } else {
            echo "<div class='upload-container'><p style='color:red;'>Error uploading file.</p></div>";
        }
    } else {
        echo "<div class='upload-container'><p style='color:red;'>Only JPG, JPEG, PNG & GIF files allowed.</p></div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Product Image - Pastimes</title>
    <link rel="stylesheet" href="includes/assets/css/style.css">
</head>
<body>
<?php include 'includes/navbar.php'; ?>

<div class="upload-container">
    <h2>Upload Product Image</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Select Image:</label>
        <input type="file" name="productImage" required>
        <input type="submit" value="Upload">
    </form>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
