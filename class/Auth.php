<?php

/**
 * Created by PhpStorm.
 * User: Rui Li
 * Date: 11/15/2016
 * Time: 3:52 PM
 */
class Auth
{
    private $db;
    private $table;

    function __construct($DB_con,$DB_table)
    {
        $this->db = $DB_con;
        $this->table = $DB_table;
    }

    public function register($uname,$upass,$uchek,$uid,$fname,$lname,$title,
                             $company,$organ,$uaddr,$phone,$email)
    {
        if(empty($uname)){
            $err['username'] = 'username is required';
        }
        elseif(empty($upass)) {
            $err['password'] = 'password is required';
        }
        elseif(empty($uchek)){
            $err['passchek'] = 'Please reenter your password';
        }
        elseif ($upass != $uchek) {
            $err['passchek'] = 'Passwords does not match';

        }
        elseif(empty($uid)){
            $err['identity'] = 'Identity is required';
        }
        else{
            try {
                $err = [];

                $query = "SELECT * FROM $this->table WHERE username='$uname'";
                echo $this->table;
                $result = $this->db->query($query);
                $row = $result->fetch();

                if($row['username'] == $uname){
                    $err['username'] = 'Username exists, please try another one';
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


                    //$query = "INSERT INTO auth(username,password)"
                    //. "VALUES(:username,:password)";
                    //$prepare = $db->prepare($query);
                    //$result = $prepare->execute(array(':username'=>$username,':password'=>$password));
                    if($result == 1){
                        header('Location: '. filter_input(INPUT_POST, 'ref'));
                        session_start();
                        $_SESSION['username'] = $uname;
                        $_SESSION['table'] = $this->table;
                    }
                    else {
                        echo 'Something wrong!';
                    }
                }

            }catch (PDOException $ex){
                echo $ex->getMessage();
            }
        }

        return $err;
    }

    public function login($uname,$upass,$is_hash)
    {
        $error_code = 1;
        if(empty($uname) or empty($upass))
        {
            $error_code = -2;
        }
        else
        {
            $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE username=:uname");
            $stmt->bindparam(":uname", $uname);
            $stmt->execute();
            $row=$stmt->fetch(PDO::FETCH_ASSOC);


            if($stmt->rowCount() > 0)
            {

                if($is_hash and password_verify($upass, $row['password']))
                {
                    $_SESSION['username'] = $uname;
                    $_SESSION['table'] = $this->table;
                }
                elseif(!$is_hash and $upass == $row['password']){
                    $_SESSION['username'] = $uname;
                    $_SESSION['table'] = $this->table;
                }
                else
                {
                    $error_code = -1;
                }
            }
            else{
                $error_code = 0;
            }
        }
        return $error_code;
    }

    public function get_papers($uname){
        $stmt = $this->db->prepare("SELECT au.username AS uname, p.file_name AS filename "
            . "FROM paper p "
            . "JOIN group_paper gp ON gp.paper_id=p.paper_id  "
            . "JOIN reviewer rv ON rv.reviewer_id=gp.group_id "
            . "JOIN auth au ON au.user_id=p.user_id "
            . "WHERE rv.username=:uname");

        $stmt->bindparam(":uname", $uname);
        $stmt->execute();
        $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);


        //echo $stmt->rowCount();
        foreach($rows as $row){
            $file[] = $row['uname'].'/'.$row['filename'];
        }

        return $file;
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

    public function get_id_by_uname($uname) {
        $stmt = $this->db->prepare("SELECT user_id FROM auth WHERE username=:uname");

        $stmt->bindparam(":uname", $uname);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);

        return $row['user_id'];
    }

    public function add_paper($uname, $file_name) {
        $uid = $this->get_id_by_uname($uname);
        try {
            $stmt = $this->db->prepare("INSERT INTO "
                . "$this->table(user_id,file_name) "
                . "VALUES(:uid,:file_name)");

            $stmt->bindparam(":uid", $uid);
            $stmt->bindparam(":file_name", $file_name);

            $stmt->execute();
        } catch (PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    public function is_loggedin()
    {
        if(isset($_SESSION['user_session']))
        {
            return true;
        }
    }

    public function redirect($url)
    {
        header("Location: $url");
    }

    public function logout()
    {
        session_destroy();
        unset($_SESSION['user_session']);
        return true;
    }
}