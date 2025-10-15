<?php
include_once 'app/middleware/guest.php';
include_once 'app/requests/Validation.php';
include_once 'app/models/User.php';

if ($_POST) {
  $success = [];

  $emailValidation = new Validation("Email", $_POST['email']);
  $emailRequired = $emailValidation->required();
  $emailPattern = "/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/";
  if (empty($emailRequired)) {
    $emailRegEx = $emailValidation->regex($emailPattern);
    if (empty($emailRegEx)) {
      $success['email'] = 'email';
    }
  }

  $passwordValidation = new Validation("Password", $_POST['password']);
  $passwordRequired = $passwordValidation->required();
  $passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,15}$/";
  if (empty($passwordRequired)) {
    $passwordRegEx = $passwordValidation->regex($passwordPattern);
    if (empty($passwordRegEx)) {
      $success['password'] = 'password';
    }
  }

  if (isset($success['email']) && isset($success['password'])) {
    $userLogin = new User;
    $userLogin->setEmail($_POST['email']);
    $result = $userLogin->login();
    if ($result) {
      $user = $result->fetch_object();
      if ($user->status == 1) {
        $_SESSION['user'] = $user;
        header("location:profile.php");
        exit();
      }
    }
  } else {
    $error = "<div class='alert alert-danger'>Email Or Password Is Incorrect Or Your Account Is Not Verified</div>";
  }
}


?>



<!doctype html>
<html lang="en">

<head>
  <title>Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body class="bg-light">

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card shadow-lg rounded">
          <div class="card-header bg-primary text-white text-center">
            <h3>Login</h3>
          </div>
          <div class="card-body">

            <?= isset($error) ? $error : '' ?>

            <form action="" method="post">
              <!-- Email -->
              <div class="form-group">
                <label for="email">Email</label>
                <input
                  type="email"
                  id="email"
                  name="email"
                  class="form-control <?= (!empty($emailRequired) || !empty($emailRegEx)) ? 'is-invalid' : '' ?>"
                  placeholder="Enter your email"
                  value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
                <?= empty($emailRequired) ? '' : "<div class='invalid-feedback'>$emailRequired</div>" ?>
                <?= empty($emailRegEx) ? '' : "<div class='invalid-feedback'>$emailRegEx</div>" ?>
              </div>

              <!-- Password -->
              <div class="form-group">
                <label for="password">Password</label>
                <input
                  type="password"
                  id="password"
                  name="password"
                  class="form-control <?= (!empty($passwordRequired) || !empty($passwordRegEx)) ? 'is-invalid' : '' ?>"
                  placeholder="Enter your password">
                <?= empty($passwordRequired) ? '' : "<div class='invalid-feedback'>$passwordRequired</div>" ?>
                <?= empty($passwordRegEx) ? '' : "<div class='invalid-feedback'>Minimum 8-15 characters, at least one uppercase, one lowercase, one number, one special character</div>" ?>
              </div>

              <!-- Buttons -->
              <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
              <a href="register.php" class="btn btn-outline-secondary btn-block mt-2">Create New Account</a>
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