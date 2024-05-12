<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog</title>
    <link rel="stylesheet" href="../../styles/style.css">
    <link rel="stylesheet" href="../../styles/contact.css">
    <link rel="icon" href="../../assets/logo/miaw.ico">
    <link rel="stylesheet" href="../../styles/bloglist.css">
</head>
<body>
    <header class="header">
        <a href="#" class="logo">Personal Homepage.</a>

        <nav class="navbar">
            <a href="../../index.html" style="--i:1">Home</a>
            <a href="../gallery.html" style="--i:2">Gallery</a>
            <a href="../blog.php" class="active" style="--i:3">Blog</a>
            <div class="dropdown" id="contactDropdown">
                <a href="#" class="contact" style="--i:4">Contact</a>
                <div class="dropdown-content">
                    <a href="../contact-list/contact-email.html">E-mail</a>
                    <a href="../contact-list/contact-wa.html">WhatsApp</a>
                </div>
            </div>
        </nav> 
    </header>
    <div class="article">
        <!-- Add a button to go back to the blog list -->
        <div class="tombol-kembali">
            <a href="../blog.php">Kembali Ke Daftar Artikel</a>
        </div>
        <?php
        // Include connection file
        include('../../connection.php');

        // Check if post_id is provided in the URL
        if (isset($_GET['id'])) {
            $post_id = $_GET['id'];

            // Query to fetch the selected blog post from the database
            $query = "SELECT *, DATE_FORMAT(publish_date, '%d %M %Y') AS formatted_date FROM posts WHERE post_id = $post_id";
            $stmt = $conn->query($query);

            // Check if the blog post exists
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                // Display the title, image, and content of the selected blog post
                echo '<h1 class="judul">' . $row['title'] . '</h1>';
                echo '<p class="author-credit">oleh <b>' . $row['author'] . '</b> — ' . $row['formatted_date'] . '</p>';
                echo '<div class="img"><img src="../../assets/img/' . $row['image_path'] . '" alt="Gambar Artikel"></div>';
                echo '<div class="isi-artikel">' . $row['content'] . '</div>';
                echo '<div class="referensi"><p><a href="' . $row['source_link'] . '">Sumber</a></p></div>';
            } else {
                // If the blog post does not exist, display an error message
                echo '<p>Artikel tidak ditemukan.</p>';
            }
        } else {
            // If post_id is not provided in the URL, display an error message
            echo '<p>Post ID tidak ditemukan.</p>';
        }
        ?>
        <!-- Add a button to go back to the blog list -->
        <div class="tombol-kembali">
            <a href="../blog.php">Kembali Ke Daftar Artikel</a>
        </div>
    </div>
</body>
</html>