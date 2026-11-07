
<?php
class Users {
    private $conn;
    private $usersTable = "users";

    public function __construct($db){
        $this->conn = $db;
    }

    public function getusers(){
        $query = "select * from users  where role = 'customer' and deleted_at IS NULL order by id desc";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
          return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public function getUserByID($id){

$query = "select * from users where id = ? and deleted_at is null";
$stmt =$this->conn->prepare($query);
$stmt->execute([$id]);

return $stmt->fetch(PDO::FETCH_ASSOC);

}



public function updateUser($id,$name,$email,$phone,$address){
    $query = "update users set name =? ,email =?,phone =?,address=?
    where id =? and deleted_at is null";
    $stmt = $this->conn->prepare($query);
    return $stmt->execute([
        $name,$email,$phone,$address,$id
    ]);
}

public function deleteUser($id){
    $query = "update users set deleted_at = now() where id = ? and deleted_at is null";
    $stmt = $this->conn->prepare($query);
    return $stmt->execute([$id]);
}






















}



















?>