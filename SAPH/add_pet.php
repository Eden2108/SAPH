<?php
session_start();
include 'DBConn.php';
include 'includes/navbar.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $colour = $_POST['colour'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO Pet (Name, Age, Colour, AdoptionStatus) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $name, $age, $colour, $status);
    $stmt->execute();
    $stmt->close();

    // Redirect back to Manage Pets
    header("Location: pet_management.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add New Pet - Save-A-Pet Hub</title>
    <link rel="stylesheet" href="includes/assets/css/style.css">
    <style>
        form {
            max-width: 400px;
            margin: 20px auto;
            background: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            margin-top: 15px;
            padding: 8px 14px;
            background-color: slateblue;
            color: white;
            border: none;
            border-radius: 4px;
        }
        button:hover {
            background-color: plum;
        }
        .back-link {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: slateblue;
        }
        .back-link:hover {
            color: plum;
        }
    </style>
</head>
<body>
<main>
     <a href="pet_management.php" class="back-link">← Back to Manage Pets</a>
    <h2>Add New Pet</h2>
    <form method="POST" action="">
        <label>Pet Name:</label>
        <input type="text" name="name" required>

        <label>Age:</label>
        <input type="number" name="age" required>

        <label>Colour:</label>
        <input type="text" name="colour" required>

        <label>Status:</label>
        <select name="status" required>
            <option value="Available">Available</option>
            <option value="Adopted">Adopted</option>
        </select>

        <button type="submit">Save Pet</button>
    </form>
</main>
<?php include 'includes/footer.php'; ?>
</body>
</html>
