<?php
class Category {
    private $conn;
    private $categoryTable = "categories";

    public function __construct($db){
        $this->conn = $db;
    }

    public function getAllCategories(){
        $query = "select * from categories where categories.deleted_at is null order by id desc";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
          return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public function getCategoryByID($id){

$query = "select * from categories where id = ? and deleted_at is null";
$stmt =$this->conn->prepare($query);
$stmt->execute([$id]);

return $stmt->fetch(PDO::FETCH_ASSOC);

}


public function addNewCategory($name,$description,$image){
    $query = "insert into categories (name,description,image) values (?,?,?)";
    $stmt = $this->conn->prepare($query);
    return $stmt->execute([
        $name,$description,$image
    ]);

}

public function updateCategory($id,$name,$description,$image){
    $query = "update categories set name =? ,description =?,image =?
    where id =? and deleted_at is null";
    $stmt = $this->conn->prepare($query);
    return $stmt->execute([
        $name,$description,$image,$id
    ]);
}

public function deleteCategory($id){
    $query = "update categories set deleted_at = now() where id = ? and deleted_at is null";
    $stmt = $this->conn->prepare($query);
    return $stmt->execute([$id]);
}






















}



















?>