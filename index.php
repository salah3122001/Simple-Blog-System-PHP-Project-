<?php
include_once 'app/middleware/guest.php';
include_once 'app/models/Post.php';
include_once 'app/models/User.php';
include_once 'app/models/Comment.php';
include_once 'app/models/Like.php';

$user = new User;
$comment = new Comment;
$post = new Post;
$like = new Like;
?>

<!doctype html>
<html lang="en">

<head>
    <title>Blog - Home</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <style>
        body {
            background: #f8f9fa;
        }

        .navbar {
            margin-bottom: 30px;
        }

        .card {
            margin-bottom: 20px;
        }

        .post-title {
            font-size: 1.3rem;
            font-weight: 600;
        }

        .comments-box {
            background: #f1f3f5;
            padding: 10px;
            border-radius: 8px;
        }

        .likes {
            font-weight: bold;
            color: #007bff;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Simple Blog</a>
        <div class="ml-auto">
            <form action="login.php" method="post" class="d-inline">
                <button class="btn btn-outline-light btn-sm">Login</button>
            </form>
            <form action="register.php" method="post" class="d-inline">
                <button class="btn btn-warning btn-sm">Register</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <h2 class="mb-4 text-center">All Posts</h2>

        <?php
        $postResult = $post->getAllPosts();


        if ($postResult && $postResult->num_rows > 0) {
            $postData = $postResult->fetch_all(MYSQLI_ASSOC);
            foreach ($postData as $postObject) {
                $like->setPostId($postObject['id']);
                $likeResult = $like->countLikes();
                $totalLikes = 0;
                if ($likeResult && $likeResult->num_rows > 0) {
                    $likeData = $likeResult->fetch_object();
                    $totalLikes = $likeData->total_likes;
                }

                $user->setId($postObject['user_id']);
                $userResult = $user->getUserById();
                $userName = "Unknown User";
                if ($userResult && $userResult->num_rows > 0) {
                    $userData = $userResult->fetch_object();
                    $userName = $userData->name;
                }
        ?>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="post-title"><?= $postObject['title'] ?></h5>
                        <p><?= $postObject['body'] ?></p>
                        <p class="likes"><?= $totalLikes ?> Likes</p>
                        <hr>
                        <h6>Comments:</h6>
                        <div class="comments-box">
                            <?php
                            $comment->setPostId($postObject['id']);
                            $commentResult = $comment->getAllcomments();

                            if ($commentResult && $commentResult->num_rows > 0) {
                                $commentData = $commentResult->fetch_all(MYSQLI_ASSOC);
                                foreach ($commentData as $commentObject) {
                                    echo "<p>• " . $commentObject['body'] . "</p>";
                                }
                            } else {
                                echo "<p>No Comments Yet</p>";
                            }
                            ?>
                        </div>
                        <p class="text-muted mt-2">Posted by: <strong><?= $userName ?></strong></p>
                    </div>
                </div>

        <?php
            }
        } else {
            echo "<div class='alert alert-info text-center'>No Posts Yet</div>";
        }

        ?>
    </div>

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>