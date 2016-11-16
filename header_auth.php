<?php
/**
 * Created by PhpStorm.
 * User: Rui Li
 * Date: 11/16/2016
 * Time: 2:09 PM
 */

//require auth class
require_once "class/Auth.php";

session_start();

$auth = new Auth();

//according to the auth class return value, decide which header to include;
if (!$auth->is_logged_in() or !$auth->is_active()){
    header('Location: index.php');
    die();
}