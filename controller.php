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

    //Login;
    if($action == 'login'){
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');

        $error_code = $auth->login($username, $password);

        if (!$error_code){
            header('Location: index.php');
        }
        else {
            header('Location: index.php?error_code=AU00200');
        }
        echo json_encode(array(
            'message' => $result['message'],
            'data' => $result['data']
        ));
        /**
         * Check log status;
         */
    } elseif($action == 'check_login') {
        $result = array();

        if(isset($_SESSION['username'])){
            $result['message'] = "logged in";
            $result['status'] = 200;

            $result['data']['payment_method'] =
                $customer->getPaymentMethod(null, $_SESSION['username']);

            $result['data']['username'] = $_SESSION['username'];
        } else {
            $result['message'] = "not logged in";
            $result['status'] = 401;
            $result['data'] = null;
        }

        http_response_code($result['status']);
        echo json_encode(array(
            'message' => $result['message'],
            'data' => $result['data']
        ));
        /**
         * Log out
         */
    } elseif($action == 'logout'){
        if(isset($_SESSION['username'])){
            unset($_SESSION['username']);
            unset($_SESSION['timeout']);
        }

        http_response_code(200);
        echo json_encode(array(
            'message' => 'successful'
        ));
        /**
         * Pay with a new credit card
         * Use card number, expiration date and security code
         */
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
}