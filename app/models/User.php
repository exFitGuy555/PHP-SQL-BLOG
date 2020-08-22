<?php

class User {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    //Register User 
    public function register($data){
        $this->db->query('INSERT INTO users (name, `email`, password) VALUES(:name, :email, :password)');

        //bind our values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']); //hash password

        //execute
        if($this->db->execute()){
   return true;
        } else {
            return false;
        }

    }
    

    //login 
    public function login($email, $password){
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);

        $row =$this->db->single();
        $hashed_Password = $row->password; //the hashed password coming from db 

        //match the passwords !
    if(password_verify($password, $hashed_Password)){
     return $row;
    } else {
        return false;
    }
}



    //Find User by email 

    //email come from the controller
    public function findUserByEmail($email){
        $this->db->query("SELECT * FROM users WHERE email = :email");

        $this->db->bind(':email', $email);

        $row = $this->db->single();

        if($this->db->rowCount() > 0) {
           return true;
        } else {
            return false;
        }
    }

    public function getUserById($id){
        $this->db->query("SELECT * FROM users WHERE id = :id");

        $this->db->bind(':id', $id);

        $row = $this->db->single();

       return $row;

}
}