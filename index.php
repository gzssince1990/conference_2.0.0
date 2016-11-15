<?php
//require auth class
require_once "class/Auth.php";
$auth = new Auth();

//according to the auth class return value, decide which header to include;
if ($auth->is_logged_in()){
    $user_name = "ge";
    include_once "header.php";
}
else {
    include_once "header_login.php";
}
?>
<h1>Welcome to conference!</h1>

<p>
    Content goes here. (description)
</p>

<?php
    include_once 'footer.php';
?>
