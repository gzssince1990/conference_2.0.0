<?php
//require auth class
require_once "class/Auth.php";

session_start();

$auth = new Auth();

//according to the auth class return value, decide which header to include;
//var_dump($auth->is_logged_in()); //debug
if ($auth->is_logged_in()){
    header('Location: index.php');
    die();
}

include_once 'header_login.php';
?>
<br><br>
<form action="controller.php" method="post" autocomplete="on">
    <input type="hidden" name="action" value="sign_up">

    <table>
        <tbody>
        <tr>
            <th>Username</th>
            <th><input type="text" name="username" required></th>
            <td><?php if(isset($error['username'])){echo $error['username'];}?></td>
        </tr>

        <tr>
            <th>Enter Password</th>
            <th><input type="password" name="password" required></th>
            <td><?php if(isset($error['password'])){ echo $error['password'];}?></td>
        </tr>

        <tr>
            <th>Re-enter Password</th>
            <th><input type="password" name="password_check" required></th>
            <td><?php if(isset($error['passchek'])){ echo $error['passchek'];}?></td>
        </tr>

        <tr>
            <th>Select your role</th>
            <th>
                <select name="identity">
                    <option>Author</option>
                    <option>Presenter</option>
                    <option selected>Student</option>
                    <option>Regular Attendee</option>
            <td><?php if(isset($error['identity'])){ echo $error['identity'];}?></td>
            </select>
            </th>
        </tr>

        <tr>
            <th>First Name</th>
            <th><input type="text" name="first_name"></th>
        </tr>

        <tr>
            <th>Last Name</th>
            <th><input type="text" name="last_name"></th>
        </tr>

        <tr>
            <th>Title</th>
            <th><input type="text" name="title"></th>
        </tr>

        <tr>
            <th>Company</th>
            <th><input type="text" name="company"></th>
        </tr>

        <tr>
            <th>Organization</th>
            <th><input type="text" name="organization"></th>
        </tr>

        <tr>
            <th>Address</th>
            <th><input type="text" name="address"></th>
        </tr>

        <tr>
            <th>Phone Number</th>
            <th><input type="tel" name="phone_number"></th>
        </tr>

        <tr>
            <th>Email Address</th>
            <th><input type="email" name="email"></th>
        </tr>
        
        <tr>
            <th><input type="submit" value="Submit"></th>
            <th><input type="reset" value="Clear"></th>
        </tr>
        </tbody>
    </table>
</form>

<?php
include_once 'footer.php';