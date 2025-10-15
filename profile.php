<?php
include_once 'app/middleware/auth.php';

if (isset($_POST['posts'])) {
    header("location:post.php");
    exit();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("location:index.php");
    exit();
}
?>
<!doctype html>
<html lang="en">

<head>
    <title>Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card shadow-lg rounded">
                    <div class="card-header bg-primary text-white text-center">
                        <h3>Welcome, <?= $_SESSION['user']->name ?> 🎉</h3>
                    </div>
                    <div class="card-body text-center">
                        <form method="post">
                            <button name="posts" class="btn btn-success btn-lg mb-3 w-100">📄 My Posts</button>
                            <button name="logout" class="btn btn-danger btn-lg w-100">🚪 Logout</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>