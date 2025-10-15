<?php
include_once __DIR__ .'/../database/config.php';
include_once __DIR__ .'/../database/operations';

class Post extends config implements Operations{

    private $id,$title,$body,$user_id,$created_at,$updated_at;
    
    

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
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     */
    public function setTitle($title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of body
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set the value of body
     */
    public function setBody($body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get the value of user_id
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set the value of user_id
     */
    public function setUserId($user_id): self
    {
        $this->user_id = $user_id;

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

    /**
     * Get the value of updated_at
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set the value of updated_at
     */
    public function setUpdatedAt($updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function create()
    {
        $query="INSERT INTO posts (title,body,user_id) VALUES('$this->title','$this->body','$this->user_id')";
        return $this->runDML($query);
    }
    public function read()
    {
        $query="SELECT * FROM posts WHERE user_id='$this->user_id'";
        return $this->runDQL($query);   
    }
    public function update()
    {
        $query="UPDATE posts SET title='$this->title' , body='$this->body' WHERE id = '$this->id'";
        return $this->runDML($query);
    }
    public function delete()
    {
        $query="DELETE FROM posts WHERE id='$this->id'";
        return $this->runDML($query);
    }
    public function getAllPosts()
    {
        $query="SELECT * FROM posts";
        return $this->runDQL($query);
    }

}





?>