<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog CRUD</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        h1 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #efefef;
            color: black;
        }

        tr:hover {
            background-color: #00EEFF;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }

        button {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            background-color: #dc3545;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #c82333;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 20% auto;
            padding: 20px;
            border-radius: 5px;
            width: 50%;
            text-align: center;
            color: black;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="icon" href="../assets/logo/miaw.ico">
</head>

<body>
    
    <header class="header">
        <a href="#" class="logo">Blog CRUD.</a>

        <nav class="navbar">
            <a href="../index.html" style="--i:1">Home</a>
        </nav>
    </header>

    <br>
    <br>
    <br>
    <br>
    <br>

    <a href="add-blog.php" style="margin-bottom: 20px; display: block;">Add New Post</a>

    <table>
        <thead>
            <tr>
                <th>Post ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Publish Date</th>
                <th>Category</th>
                <th>Image</th>
                <th>Source Link</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Include connection file
            include('../connection.php');

            // Query to fetch posts from the database
            $query = "SELECT posts.*, categories.name AS category_name FROM posts 
            LEFT JOIN categories ON posts.category_id = categories.category_id";
            $stmt = $conn->query($query);

            // Loop through the fetched posts and display them in table rows
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . $row['post_id'] . '</td>';
                echo '<td>' . $row['title'] . '</td>';
                echo '<td>' . $row['author'] . '</td>';
                echo '<td>' . $row['publish_date'] . '</td>';
                echo '<td>' . $row['category_name'] . '</td>';
                echo '<td>' . $row['image_path'] . '</td>';
                echo '<td>' . $row['source_link'] . '</td>';
                // Add edit and delete buttons with appropriate links and attributes
                echo '<td>';
                echo '<a href="edit-blog.php?id=' . $row['post_id'] . '">Edit</a>';
                echo '<button onclick="deletePost(' . $row['post_id'] . ')">Delete</button>';
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>

    <!-- Modal for delete confirmation -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Are you sure you want to delete this post?</p>
            <button id="confirmDelete">Delete</button>
        </div>
    </div>

    <script>
        // Function to open the delete confirmation modal
        function deletePost(postId) {
            var modal = document.getElementById("deleteModal");
            modal.style.display = "block";

            var confirmDeleteBtn = document.getElementById("confirmDelete");
            // Redirect to delete-blog.php with post_id parameter when delete button in the modal is clicked
            confirmDeleteBtn.onclick = function() {
                window.location.href = "delete-blog.php?id=" + postId;
            }

            // Close the modal when the close button (x) is clicked
            var span = document.getElementsByClassName("close")[0];
            span.onclick = function() {
                modal.style.display = "none";
            }
        }
    </script>

    <script src="../script/anim-type.js"></script>
    <script src="../script/script.js"></script>
</body>


</html>
