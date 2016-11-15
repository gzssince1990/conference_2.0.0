<?php
//TODO require auth class
require_once "class/Auth.php";
//$auth = new Auth();

//TODO according to the auth class return value, decide which header to include;
if (true){
    $user_name = "ge";
    include_once "header.php";
}
else {
    include_once "header_login.php";
}

//    $action = 'index.php';
//    $content_source = 'index.html';

//    include_once 'session_control.php';
//    include_once $content_source;
//    include_once 'footer.php';
?>