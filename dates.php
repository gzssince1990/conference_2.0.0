<?php
//require auth class
require_once "class/Auth.php";

session_start();

$auth = new Auth();

//according to the auth class return value, decide which header to include;
if (!$auth->is_logged_in()){
    header('Location: index.php');
    die();
}

$page_title = "IT Conferences";
include_once "header.php";
?>
    <h1>Important Dates!</h1>

    <p>
        Content goes here. (description)
    </p>
<?php include_once 'footer.php';
