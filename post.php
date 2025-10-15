<?php
ob_start();
include_once 'app/middleware/auth.php';
include_once 'app/models/Post.php';
include_once 'app/models/Comment.php';
include_once 'app/models/Like.php';
include_once 'app/requests/Validation.php';

if (isset($_SESSION['success'])) {
    echo $_SESSION['success'];
    unset($_SESSION['success']);
}

if (isset($_POST['backToProfile'])) {
    header("location:profile.php");
    exit();
}

if (isset($_POST['allposts'])) {
    header("location:allposts.php");
    exit();
}


$post = new Post;
$like = new Like;
$comment = new Comment;

$titleRequired = '';
$bodyRequired = '';
$commentRequired = '';
$commRequired = '';
?>

<!doctype html>
<html lang="en">

<head>
    <title>Posts Page</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container py-4">

        <form method="post" class="mb-3">
            <button name='add' class="btn btn-primary">Add Post</button>
            <button name="show" class="btn btn-info">Show My Posts</button>
            <button name="allposts" class="btn btn-success">See All Posts</button>
            <button name="backToProfile" class="btn btn-secondary">Back To Profile</button>

        </form>

        <?php
        // insert post
        if (isset($_POST['insert'])) {
            $titleValidation = new Validation('Title', $_POST['title']);
            $bodyValidation = new Validation('Post', $_POST['body']);

            $titleRequired = $titleValidation->required();
            $bodyRequired = $bodyValidation->required();

            if (empty($titleRequired) && empty($bodyRequired)) {
                $post->setTitle($_POST['title']);
                $post->setBody($_POST['body']);
                $post->setUserId($_SESSION['user']->id);
                $result = $post->create();
                if ($result) {
                    $_SESSION['success'] = "<div class='alert alert-success'>Post Added Successfully</div>";
                    header("location:post.php");
                    exit();
                } else {
                    echo "<div class='alert alert-danger'>Try Again Later</div>";
                }
            }
        }

        // add post form
        if (isset($_POST['add']) || isset($_POST['insert'])) { ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Add New Post</h5>
                    <form method="post">
                        <div class="form-group">
                            <input type="text" name="title" class="form-control" placeholder="Write Title Here"
                                value="<?= isset($_POST['title']) ? $_POST['title'] : '' ?>">
                            <?= empty($titleRequired) ? '' : "<div class='alert alert-danger mt-2'>$titleRequired</div>" ?>
                        </div>
                        <div class="form-group">
                            <textarea name="body" class="form-control" placeholder="Write Your Post Here"><?= isset($_POST['body']) ? $_POST['body'] : '' ?></textarea>
                            <?= empty($bodyRequired) ? '' : "<div class='alert alert-danger mt-2'>$bodyRequired</div>" ?>
                        </div>
                        <button name="insert" class="btn btn-success">Add Post</button>
                    </form>
                </div>
            </div>
            <?php }

        // add comment
        if (isset($_POST['addcomment'])) {
            $commentValidation = new Validation("Comment", $_POST['body_comment']);
            $commentRequired = $commentValidation->required();
            if (empty($commentRequired)) {
                $comment->setBody($_POST['body_comment']);
                $comment->setUserId($_SESSION['user']->id);
                $comment->setPostId($_POST['post_id']);
                $result = $comment->create();
                if ($result) {
                    $_SESSION['success'] = "<div class='alert alert-success'>Comment Added Successfully</div>";
                    header("location:post.php");
                    exit();
                } else {
                    echo "<div class='alert alert-danger'>Try Again Later</div>";
                }
            }
        }

        // show posts
        if (isset($_POST['show']) || !empty($commentRequired)) {
            $post->setUserId($_SESSION['user']->id);
            $result = $post->read();


            if ($result && $result->num_rows > 0) {
                $postResult = $result->fetch_all(MYSQLI_ASSOC);
            ?>
                <table class="table table-bordered bg-white">
                    <thead class="thead-dark">
                        <tr>
                            <th>Title</th>
                            <th>Post</th>
                            <th>Your Comments</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($postResult as $key => $postObject) { ?>
                            <tr>
                                <td><?= $postObject['title'] ?></td>
                                <td><?= $postObject['body'] ?></td>
                                <td>
                                    <?php
                                    $comment->setUserId($_SESSION['user']->id);
                                    $comment->setPostId($postObject['id']);
                                    $result = $comment->read();
                                    if ($result and $result->num_rows > 0) {
                                        $commentResult = $result->fetch_all(MYSQLI_ASSOC);
                                        foreach ($commentResult as $key => $commentObject) {
                                            echo "<div class='border rounded p-2 mb-2 bg-light'>";
                                            echo $commentObject['body'];
                                    ?>
                                            <form method='post' class="d-inline">
                                                <input type="hidden" name="comment_id" value="<?= $commentObject['id'] ?>">
                                                <input type="hidden" name="old_comment" value="<?= $commentObject['body'] ?>">
                                                <button name='edit_comment' class='btn btn-sm btn-warning'>Edit Comment</button>
                                            </form>
                                            <form method='post' class="d-inline">
                                                <input type="hidden" name="comment_id" value="<?= $commentObject['id'] ?>">
                                                <button name='delete_comment' class='btn btn-sm btn-danger'>Delete Comment</button>
                                            </form>
                                    <?php
                                            echo "</div>";
                                        }
                                    } else {
                                        echo "No Comments Yet";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <form action="" method="post" class="d-inline">
                                        <input type="hidden" name="post_id" value="<?= $postObject['id'] ?>">
                                        <input type="hidden" name="old_title" value="<?= $postObject['title'] ?>">
                                        <input type="hidden" name="old_body" value="<?= $postObject['body'] ?>">
                                        <button name="edit" class="btn btn-sm btn-warning">Edit Post</button>
                                    </form>
                                    <form action="" method="post" class="d-inline">
                                        <input type="hidden" name="post_id" value="<?= $postObject['id'] ?>">
                                        <button name="delete" class="btn btn-sm btn-danger">Delete Post</button>
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <form action="" method="post" class="d-inline">
                                        <input type="hidden" name="post_id" value="<?= $postObject['id'] ?>">
                                        <button name="like" class="btn btn-outline-primary btn-sm">Like</button>
                                    </form>
                                    <span class="ml-2">
                                        <?php
                                        $like->setPostId($postObject['id']);
                                        $likeCount = $like->countLikes();
                                        $likeData = $likeCount->fetch_object();
                                        echo $likeData->total_likes . " Likes";
                                        ?>
                                    </span>
                                    <form action="" method="post" class="mt-2">
                                        <input type="hidden" name="post_id" value="<?= $postObject['id'] ?>">
                                        <input type="text" name="body_comment" class="form-control mb-2" placeholder="Write comment...">
                                        <?= empty($commentRequired) ? '' : "<div class='alert alert-danger'>$commentRequired</div>" ?>
                                        <button name="addcomment" class="btn btn-sm btn-success">Add Comment</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php
            } else {
                echo "<div class='alert alert-info'>No Posts Yet✨</div>";
            }
        }

        // update post
        if (isset($_POST['update'])) {
            $titleValidation = new Validation('Title', $_POST['newtitle'] ?? '');
            $bodyValidation = new Validation('Post', $_POST['newbody'] ?? '');

            $titleRequired = $titleValidation->required();
            $bodyRequired = $bodyValidation->required();

            if (empty($titleRequired) && empty($bodyRequired)) {
                $post->setId($_POST['post_id']);
                $post->setTitle($_POST['newtitle']);
                $post->setBody($_POST['newbody']);
                $updateResult = $post->update();
                if ($updateResult) {
                    $_SESSION['success'] = "<div class='alert alert-success'>Successfully Updated</div>";
                    header("location:post.php");
                    exit();
                } else {
                    echo "<div class='alert alert-danger'>Try Again Later</div>";
                }
            }
        }

        if (isset($_POST['edit']) || isset($_POST['update'])) { ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Edit Post</h5>
                    <form method="post">
                        <input type="hidden" name="post_id" value="<?= $_POST['post_id'] ?>">
                        <div class="form-group">
                            <input type="text" name="newtitle" class="form-control" placeholder="Write New Title Here"
                                value="<?= isset($_POST['old_title']) ? $_POST['old_title'] : '' ?>">
                            <?= empty($titleRequired) ? '' : "<div class='alert alert-danger mt-2'>$titleRequired</div>" ?>
                        </div>
                        <div class="form-group">
                            <textarea name="newbody" class="form-control" placeholder="Write Your New Post Here"><?= isset($_POST['old_body']) ? $_POST['old_body'] : '' ?></textarea>
                            <?= empty($bodyRequired) ? '' : "<div class='alert alert-danger mt-2'>$bodyRequired</div>" ?>
                        </div>
                        <button name="update" class="btn btn-primary">Update Post</button>
                    </form>
                </div>
            </div>
        <?php }

        // delete post
        if (isset($_POST['delete'])) {
            $post->setId($_POST['post_id']);
            $deleteResult = $post->delete();
            if ($deleteResult) {
                $_SESSION['success'] = "<div class='alert alert-success'>Successfully Deleted</div>";
                header("location:post.php");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Try Again Later</div>";
            }
        }

        // like
        if (isset($_POST['like'])) {
            $like->setPostId($_POST['post_id']);
            $like->setUserId($_SESSION['user']->id);
            $existingLike = $like->checkUserLike();
            if ($existingLike) {
                $result = $like->unlike();
                if ($result) {
                    $_SESSION['success'] = "<div class='alert alert-warning'>Like Removed Successfully</div>";
                    header("location:post.php");
                    exit();
                }
            } else {
                $result = $like->like();
                if ($result) {
                    $_SESSION['success'] = "<div class='alert alert-success'>Like Added Successfully</div>";
                    header("location:post.php");
                    exit();
                }
            }
        }

        // update comment
        if (isset($_POST['update_comment'])) {
            $commValidation = new Validation('New Comment', $_POST['updated_comment']);
            $commRequired = $commValidation->required();
            if (empty($commRequired)) {
                $comment->setId($_POST['comment_id']);
                $comment->setBody($_POST['updated_comment']);
                $result = $comment->update();
                if ($result) {
                    $_SESSION['success'] = "<div class='alert alert-success'>Comment Updated Successfully</div>";
                    header("location:post.php");
                    exit();
                } else {
                    echo "<div class='alert alert-danger'>Try Again Later</div>";
                }
            }
        }

        if (isset($_POST['edit_comment']) || !empty($commRequired)) { ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5>Edit Comment</h5>
                    <form method="post">
                        <input type="hidden" name="comment_id" value="<?= $_POST['comment_id'] ?>">
                        <input type="text" name="updated_comment" class="form-control mb-2"
                            value="<?= isset($_POST['old_comment']) ? $_POST['old_comment'] : '' ?>">
                        <?= empty($commRequired) ? '' : "<div class='alert alert-danger'>$commRequired</div>" ?>
                        <button name="update_comment" class="btn btn-primary">Update Comment</button>
                    </form>
                </div>
            </div>
        <?php }

        // delete comment
        if (isset($_POST['delete_comment'])) {
            $comment->setId($_POST['comment_id']);
            $result = $comment->delete();
            if ($result) {
                $_SESSION['success'] = "<div class='alert alert-danger'>Comment Deleted</div>";
                header("location:post.php");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Try Again Later</div>";
            }
        }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
<?php ob_end_flush(); ?>

</html>