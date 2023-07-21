<?php

namespace services;

use mysqli;

class DB
{
    public $db_host='localhost';
    public $db_user='root';
    public $db_password='root';
    public $db_database='react_php';

    public function databases()
    {

       $conn= new mysqli($this->db_host, $this->db_user, $this->db_password, $this->db_database);

        if($conn->connect_error) {
            die('Connection failed'.$conn->connect_error);
        }
        return $conn;
    }



}
