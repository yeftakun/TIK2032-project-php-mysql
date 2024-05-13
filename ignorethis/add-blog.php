<?php
// Include connection file
include('../connection.php');

// Initialize variables
$title = $author = $imagePath = $sourceLink = $category = $content = '';
$publishDate = date('Y-m-d'); // Current date

// Function to upload image
function uploadImage() {
    $targetDir = "../assets/img/";
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            return basename( $_FILES["image"]["name"]);
        } else {
            echo "Sorry, there was an error uploading your file.";
            return '';
        }
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from form
    $title = $_POST['title'];
    $author = $_POST['author'];
    $sourceLink = $_POST['source_link'];
    $category = $_POST['category'];
    $content = $_POST['content'];

    // Upload image
    $imagePath = uploadImage();

    // Insert data into database
    $query = "INSERT INTO posts (title, author, publish_date, image_path, source_link, category_id, content) 
              VALUES (:title, :author, :publish_date, :image_path, :source_link, :category, :content)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':author', $author);
    $stmt->bindParam(':publish_date', $publishDate);
    $stmt->bindParam(':image_path', $imagePath);
    $stmt->bindParam(':source_link', $sourceLink);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':content', $content);

    if ($stmt->execute()) {
        // Redirect to blog CRUD page after successful insertion
        header("Location: blog-crud.php");
        exit();
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Post</title>
    <!-- Your CSS styles -->
    <link rel="icon" href="assets/logo/miaw.ico">
</head>
<body>
    <h1>Add New Post</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required><br><br>
        
        <label for="author">Author:</label><br>
        <input type="text" id="author" name="author" required><br><br>
        
        <label for="image">Upload Image:</label><br>
        <input type="file" id="image" name="image" accept="image/*" required><br><br>
        
        <label for="source_link">Source Link:</label><br>
        <input type="text" id="source_link" name="source_link" required><br><br>
        
        <label for="category">Category:</label><br>
        <select id="category" name="category" required>
            <option value="">Select Category</option>
            <?php
            // Fetch categories from the database
            $query = "SELECT * FROM categories";
            $stmt = $conn->query($query);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="' . $row['category_id'] . '">' . $row['name'] . '</option>';
            }
            ?>
        </select><br><br>
        
        <label for="content">Content:</label><br>
        <textarea id="content" name="content" rows="5" required></textarea><br><br>
        
        <input type="submit" value="Post">
    </form>
</body>
</html>
