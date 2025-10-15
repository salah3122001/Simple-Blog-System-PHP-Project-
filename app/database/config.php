<?php

use Dom\Mysql;

class config{
    private $hostname="localhost";
    private $username="root";
    private $password="";
    private $databasename='blog_system';
    private $conn;

    public function __construct()
    {
        $this->conn=new mysqli($this->hostname,$this->username,$this->password,$this->databasename);
        if ($this->conn->connect_error) {
            die("Connect Failed: ".$this->conn->connect_error);
        }
    }

    public function runDML(string $query) : bool
    {
        $result=$this->conn->query($query);
        if ($result) {
            return true;
        }
        else {
            return false;
        }
    }

    public function runDQL(string $query)
    {
        $result=$this->conn->query($query);
        if ($result->num_rows > 0) {
            return $result;
        }
        else {
            return;
        }
    }
}


?>