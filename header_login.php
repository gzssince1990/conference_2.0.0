<?php
/**
 * Created by PhpStorm.
 * User: Rui Li
 * Date: 11/15/16
 * Time: 12:54 AM
 */
?>
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
                <option>User</option>
                <option>Reviewer</option>
            </select>
        </label>
        <label>
            <input type="text" size="10" name="username" placeholder="username">
        </label>
        <label>
            <input type="password" size="10" name="password" placeholder="password">
        </label>
        <input type="submit" value="Login">
        <input type="hidden" name="action" value="login">
        <a href="signup.php">
            Sign up
        </a>
    </form>
    <?php
    if (filter_input(INPUT_GET, 'error_code')){
        $error_code = filter_input(INPUT_GET, 'error_code');
        if ($error_code == "AU00200"){ ?>
            <div class="alert">Login error, please try again!</div>
        <?php
        }
    }
    ?>
    <div></div>
</div>

<?php
include_once "footer.php";
?>