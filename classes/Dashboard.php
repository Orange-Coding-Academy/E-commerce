<?php
class Dashboard{
    private $conn;
    public function __construct($db){
        $this->conn = $db;
    }
public function getStatistics(){
    $query = "select (select count(*) from orders)as Total_Orders,
    (select coalesce(sum(total),0)from orders where payment_status = 'Paid')as Total_Revenue,
    (select count(*)from users where role = 'customer' and deleted_at is null) as Total_Customers";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);

}

public function getOrderByStatus(){
$query = "select order_status,count(*) as Total from orders group by order_status";
$stmt = $this->conn->prepare($query);
$stmt->execute();
return $stmt->fetchAll(PDO::FETCH_ASSOC);}

public function getMonthlyRevenue(){
    $query = "select year(created_at) as Year,month(created_at) as Month ,sum(total) as Revenue from orders where payment_status = 'paid'group by year(created_at) ,month(created_at)  
     order by Year desc,Month desc";
     $stmt = $this->conn->prepare($query);
     $stmt->execute();
     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotaltProduct(){
        $query = "select count(*) as TotalProduct from products where deleted_at is null";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }
      public function getTotaltCategories(){
        $query = "select count(*) as TotalCategory from categories where deleted_at is null";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

}
?>