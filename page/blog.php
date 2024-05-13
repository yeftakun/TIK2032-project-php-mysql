<?php
session_start();

include('../connection.php');

// Tambah jumlah pengunjung jika belum dihitung dalam sesi.
function incrementVisitorCount() {
    global $conn;
    // Cek pengunjung
    if (!isset($_SESSION['visitor_counted'])) {
        $query = "UPDATE visitor_count SET count = count + 1 WHERE id = 1"; // Assuming the count is stored in the row with ID 1
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $_SESSION['visitor_counted'] = true; // Tandai pengunjung
    }
}

// Ambil nilai jumlah pengunjung
function getVisitorCount() {
    global $conn;
    $query = "SELECT count FROM visitor_count WHERE id = 1";
    $stmt = $conn->query($query);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['count'];
}

incrementVisitorCount();
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
            <p>Total Pengunjung: <?php echo $visitorCount; ?></p> <!-- Tampilkan pengunjung -->
        </div>
        
        <!-- Dropdown menu untuk kategori -->
        <form method="GET" action="blog.php">
            <label for="category">Select Category </label>
            <select name="category" id="category">
                <option value="All">All</option>
                
                <?php
                include('../connection.php');
                $query = "SELECT * FROM categories"; // Ambil kategori dari database
                $stmt = $conn->query($query);

                // Kategori yang dipilih
                $selectedCategory = isset($_GET['category']) ? $_GET['category'] : 'All';

                // Perulangan untuk menampilkan kategori di dropdown
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $isActive = ($row['category_id'] == $selectedCategory) ? 'selected="selected"' : '';
                    echo '<option value="' . $row['category_id'] . '" ' . $isActive . '>' . $row['name'] . '</option>';
                }
                ?>
            </select>
        </form>
        <ul class="blog-list">
            <?php
            // Kategori terpilih dari dropdown
            if (isset($_GET['category'])) {
                $category_id = $_GET['category'];
                if ($category_id == 'All') { // Pilihan kategori All
                    $query = "SELECT * FROM posts";
                } else {
                    // Ambil artikel berdasarkan kategori
                    $query = "SELECT * FROM posts WHERE category_id = $category_id";
                }
            } else {
                // Jika tidak ada yg dipilih
                $query = "SELECT * FROM posts";
            }

            // Query untuk mengambil artikel sesuai dengan kategori
            $stmt = $conn->query($query);

            // Perulangan untuk menampilkan artikel
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<li class="blog-item"><a href="./blog-list/blogcontent.php?id=' . $row['post_id'] . '">';
                echo '<img src="../assets/img/' . $row['image_path'] . '" alt="' . $row['title'] . '">';
                echo '<p class="blog-item-title">' . $row['title'] . '</p>';
                echo '</a></li>';
            }
            ?>
        </ul>
      </div>
    </div>
    <script src="./script/script.js"></script>
    <script>
        // Mdapatkan elemen dropdown kategori
        var categorySelect = document.getElementById('category');
        // Menambahkan event listener ketika nilai dropdown berubah
        categorySelect.addEventListener('change', function() {
            // Kategori yang dipilih
            var selectedCategory = categorySelect.value;
            // Membuat URL dengan kategori terpilih
            var url = 'blog.php?category=' + encodeURIComponent(selectedCategory);
            // Mengarahkan ke URL baru
            window.location.href = url;
        });
    </script>
</body>


</html>
