<?php 

//extends Controller to be able to use model view etc
class Posts extends Controller{ 
    public function __construct(){
          if(!isset($_SESSION['userloggedin'])){
                redirect('users/login');
          }

          $this->postModel = $this->model('Post');
          $this->userModel = $this->model('User');
    }
      public function index() {
         $data = [];
         //Get Posts
         $posts = $this->postModel->getPosts();

         $data = [
               'posts' => $posts
         ];

          $this->view('posts/index', $data);
      }

      public function add(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                  //SENITIZE the post 

                  $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);

                  $data = [
                        'title' => trim($_POST['title']),
                        'body' => trim($_POST['body']),
                        'user_id' => $_SESSION['user_id'],
                        'title_err' => '',
                        'body_err' => '',

                  ];

                  //validate title 
                  if(empty($data['title'])){
                        $data['title_err'] = 'please Enter Title';
                  }
 

                  //validate title 
                  if(empty($data['body'])){
                        $data['body_err'] = 'please Enter Body text';
                  }

                  //make sure there is no errors

                  if(empty($data['title_err']) && empty($data['body_err'])){ 
                      //validatedz
                      if($this->postModel->addPost($data)){
                            flash('post_message', 'Post added successfully');
                            redirect('posts');
                      } else {
                          
                        die('something went wrong');
                      }
                  } else {
                        //load view with erros
                        $this->view('posts/add', $data);
                  }
 
            } else {

                  $data = [
                        'title' => '',
                        'body' => ''
                  ];

                  $this->view('posts/add', $data);
            }
           
      }

      public function edit($id)
      {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                  //SENITIZE the post 

                  $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                  $data = [
                        'id' => $id,
                        'title' => trim($_POST['title']),
                        'body' => trim($_POST['body']),
                        'user_id' => $_SESSION['user_id'],
                        'title_err' => '',
                        'body_err' => '',
                  ];

                  //validate title 
                  if (empty($data['title'])) {
                        $data['title_err'] = 'please Enter Title';
                  }


                  //validate title 
                  if (empty($data['body'])) {
                        $data['body_err'] = 'please Enter Body text';
                  }

                  //make sure there is no errors

                  if (empty($data['title_err']) && empty($data['body_err'])) {
                        //validatedz
                        if ($this->postModel->updatePost($data)) {
                              flash('post_message', 'Post Updated successfully');
                              redirect('posts');
                        } else {

                              die('something went wrong');
                        }
                  } else {
                        //load view with erros
                        $this->view('posts/edit', $data);
                  }
            } else {
                   //get existing post from model 
                  $post = $this->postModel->getPostById($id);
                  //check for owner 
                  if($post->user_id != $_SESSION['user_id']){
                        redirect('posts ');
                  }

                  $data = [
                        
                        'id'=> $id,
                        'title' => $post->title,
                        'body' => $post->body,
                  ];

                  $this->view('posts/edit', $data);
            }
      }

      public function show($id){

            //equal the recived post row
            $post = $this->postModel->getPostById($id);
            //remember that we join our tables into posts table so we can access user_id
            $user =$this->userModel->getUserById($post->user_id);
            //$user will be equal to the recived row from the db 
            $data =[
                  'post' => $post,
                  'user' => $user,
            ];

          $this->view('posts/show', $data);


      }

      public function delete($id){

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                  //get existing post from model \\ fethcing the post
                  $post = $this->postModel->getPostById($id);
                  //check for owner before doing anyting
                  if ($post->user_id != $_SESSION['user_id']) {
                        redirect('posts ');
                  }


                  if($this->postModel->deletePost($id)){
                        flash('post_message', 'Post Removed');
                        redirect('posts');
                  } else {
                        die('Something Went Wrong!');
                  }
            } else {
                  redirect('posts');
            }
      }
}