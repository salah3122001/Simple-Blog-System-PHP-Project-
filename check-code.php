<?php
include_once 'app/middleware/guest.php';
include_once 'app/models/User.php';

if ($_POST) {
    $errors = [];
    if (empty($_POST['code'])) {
        $errors['required'] = "<div class='alert alert-danger'>Code Is Required</div>";
    } elseif (strlen($_POST['code']) != 5) {
        $errors['digits'] = "<div class='alert alert-danger'>Code Must Be 5 Digits</div>";
    }

    if (empty($errors)) {
        $userObject = new User;
        $userObject->setCode($_POST['code']);
        $userObject->setEmail($_SESSION['user-email']);
        $codeResult = $userObject->checkCode();
        if ($codeResult) {
            $userObject->setStatus(1);
            $userObject->changeStatus();
            header("location:login.php");
            exit();
        } else {
            $errors['wrong'] = "<div class='alert alert-danger'>Incorrect Code</div>";
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <title>Code Verification</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body class="bg-light d-flex align-items-center" style="height:100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Code Verification</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="code">Enter Your 5-Digit Code</label>
                                <input type="number" name="code" id="code" class="form-control" placeholder="e.g. 12345">
                            </div>

                            <?php
                            if (isset($errors)) {
                                foreach ($errors as $key => $value) {
                                    echo $value;
                                }
                            }
                            ?>

                            <div class="text-center mt-3">
                                <button name="check" class="btn btn-success btn-block">Verify Code</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-muted text-center">
                        Please check your email for the verification code.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>