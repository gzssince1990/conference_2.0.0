<?php
//require auth class
require_once "class/Auth.php";

session_start();

$auth = new Auth();

//according to the auth class return value, decide which header to include;
//var_dump($auth->is_logged_in()); //debug
if ($auth->is_logged_in()){
    $page_title = "IT Conferences";
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