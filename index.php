<?php
//require auth class
require_once "class/Auth.php";

session_start();

$auth = new Auth();

//according to the auth class return value, decide which header to include;
//var_dump($auth->is_logged_in()); //debug
//var_dump(isset($_SESSION['status'])? $_SESSION['status']: 'No record');
if ($auth->is_logged_in() and !$auth->is_active()){
    $page_title = "IT Conferences";
    include_once "header.php"; ?>

    <h2>Credit Card Infomation</h2>
    <?php
    if (filter_input(INPUT_GET, 'error_code')){
        $error_code = filter_input(INPUT_GET, 'error_code');
        if ($error_code == "AU00220"){ ?>
            <div class="alert">
                Payment failed. please try again!
                <span class="closeBtn" onclick="this.parentElement.style.display='none';">&times;</span>
            </div>
            <?php
        }
    }
    ?>
    <form action="controller.php" method="post" autocomplete="off">
        <input type="hidden" name="action" value="credit_card">
        <table>
            <tbody>
            <tr>
                <td colspan="3"><label for="card_number">Card Number<sup>*</sup></label></td>
                <td><label for="security_code">CVV2<sup>*</sup></label></td>
            </tr>
            <tr>
                <td colspan="3"><input type="text" size="32" name="card_number" id="card_number" required/></td>
                <td><input type="text" size="6" name="security_code" id="security_code" required/></td>
            </tr>
            <tr>
                <td><label for="expiry_month">Month<sup>*</sup></label></td>
                <td><label for="expiry_year">Year<sup>*</sup></label></td>
                <td><label for="first_name">First Name<sup>*</sup></label></td>
                <td><label for="last_name">Last Name<sup>*</sup></label></td>
            </tr>
            <tr>
                <td>
                    <select name="expiry_month" id="expiry_month" required>
                        <option selected="" value="">Month</option>
                        <option value="01">01-Jan</option>
                        <option value="02">02-Feb</option>
                        <option value="03">03-Mar</option>
                        <option value="04">04-Apr</option>
                        <option value="05">05-May</option>
                        <option value="06">06-Jun</option>
                        <option value="07">07-Jul</option>
                        <option value="08">08-Aug</option>
                        <option value="09">09-Sep</option>
                        <option value="10">10-Oct</option>
                        <option value="11">11-Nov</option>
                        <option value="12">12-Dec</option>
                    </select>
                </td>
                <td>
                    <select name="expiry_year" id="expiry_year" required>
                        <option selected="" value="">Year</option>
                        <option value="16">2016</option>
                        <option value="17">2017</option>
                        <option value="18">2018</option>
                        <option value="19">2019</option>
                        <option value="20">2020</option>
                        <option value="21">2021</option>
                        <option value="22">2022</option>
                        <option value="23">2023</option>
                        <option value="24">2024</option>
                        <option value="25">2025</option>
                        <option value="26">2026</option>
                        <option value="27">2027</option>
                        <option value="28">2028</option>
                        <option value="29">2029</option>
                        <option value="30">2030</option>
                        <option value="31">2031</option>
                        <option value="32">2032</option>
                    </select>
                </td>
                <td><input type="text" size="6" name="first_name" id="first_name" required></td>
                <td><input type="text" size="6" name="last_name" id="last_name" required></td>
            </tr>
            </tbody>
        </table>
        <table>
            <tbody>
            <tr>
                <th><input type="submit" value="Pay"></th>
                <th><input type="reset" value="Clear"></th>
            </tr>
            </tbody>
        </table>
    </form>
    <?php
}
else {
    if ($auth->is_logged_in() and $auth->is_active()){
        $page_title = "IT Conferences";
        include_once "header.php";
    }
    elseif (!$auth->is_logged_in()){
        include_once "header_login.php";
    }

    if (filter_input(INPUT_GET, 'error_code')){
        $error_code = filter_input(INPUT_GET, 'error_code');
        if ($error_code == "I00001"){ ?>
            <div class="success">
                Thank you so and so for your online registration!
                <span class="closeBtn" onclick="this.parentElement.style.display='none';">&times;</span>
            </div>
            <?php
        }
    }

    ?>
    <h1>Welcome to conference!</h1>

    <p>
        Content goes here. (description)
    </p>
    <?php
}
include_once 'footer.php';