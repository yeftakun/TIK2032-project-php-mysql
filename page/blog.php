<?php
session_start();

// Include connection file
include('../connection.php');

// Function to increment visitor count if not already counted in the current session
function incrementVisitorCount() {
    global $conn;
    
    // Check if visitor has not been counted in the current session
    if (!isset($_SESSION['visitor_counted'])) {
        $query = "UPDATE visitor_count SET count = count + 1 WHERE id = 1"; // Assuming the count is stored in the row with ID 1
        $stmt = $conn->prepare($query);
        $stmt->execute();

        // Mark visitor as counted in the current session
        $_SESSION['visitor_counted'] = true;
    }
}

// Function to retrieve visitor count
function getVisitorCount() {
    global $conn;
    $query = "SELECT count FROM visitor_count WHERE id = 1"; // Assuming the count is stored in the row with ID 1
    $stmt = $conn->query($query);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['count'];
}

// Increment visitor count
incrementVisitorCount();

// Get visitor count
$visitorCount = getVisitorCount();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/blog.css">
    <link rel="icon" href="../assets/logo/miaw.ico">
</head>

<body>
    
    <header class="header">
        <a href="#" class="logo">Personal Homepage.</a>

        <nav class="navbar">
            <a href="../index.html" style="--i:1">Home</a>
            <a href="./gallery.html" style="--i:2">Gallery</a>
            <a href="./blog.php" class="active" style="--i:3">Blog</a>
            <div class="dropdown" id="contactDropdown">
                <a href="#" class="contact" style="--i:4">Contact</a>
                <div class="dropdown-content">
                    <a href="./contact-list/contact-email.html">E-mail</a>
                    <a href="./contact-list/contact-wa.html">WhatsApp</a>
                </div>
            </div>
        </nav> 
    </header>
    
    <div class="container">
        <div class="header-section">
            <img src="../assets/img/foto1.jpg">
            <h1>Yefta's Blog</h1>
            <p>This is my Blog</p>
            <p>Total Pengunjung: <?php echo $visitorCount; ?></p> <!-- Display visitor count -->
        </div>
        
        <!-- Add dropdown menu for selecting categories -->
        <form method="GET" action="blog.php">
            <label for="category">Select Category </label>
            <select name="category" id="category">
                <option value="All">All</option>
                <?php
                // Include connection file
                include('../connection.php');

                // Query to fetch categories from the database
                $query = "SELECT * FROM categories";
                $stmt = $conn->query($query);

                // Get the selected category from the URL, if exists
                $selectedCategory = isset($_GET['category']) ? $_GET['category'] : 'All';

                // Loop through the fetched categories and display them as options in the dropdown
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // Check if the current category matches the selected category
                    $isActive = ($row['category_id'] == $selectedCategory) ? 'selected="selected"' : '';

                    echo '<option value="' . $row['category_id'] . '" ' . $isActive . '>' . $row['name'] . '</option>';
                }
                ?>
            </select>
        </form>
        <ul class="blog-list">
            <?php
            // Check if category is selected from the dropdown
            if (isset($_GET['category'])) {
                $category_id = $_GET['category'];
                // If 'All' is selected, fetch all posts
                if ($category_id == 'All') {
                    $query = "SELECT * FROM posts";
                } else {
                    // Otherwise, fetch posts based on selected category
                    $query = "SELECT * FROM posts WHERE category_id = $category_id";
                }
            } else {
                // If no category is selected, fetch all posts
                $query = "SELECT * FROM posts";
            }

            // Query to fetch blog posts from the database based on selected category
            $stmt = $conn->query($query);

            // Loop through the fetched blog posts and display them
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<li class="blog-item"><a href="./blog-list/blogcontent.php?id=' . $row['post_id'] . '">';
                echo '<img src="../assets/img/' . $row['image_path'] . '" alt="' . $row['title'] . '">';
                echo '<p class="blog-item-title">' . $row['title'] . '</p>';
                echo '</a></li>';
            }
            ?>
        </ul>
          <!-- Tambahkan lebih banyak div.gallery-item di sini sesuai dengan jumlah foto yang kamu miliki -->
      </div>
    </div>
    <script src="./script/script.js"></script>
    <script>
        // Get the select element
        var categorySelect = document.getElementById('category');

        // Add event listener to detect changes in the dropdown selection
        categorySelect.addEventListener('change', function() {
            // Get the selected value
            var selectedCategory = categorySelect.value;

            // Construct the URL with the selected category value
            var url = 'blog.php?category=' + encodeURIComponent(selectedCategory);

            // Redirect to the constructed URL
            window.location.href = url;
        });
    </script>
</body>


</html>
