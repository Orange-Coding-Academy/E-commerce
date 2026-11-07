<?php
class Auth {
    private $conn;
    private $userTable = "users";
      private function startSession(){
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
}
    public function __construct($db){
        $this->conn = $db;
    }

    public function Register($name,$email,$password,$phone,$address){
        $userExsist = "select id from users where email = ? and deleted_at is null";
        $stmt = $this->conn->prepare($userExsist);
        $stmt->execute([$email]);

        if($stmt->rowCount() >0){
         return false;
        }
        $hashPass = password_hash($password,PASSWORD_DEFAULT);

        $query = "insert into users  (name, email, password, phone, address)
                  VALUES (?, ?, ?, ?, ?)";


        $stmt1 = $this->conn->prepare($query);
        return $stmt1->execute([$name,$email,$hashPass,$phone,$address]);
        }


        public function Login($email,$password){
             $this->startSession();
            $query = "select * from users where email =? and deleted_at is null";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$email]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if($user && password_verify($password,$user['password'])){
            
              session_regenerate_id(true);
              $_SESSION['user_id'] = $user['id'];
              $_SESSION['user_name'] = $user['name'];
              $_SESSION['user_email'] = $user['email'];
              $_SESSION['user_role'] = $user['role'];
              
                unset($user['password']);

              return ["success" => true,
                      "user" => $user];
              }
              return ["success" => false,
                   "message" => "Invalid Email or Password"];

        }


     public function Logout(){
           $this->startSession();
         $_SESSION = [];
         session_destroy();
         return true;
     }




 public function Profile(){
       $this->startSession();
    if(!isset($_SESSION['user_id'])){
        return false;}
   $query = "select id, name, email, phone, address, role from users where id = ? and deleted_at is null";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function isLoggedIn(){
    $this->startSession();
return isset($_SESSION['user_id']);}


public function isAdmin(){
    $this->startSession();
    return isset($_SESSION['user_id']) &&
     isset($_SESSION['user_role']) &&
     $_SESSION['user_role'] === 'admin';
}








}


















?>