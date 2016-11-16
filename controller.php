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
        var_dump($email);

        $error_code = $auth->register($username,$password,$password_check,$identity,$first_name,$last_name,$title,
            $company,$organization,$address,$phone_number,$email);

        if (!$error_code){
            header('Location: index.php');
        }
        else {
            header("Location: index.php?error_code={$error_code}");
        }

    } elseif ($action == 'pay_new_card') {
        //Check if user want to save the card info
        $save_card = filter_input(INPUT_POST, 'save_card');

        $row = $customer->getCustomerInfo();

        if($save_card === 'true'){
            ChargeWithSave($customer, $auth);
        } else {
            ChargeWithoutSave($customer, $auth);
        }
    } elseif ($action == 'pay_old_card') {
        /**
         * Pay with an existing credit card
         */
        ChargeExistingCard($customer, $auth, null, null, null);
    } elseif ($action == 'delete_payment') {
        DeletePaymentProfile($customer, $auth);
    } elseif ($action == 'refund') {
        Refund($customer, $auth);
    } elseif ($action == 'recharge') {
        Recharge($customer, $auth);
    }
} elseif (filter_input(INPUT_GET, 'action')) {
    //Handle different get request;
    $action = filter_input(INPUT_GET, 'action');
    if($action == 'logout'){//Log out;
        $auth->logout();

        header('Location: index.php');
    }
}