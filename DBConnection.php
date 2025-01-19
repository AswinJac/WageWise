<?php

Class DBConnection{
    protected $db;
    function __construct(){
        $this->db = new mysqli('localhost','root','','spms_db');
        if(!$this->db){
            die("Database Connection Error.");
        }

    }
    function connection(){
        return $this->db;
    }
    function __destruct(){
         $this->db->close();
    }
}

$db_connection = new DBConnection();
$conn = $db_connection->connection();