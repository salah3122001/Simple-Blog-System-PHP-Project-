<?php
include_once 'app/middleware/guest.php';
include_once 'app/models/User.php';
include_once 'app/requests/Validation.php';
include_once 'app/sevices/mail.php';

$success = [];
if ($_POST) {
    $nameValidation = new Validation('name', $_POST['name']);
    $nameRequired = $nameValidation->required();
    if (empty($nameRequired)) {
        $success['name'] = 'name';
    }

    $emailValidation = new Validation('Email', $_POST['email']);
    $emailRequired = $emailValidation->required();
    $emailPattern = "/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/";
    if (empty($emailRequired)) {

        $emailRegEx = $emailValidation->regex($emailPattern);
        if (empty($emailRegEx)) {
            $emailUnique = $emailValidation->unique('users');
            if (empty($emailUnique)) {
                $success['email'] = 'email';
            }
        }
    }
    $passwordValidation = new Validation('Password', $_POST['password']);
    $passwordRequired = $passwordValidation->required();
    $passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,15}$/";
    if (empty($passwordRequired)) {
        $passwordRegEx = $passwordValidation->regex($passwordPattern);
        if (empty($passwordRegEx)) {
            $psaswordConfirmation = $passwordValidation->confirmed($_POST['confirm_password']);
            if (empty($psaswordConfirmation)) {
                $success['password'] = 'password';
            }
        }
    }

    if (isset($success['name']) && isset($success['email']) && isset($success['password'])) {
        $userObject = new User;
        $userObject->setName($_POST['name']);
        $userObject->setEmail($_POST['email']);
        $userObject->setPassword($_POST['password']);
        $code = rand(10000, 99999);
        $userObject->setCode($code);
        $createUser = $userObject->create();
        if ($createUser) {
            $subject = "Verification Code";
            $body = "Hello {$_POST['name']} Your Verification Code Is {$code}";
            $mail = new mail($_POST['email'], $subject, $body);
            $mailResult = $mail->send();
            if ($mailResult) {
                $_SESSION['user-email'] = $_POST['email'];
                header("location:check-code.php");
                exit();
            } else {
                $errors = "<div class='alert alert-danger'>Try Again Later<div>";
            }
        } else {
            $errors = "<div class='alert alert-danger'>Try Again Later<div>";
        }
    }
}





?>


<!doctype html>
<html lang="en">

<head>
    <title>Register</title>
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
                    <div class="card-header text-center bg-primary text-white">
                        <h3>Create Account</h3>
                    </div>
                    <div class="card-body">

                        <?= isset($errors) ? $errors : '' ?>

                        <form action="" method="post">
                            <!-- Name -->
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    class="form-control <?= !empty($nameRequired) ? 'is-invalid' : '' ?>"
                                    placeholder="Enter your name"
                                    value="<?= isset($_POST['name']) ? $_POST['name'] : '' ?>">
                                <?= empty($nameRequired) ? '' : "<div class='invalid-feedback'>$nameRequired</div>" ?>
                            </div>

                            <!-- Email -->
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="form-control <?= (!empty($emailRequired) || !empty($emailRegEx) || !empty($emailUnique)) ? 'is-invalid' : '' ?>"
                                    placeholder="Enter your email"
                                    value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
                                <?= empty($emailRequired) ? '' : "<div class='invalid-feedback'>$emailRequired</div>" ?>
                                <?= empty($emailRegEx) ? '' : "<div class='invalid-feedback'>$emailRegEx</div>" ?>
                                <?= empty($emailUnique) ? '' : "<div class='invalid-feedback'>$emailUnique</div>" ?>
                            </div>

                            <!-- Password -->
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control <?= (!empty($passwordRequired) || !empty($passwordRegEx) || !empty($psaswordConfirmation)) ? 'is-invalid' : '' ?>"
                                    placeholder="Enter your password">
                                <?= empty($passwordRequired) ? '' : "<div class='invalid-feedback'>$passwordRequired</div>" ?>
                                <?= empty($passwordRegEx) ? '' : "<div class='invalid-feedback'>Minimum 8-15 characters, at least one uppercase, one lowercase, one number, one special character</div>" ?>
                                <?= empty($psaswordConfirmation) ? '' : "<div class='invalid-feedback'>$psaswordConfirmation</div>" ?>
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password</label>
                                <input
                                    type="password"
                                    id="confirm_password"
                                    name="confirm_password"
                                    class="form-control"
                                    placeholder="Re-enter your password">
                            </div>

                            <button type="submit" name="register" class="btn btn-primary btn-block">Register</button>
                            <a href="login.php" class="btn btn-outline-secondary btn-block mt-2">Already have an account? Login</a>
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