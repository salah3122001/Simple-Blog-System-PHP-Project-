<?php
include_once __DIR__ .'/../database/config.php';
include_once __DIR__ .'/../database/operations';

class Comment extends config implements operations{

    private $id,$body,$user_id,$post_id,$created_at;
    
    

   

    


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
     * Get the value of post_id
     */
    public function getPostId()
    {
        return $this->post_id;
    }

    /**
     * Set the value of post_id
     */
    public function setPostId($post_id): self
    {
        $this->post_id = $post_id;

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
        $query="INSERT INTO comments (body,user_id,post_id) VALUES('$this->body','$this->user_id','$this->post_id')";
        return $this->runDML($query);
    }
    public function read()
    {
        $query="SELECT * FROM comments WHERE user_id='$this->user_id' AND post_id='$this->post_id'";
        return $this->runDQL($query);
    }
    public function update()
    {
        $query="UPDATE comments SET body='$this->body' WHERE id='$this->id'";
        return $this->runDML($query);
    }
    public function delete()
    {
        $query="DELETE FROM comments WHERE id='$this->id'";
        return $this->runDML($query);
    }
    public function getAllComments(){
        $query="SELECT * From comments Where post_id='$this->post_id'";
        return $this->runDQL($query);
    }
    
}





?>