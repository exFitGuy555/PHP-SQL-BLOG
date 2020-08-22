<?php
  class Users extends Controller {
    public function __construct(){
      $this->userModel = $this->model('User');
    }

    public function register(){
      // Check for POST
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
       
        //Senitized POST data

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING); //convert the array data to string


      // Process form
      $data = [
        'name' => trim($_POST['name']),
        'email' => trim($_POST['email']),
        'password' => trim($_POST['password']),
        'confirm_password' => trim($_POST['confirm_password']),
        'name_err' => '',
        'email_err' => '',
        'password_err' => '',
        'confirm_password_err' => ''
      ];

      //Validate Email
      if(empty($data['email'])){
       $data['email_err'] = 'please Enter Email';
      } else {
        //check if exists
        if($this->userModel->findUserByEmail($data['email'])) {
          $data['email_err'] = 'Email allready Exists';
        }
      }
      //Validate Email
      if(empty($data['name'])){
       $data['name_err'] = 'please Enter Name';
      } 
      //Validate Email
      if(empty($data['password'])){
       $data['password_err'] = 'please Enter Password';
      } elseif (strlen($data['password'])<6){
        $data['password_err'] = 'Password must be at least 6 characters';
      }

      //Validate Confirm
      if (empty($data['confirm_password'])) {
        $data['confirm_password_err'] = 'please Confirm Password';
      } else { 
         if($data['password'] != $data['confirm_password']){
          $data['confirm_password_err'] = 'Password do no match';

         }
      }

      //Make sure Errors are empty
      if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
        //Validated
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);



        //Register User
        if($this->userModel->register($data)){
          flash('register_success', 'Account registered, Please Login');
          redirect('users/login');
        } else {
          die('something went wrong');
        }
      



      } else {

        //load view with erros

        $this->view('users/register', $data);

      }


      } else {
        // Init data
        $data =[
          'name' => '',
          'email' => '',
          'password' => '',
          'confirm_password' => '',
          'name_err' => '',
          'email_err' => '',
          'password_err' => '',
          'confirm_password_err' => ''
        ];



        // Load view
        $this->view('users/register', $data);
      }
    }

  public function login()
  {
    // Check for POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Process form
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING); //convert the array data to string


      // Process form
      $data = [
        'email' => trim($_POST['email']),
        'password' => trim($_POST['password']),
        'email_err' => '',
        'password_err' => '',
      ];

      //Validate Email
      if (empty($data['email'])) {
        $data['email_err'] = 'please Enter Email';
      } 
      //Validate Password
      if (empty($data['password'])) {
        $data['password_err'] = 'please Enter Password';
      }

      //Check for email/user 
      if($this->userModel->findUserByEmail($data['email'])){
        //User Found
      } else {
        $data['email_err'] = 'No User Found';
      }

      //Make sure Errors are empty
      if (empty($data['email_err'])  && empty($data['password_err'])) {
        //Validated
       //Check and set logged in user 
        $loggedInUser = $this->userModel->login($data['email'], $data['password']);
        if($loggedInUser){
        $this->createUserSession($loggedInUser);
        } else {
        $data['password_err'] = 'password incorrect';

        $this->view('users/login', $data);

      }
    } else {

        //load view with erros
       $this->view('users/login', $data);
       
      }




    } else {
      // Init data
      $data = [
        'email' => '',
        'password' => '',
        'email_err' => '',
        'password_err' => '',
      ];

      // Load view
      $this->view('users/login', $data);
    }
  }


  //user is actually the row we pulled from the database
  //setting the SESSIONS Variables
  public function createUserSession($user){
    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_email'] = $user->email;
    $_SESSION['user_name'] = $user->name;
    $_SESSION['userloggedin'] = true;
    redirect('posts');
  }

  public function logout(){
    unset($_SESSION['user_id']);
    unset($_SESSION['user_email']) ;
    unset($_SESSION['user_name']);
    $_SESSION['userloggedin'] = true;

    session_destroy();
    $data = [
      'email' => '',
      'password' => '',
      'email_err' => '',
      'password_err' => '',
    ];

    $alert = flash('register_success', 'User Logged Out, Please Login', 'alert-danger');
    $this->view('users/login',$data);
  }




  }