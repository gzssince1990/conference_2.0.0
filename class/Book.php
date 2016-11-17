<?php

/**
 * Created by PhpStorm.
 * User: Rui Li
 * Date: 11/17/2016
 * Time: 1:43 PM
 */
class Book
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
    private $table_book_store;

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
            $this->table_book_store = "book_store";
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * @return array
     */
    public function get_book_list(){
        $stmt = $this->db->prepare("SELECT * FROM {$this->table_book_store}");
        $stmt->execute();
        $book_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $book_list;
    }

    /**
     * @param $update_data
     * @param $book_id
     */
    public function update_book($update_data, $book_id){
        try {
            $stmt = $this->db->prepare("UPDATE {$this->table_book_store} SET book_name=:book_name, "
                . "book_price=:book_price WHERE book_id=:book_id");

            $stmt->bindparam(":book_id", $book_id);
            $stmt->bindparam(":book_name", $update_data['book_name']);
            $stmt->bindparam(":book_price", $update_data['book_price']);
            $stmt->execute();
        } catch (PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * @param $book_id
     */
    public function delete_book($book_id){
        try {
            $stmt = $this->db->prepare("DELETE FROM {$this->table_book_store} WHERE book_id=:book_id");
            $stmt->bindparam(":book_id", $book_id);
            $stmt->execute();
        } catch (PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    public function add_book($insert_data){
        $stmt = $this->db->prepare("INSERT INTO {$this->table_book_store}(book_name, book_price) "
            . "VALUES(:book_name, :book_price)");
        $stmt->bindparam(":book_name", $insert_data['book_name']);
        $stmt->bindparam(":book_price", $insert_data['book_price']);
        $stmt->execute();
    }
}
