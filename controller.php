<?php
/**
 * Created by PhpStorm.
 * User: Rui Li
 * Date: 11/15/2016
 * Time: 4:48 PM
 */

require_once "class/Auth.php";

session_start();

$auth = new Auth();

if(filter_input(INPUT_POST, 'action')){
    //Handle different post request
    $action = filter_input(INPUT_POST, 'action');

    if($action == 'login'){//Login;
        $login_as = filter_input(INPUT_POST, 'login_as');
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');

        $error_code = $auth->login($login_as, $username, $password);

        if (!$error_code){
            header('Location: index.php');
        }
        else {
            header('Location: index.php?error_code=AU00200');
        }
    } elseif($action == 'sign_up') {//Sign up
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');
        $password_check = filter_input(INPUT_POST, 'password_check');
        $identity = filter_input(INPUT_POST, 'identity');
        $first_name = filter_input(INPUT_POST, 'first_name');
        $last_name = filter_input(INPUT_POST, 'last_name');
        $title = filter_input(INPUT_POST, 'title');
        $company = filter_input(INPUT_POST, 'company');
        $organization = filter_input(INPUT_POST, 'organization');
        $address = filter_input(INPUT_POST, 'address');
        $phone_number = filter_input(INPUT_POST, 'phone_number');
        $email = filter_input(INPUT_POST, 'email');

        //debug
//        echo $username;
//        echo $password;
//        echo $password_check;
//        echo $identity;
//        echo $first_name;
//        echo $last_name;
//        echo $title;
//        echo $company;
//        echo $organization;
//        echo $address;
//        echo $phone_number;

        $error_code = $auth->register($username,$password,$password_check,$identity,$first_name,$last_name,$title,
            $company,$organization,$address,$phone_number,$email);

        if (!$error_code){
            header('Location: index.php');
        }
        else {
            header("Location: index.php?error_code={$error_code}");
        }

    } elseif ($action == 'credit_card') {
        //Check if user want to save the card info
        $card_number = filter_input(INPUT_POST, 'card_number');
        $security_code = filter_input(INPUT_POST, 'security_code');
        $expiry_month = filter_input(INPUT_POST, 'expiry_month');
        $expiry_year = filter_input(INPUT_POST, 'expiry_year');
        $first_name = filter_input(INPUT_POST, 'first_name');
        $last_name = filter_input(INPUT_POST, 'last_name');

        //verify expiry date
        if (($expiry_year > date("y")) or ($expiry_year == date("y") and $expiry_month > date("m"))){
            $auth->updateUserStatus(1);
            $error_code = "I00001";
            $_SESSION['status'] = 1;
            header("Location: index.php?error_code={$error_code}");
        }
        else{
            $error_code = "AU00220";
            header("Location: index.php?error_code={$error_code}");
        }
    }
    elseif ($action == 'paper_upload') {//Paper upload
        $username = $_SESSION['username'];
        $area = filter_input(INPUT_POST, 'area');
        $subarea = filter_input(INPUT_POST, 'subarea');
        $target_dir = "uploads/".$username."/";
        if(!file_exists($target_dir)){
            mkdir($target_dir,0777);
        }
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $fileType = pathinfo($target_file,PATHINFO_EXTENSION);

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($fileType != "pdf") {
            echo "Sorry, only PDF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                $auth->add_paper($username, $_FILES["fileToUpload"]["name"], $area, $subarea);
                header('Location: review.php');
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
// elseif ($action == 'delete_payment') {
//        DeletePaymentProfile($customer, $auth);
//    } elseif ($action == 'refund') {
//        Refund($customer, $auth);
//    } elseif ($action == 'recharge') {
//        Recharge($customer, $auth);
//    }
} elseif (filter_input(INPUT_GET, 'action')) {
    //Handle different get request;
    $action = filter_input(INPUT_GET, 'action');
    if($action == 'logout'){//Log out;
        $auth->logout();
        header('Location: index.php');
        die();
    }
} else {
    header('Location: index.php');
    die();
}