<?php
include_once __DIR__.'/../database/config.php';
include_once __DIR__.'/../database/operations';

class User extends config implements Operations{
    private $id;
    private $name;
    private $email;
    private $password;
    private $code; 
    private $status;  
    private $created_at;



    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     */
    public function setPassword($password): self
    {
        $this->password = sha1($password);

        return $this;
    }

    /**
     * Get the value of code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set the value of code
     */
    public function setCode($code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     */
    public function setStatus($status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of created_at
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     */
    public function setCreatedAt($created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function create()
    {
        $query="INSERT INTO users (name,email,password,code) VALUES('$this->name','$this->email','$this->password','$this->code')";
        return $this->runDML($query);
    }
    public function read()
    {
        
    }
    public function update()
    {
        
    }
    public function delete()
    {
        
    }

    public function checkCode(){
        $query="SELECT * FROM users WHERE email='$this->email' AND code='$this->code'";
        return $this->runDQL($query);
    }
    public function changeStatus(){
        $query="UPDATE users SET status='$this->status' WHERE email='$this->email'";
        return $this->runDML($query);
    }
    public function login(){
        $query="SELECT * FROM users WHERE email='$this->email'";
        return $this->runDQL($query);
    }

     public function getUserById(){
        $query="SELECT * FROM users Where id='$this->id'";
        return $this->runDQL($query);
    }
    
}


?>