<?php
session_start();
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include 'head.php';?>
    <title>Login - Affiliate Panel</title>
  </head>
  <body>
    <div class="loginmain">
      <div class="row rowtoppadded10">
        <div class="col s4 offset-s4 loginbox center">
          <h5>Login</h5>
          
          <?php
            $emailPasswordError = "";
            
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $emailPasswordError = "";
                $passwordValid = $emailValid = false;

                if (empty($_POST["email"])) {
                  $emailPasswordError = "Email is required";
                } else {
                  $email = test_input($_POST["email"]);
                  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailPasswordError = "Invalid email format";
                  } else {
                    $sql = "SELECT * FROM `{$userTableName}` WHERE `{$emailColumn}` = '{$email}'";
                    $result = $conn->query($sql);

                    if (empty($result) OR $result->num_rows === 0) {
                      $emailPasswordError = "Username/Password Combination Incorrect";
                    } else {
                      // Account Exists
                      $emailValid = true;
                    }
                  }
                }

                if (empty($_POST["password"])) {
                  $emailPasswordError = "Password is required";
                } else {
                  $password = test_input($_POST["password"]);

                  $sql = "SELECT `{$hashPasswordColumn}` FROM `{$userTableName}` WHERE `{$emailColumn}` = '{$email}'";
                  $result = $conn->query($sql) or die($conn->error);
                  $result = $result->fetch_assoc();
                  $hashPass = $result[$hashPasswordColumn];

                  if (password_verify($password, $hashPass)) {
                    //Passwords Match
                    $passwordValid = true;
                  } else {
                    $emailPasswordError = "Username/Password Combination Incorrect";
                  }
                }

                if ($passwordValid && $emailValid) {
                  $sql = "SELECT `{$primaryKeyColumn}` FROM `{$userTableName}` WHERE `{$emailColumn}` = '{$email}'";
                  $result = $conn->query($sql) or die($conn->error);
                  $result = $result->fetch_assoc();
                  $primaryKey = $result[$primaryKeyColumn];

                  $_SESSION['userRefCode'] = $primaryKey;
                  $_SESSION['email'] = $email;

                  //Redirect
                  header("Location: overview.php"); die();
                }
            }

            function test_input($data) {
              $data = trim($data);
              $data = stripslashes($data);
              $data = htmlspecialchars($data);
              return $data;
            }
          ?>
          
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="input-field col s12">
              <input id="email" type="email" class="validate" name="email">
              <label for="email">Email</label>
            </div>
            <div class="input-field col s12">
              <input id="password" type="password" class="validate" name="password">
              <label for="password">Password</label>
            </div>
            <p class="red-text"><?php echo $emailPasswordError;?></p>
            <p><a href="https://app.ultifreehosting.com/signup">No account? Create one now!</a></p>
            <p class="green-text">If you already have an Ultifree Hosting Account, you can use those login credentials.</p>
            <p class="purple-text">By logging into the affiliate dashboard, you agree to the <a target="_BLANK" href="https://ultifreehosting.com/legal/affiliateTOS">Affiliate TOS</a>.</p>
            <div class="col s12 rowbottompadded">
              <button class="btn waves-effect waves-light" type="Submit" name="action">Login</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <?php include 'foot.php';?>
  </body>
</html>
