<?php


    include_once __DIR__ . "/../database/config.php";
class Validation{
    private $name;
    private $value;

    public function __construct($name,$value)
    {
        $this->name=$name;
        $this->value=$value;
    }

    public function required()
    {
        return (empty($this->value)) ? "$this->name Is Required" : '';
    }
    public function regex($pattern){
        return (preg_match($pattern,$this->value)) ? "" : "$this->name IS Invalid";

    }
    public function unique($table){
        $query = "SELECT * FROM $table WHERE $this->name='$this->value'";
        $config=new config;
        $result=$config->runDQL($query);
        return (empty($result)) ? '' : "$this->name Exists";
    }
    public function confirmed($valueConfirmation){
        return ($this->value == $valueConfirmation) ? '' : "$this->name Is Not Confirmed";
    }
}




?>