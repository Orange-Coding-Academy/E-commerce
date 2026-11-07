<?php
class Product {
    private $conn ;
    private $productTable = "products";
    public function __construct($db){
        $this->conn = $db;
    }
    
    public function getAllProduct(){
        $query = "select products.* ,categories.name as Category_name from products left join categories 
        on products.category_id = categories.id    WHERE products.deleted_at IS NULL order by products.id desc";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getProductByID($id){
        $query = "select * from products where id = ? and deleted_at is null";
        $stmt= $this->conn->prepare($query);
        $stmt->execute([$id]);
         return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function addNewProduct ($name,$description,$price,$stock,$category_id,$image){
        $query = "insert into products (name, description, price, stock, category_id, image)
        VALUES(?,?,?,?,?,?)";
        $stmt = $this->conn->prepare($query);
              return $stmt->execute([
            $name,
            $description,
            $price,
            $stock,
            $category_id,
            $image
        ]);
    }

    public function updateProduct($id,$name,$description,$price,$stock,$category_id,$image){
      $query = "UPDATE products SET name = ?,description = ?,price = ?,stock = ?,category_id = ?, image = ? where id = ? and deleted_at is null";
        $stmt = $this->conn->prepare($query);
          return $stmt->execute([
            $name,
            $description,
            $price,
            $stock,
            $category_id,
            $image,
            $id
        ]);
    }

    public function deleteProduct($id){
        $query = "update products set deleted_at = now() where id = ? and deleted_at is null";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function search($key){
        $query = "select * from products where name LIKE ? and deleted_at is null ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(["%" . $key . "%"]);
         return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function filterProducts($search, $category_id, $min_price, $max_price){

    $query = "SELECT products.*, categories.name AS Category_name
              FROM products
              LEFT JOIN categories ON products.category_id = categories.id
              WHERE products.deleted_at IS NULL";

    $params = [];

  
    if (!empty($search)) {
        $query .= " AND products.name LIKE ?";
        $params[] = "%" . $search . "%";
    }

    if (!empty($category_id)) {
        $query .= " AND products.category_id = ?";
        $params[] = $category_id;
    }

   
    if ($min_price !== '' && is_numeric($min_price)) {
        $query .= " AND products.price >= ?";
        $params[] = $min_price;
    }

   
    if ($max_price !== '' && is_numeric($max_price)) {
        $query .= " AND products.price <= ?";
        $params[] = $max_price;
    }



    $query .= " ORDER BY products.id DESC";

    $stmt = $this->conn->prepare($query);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



















}











?>