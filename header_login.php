<!--
/**
 * Created by PhpStorm.
 * User: Rui Li
 * Date: 11/15/16
 * Time: 12:54 AM
 */
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IT Conferences</title>
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
<div>
    <img src = "assets/img/banner.jpg" alt = "colored arrows" />
</div>
<div>
    <!-- option login area -->
    <form action="controller.php" method="post">
        <label>Login as a:
            <select name="login_as">
                <option>Student</option>
                <option>Presenter</option>
                <option>Reviewer</option>
            </select>
        </label>
        <label>
            <input type="text" size="10" name="username" placeholder="username" required>
        </label>
        <label>
            <input type="password" size="10" name="password" placeholder="password" required>
        </label>
        <input type="submit" value="Login">
        <input type="hidden" name="action" value="login">
        <a href="sign_up.php">
            Sign up
        </a>
    </form>
    <?php
    if (filter_input(INPUT_GET, 'error_code')){
        $error_code = filter_input(INPUT_GET, 'error_code');
        if ($error_code == "AU00200"){ ?>
            <div class="alert">
                Login error, please try again!
                <span class="closeBtn" onclick="this.parentElement.style.display='none';">&times;</span>
            </div>
        <?php
        }
        elseif ($error_code == "AU00210"){ ?>
            <div class="alert">
                Registration error, please try again!
                <span class="closeBtn" onclick="this.parentElement.style.display='none';">&times;</span>
            </div>
        <?php
        }
    }
    ?>
</div>