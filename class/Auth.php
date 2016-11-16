<?php

/**
 * Created by PhpStorm.
 * User: Rui Li
 * Date: 11/15/2016
 * Time: 3:52 PM
 */
class Auth
{
    //db config
    private $DB_host = "localhost";
    private $DB_user = "root";
    private $DB_pass = "";
    private $DB_name = "conference";

    //db info
    private $db;
    private $table;
    private $table_paper;

    /**
     * Auth constructor.
     */
    function __construct() {
        try
        {
            $DB_con = new PDO("mysql:host={$this->DB_host};dbname={$this->DB_name}",$this->DB_user,$this->DB_pass);
            $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db = $DB_con;
            $this->table = "auth";
            $this->table_paper = "paper";
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * all user login
     * @param $login_as
     * @param $uname
     * @param $upass
     * @param $is_hash
     * @return int
     */
    public function login($login_as, $uname, $upass, $is_hash=true) {
        $error_code = 0;
        if(empty($uname) or empty($upass))
        {
            $error_code = -1;
        }
        else
        {
            $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE username=:uname AND identity=:identity");
            $stmt->bindparam(":uname", $uname);
            $stmt->bindparam(":identity", $login_as);
            $stmt->execute();
            $row=$stmt->fetch(PDO::FETCH_ASSOC);

            if($stmt->rowCount() > 0)
            {
                if($is_hash and password_verify($upass, $row['password'])) {
                    $_SESSION['username'] = $uname;
                    $_SESSION['status'] = $row['status'];
                }
                else {
                    $error_code = -2;
                }
            }
            else{
                $error_code = -3;
            }
        }
        return $error_code;
    }

    /**
     * @return bool
     */
    public function is_logged_in()
    {
        return isset($_SESSION['username']);
    }

    /**
     * logout the current user
     */
    public function logout()
    {
        session_destroy();
        unset($_SESSION['username']);
        unset($_SESSION['status']);
    }

    public function is_active(){
        return isset($_SESSION['status']) and $_SESSION['status'] == 1;
    }

    /**
     * register
     * @param $uname
     * @param $upass
     * @param $uchek
     * @param $uid
     * @param $fname
     * @param $lname
     * @param $title
     * @param $company
     * @param $organ
     * @param $uaddr
     * @param $phone
     * @param $email
     * @return array
     */
    public function register($uname,$upass,$uchek,$uid,$fname,$lname,$title,$company,$organ,$uaddr,$phone,$email) {
        if(empty($uname)){
//            $err['username'] = 'username is required';
            $error_code = "AU00210";
        }
        elseif(empty($upass)) {
//            $err['password'] = 'password is required';
            $error_code = "AU00210";
        }
        elseif(empty($uchek)){
//            $err['passchek'] = 'Please reenter your password';
            $error_code = "AU00210";
        }
        elseif ($upass != $uchek) {
//            $err['passchek'] = 'Passwords does not match';
            $error_code = "AU00210";

        }
        elseif(empty($uid)){
//            $err['identity'] = 'Identity is required';
            $error_code = "AU00210";
        }
        else{
            try {
                $error_code = [];

                $query = "SELECT * FROM $this->table WHERE username='$uname'";
                $result = $this->db->query($query);
                $row = $result->fetch(PDO::FETCH_ASSOC);

                if($row['username'] == $uname){
//                    $err['username'] = 'Username exists, please try another one';
                    $error_code = "AU00210";
                }
                else {
                    $new_password = password_hash($upass, PASSWORD_DEFAULT);
                    $stmt = $this->db->prepare("INSERT INTO "
                        . "$this->table(username,password,identity,first_name,last_name,"
                        . "title,company,organization,address,phone_number,email) "
                        . "VALUES(:uname, :upass, :uid, :fname, :lname, "
                        . ":title, :company, :organ, :uaddr, :phone, :email)");

                    $stmt->bindparam(":uname", $uname);
                    $stmt->bindparam(":upass", $new_password);
                    $stmt->bindparam(":uid", $uid);
                    $stmt->bindparam(":fname", $fname);
                    $stmt->bindparam(":lname", $lname);
                    $stmt->bindparam(":title", $title);
                    $stmt->bindparam(":company", $company);
                    $stmt->bindparam(":organ", $organ);
                    $stmt->bindparam(":uaddr", $uaddr);
                    $stmt->bindparam(":phone", $phone);
                    $stmt->bindparam(":email", $email);

                    $result = $stmt->execute();

                    if($result == 1){
                        $_SESSION['username'] = $uname;
                        return 0;
//                        header('Location: index.php');
                    }
//                    else {
//                        echo 'Something wrong!';
//                    }
                }
            }catch (PDOException $ex){
                echo $ex->getMessage();
                $error_code = "AU00210";
            }
        }

        return $error_code;
    }

    /**
     * @param $status
     */
    public function updateUserStatus($status){
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status={$status} WHERE username='{$_SESSION['username']}'");
        $stmt->execute();
    }

    /**
     * add submitted paper
     * @param $uname
     * @param $file_name
     * @param $area
     * @param $subarea
     */
    public function add_paper($uname, $file_name, $area, $subarea) {
        echo "<br>";
        echo "{$uname}<br>";
        echo "{$file_name}<br>";
        echo "{$area}<br>";
        echo "{$subarea}<br>";


        $uid = $this->get_id_by_uname($uname);
        try {
            $stmt = $this->db->prepare("INSERT INTO "
                . "$this->table_paper(user_id,file_name, area, subarea) "
                . "VALUES(:uid,:file_name,:area,:subarea)");

            $stmt->bindparam(":uid", $uid);
            $stmt->bindparam(":file_name", $file_name);
            $stmt->bindparam(":area", $area);
            $stmt->bindparam(":subarea", $subarea);

            $stmt->execute();
        } catch (PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * get id by username
     * @param $uname
     * @return mixed
     */
    public function get_id_by_uname($uname) {
        $stmt = $this->db->prepare("SELECT user_id FROM auth WHERE username=:uname");

        $stmt->bindparam(":uname", $uname);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);

        return $row['user_id'];
    }

    /**
     * @param $uname
     * @return mixed
     */
    public function get_user_by_uname($uname) {
        $stmt = $this->db->prepare("SELECT * FROM auth WHERE username=:uname");

        $stmt->bindparam(":uname", $uname);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);

        return $row;
    }


    public function get_papers($uname){
        $user = $this->get_user_by_uname($uname);

        $files = array();
        $error_code = "I00001";

        if ($user['identity'] == "Reviewer"){
            //        $stmt = $this->db->prepare("SELECT au.username AS uname, p.file_name AS filename "
            //            . "FROM paper p "
            //            . "JOIN group_paper gp ON gp.paper_id=p.paper_id  "
            //            . "JOIN reviewer rv ON rv.reviewer_id=gp.group_id "
            //            . "JOIN auth au ON au.user_id=p.user_id "
            //            . "WHERE rv.username=:uname");

            $stmt = $this->db->prepare("SELECT p.file_name, au.username FROM {$this->table_paper} p "
                . "JOIN auth au ON au.user_id=p.user_id "
                . "WHERE p.reviewer_id=:reviewer_id");

            $stmt->bindparam(":reviewer_id", $user['user_id']);
            $stmt->execute();
            $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);

            $dir = "uploads/";

            foreach($rows as $row){
                $files[] = array(
                    'file_name' => "{$row['username']}/{$row['file_name']}",
                    'file_path' => "{$dir}/{$row['username']}/{$row['file_name']}"
                );
            }

            if (!$files) {
                $error_code = "PA00200";
            }
        }
        elseif ($user['identity'] == "Admin") {
            $error_code = "PA00210";
        }
        else {
            $dir = 'uploads/'.$uname.'/';

            if(!file_exists($dir)){
                $error_code = "PA00220";
            }
            else {
                $files_raw = scandir($dir);

//                var_dump($files_raw);
//                echo "<br>";

                if(count($files_raw)<3){
                    $error_code = "PA00220";
                }
                else {
                    foreach ($files_raw as $file_name){
                        if($file_name!='.' and $file_name!='..'){
                            $files[] = array(
                                'file_name' => $file_name,
                                'file_path' => "{$dir}{$file_name}"
                            );
                        }
                    }
                }
            }
        }

        $response = array('error_code'=>$error_code, 'files'=>$files);

//        var_dump($response);
        return $response;
    }

    public function add_comment($uname,$comment,$phone,$email) {

        try {
            $stmt = $this->db->prepare("INSERT INTO "
                . "$this->table(username,comment,phone_number,email) "
                . "VALUES(:uname,:comment,:phone,:email)");

            $stmt->bindparam(":uname", $uname);
            $stmt->bindparam(":comment", $comment);
            $stmt->bindparam(":phone", $phone);
            $stmt->bindparam(":email", $email);

            $stmt->execute();
        } catch (PDOException $exc) {
            echo $exc->getMessage();
        }


    }

    public function redirect($url)
    {
        header("Location: $url");
    }
}