<?php
class Order {
    private $conn;
    private $orderTable = "orders";
    private $orderItemsTable = "order_items";
    public function __construct($db){
        $this->conn = $db;
    }
    public function newOrder ($user_id,$delivery_address,$phone){
        $query = "select cart_items.product_id ,cart_items.quantity,products.price,products.stock,products.name from cart_items
        inner join products on cart_items.product_id = products.id where cart_items.user_id =?
        and products.deleted_at is null ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(empty($items)){
            return "Cart is empty";
        }
        $tot = 0;
        foreach($items as $item){
            if($item['quantity']> $item['stock']){
                return "Not enough stock";
            }
            if ($item['quantity'] < 1) {
                return "Invalid quantity";
            }
            $tot += $item['price']*$item['quantity'];
        }

      
      $sql = "insert into orders (user_id,total,payment_method,payment_status,order_status,delivery_address,phone) values (?,?,'COD','Unpaid','Pending',?,?)";
      
      $stmt = $this->conn->prepare($sql);
      
      $stmt->execute([$user_id,$tot,$delivery_address,$phone]);
       
      $order_id = $this->conn->lastInsertId();
     
      $sql = "insert into order_items (order_id,product_id,quantity,price)
      values(?,?,?,?)";
      $itemStmt = $this->conn->prepare($sql);
          
         
          $updateStock = "update products set stock = stock - ? where id = ? and stock >= ? and deleted_at is null";
            
           $stockStmt = $this->conn->prepare($updateStock);
           foreach ($items as $item) {

                $itemStmt->execute([
                    $order_id,
                    $item['product_id'],
                    $item['quantity'],
                    $item['price']
                ]);
                $stockStmt->execute([
            $item['quantity'],
            $item['product_id'],
            $item['quantity']
        ]);
                
                  if ($stockStmt->rowCount() !== 1) {
                return "Failed to update stock";
            }}
          $sql = "delete from cart_items where user_id = ?";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$user_id]);
            return ["success" => true,"order_id" => $order_id,"total" => $tot];

      }

    
public function getUsersOrders($user_id){
$query = "select * from orders where user_id =? order by id desc";
$stmt = $this->conn->prepare($query);
$stmt->execute([$user_id]);
return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getOrderDetails ($order_id,$user_id){
    $query = "select orders.id as Order_ID ,orders.total,orders.payment_method,orders.payment_status,orders.delivery_address,orders.phone,orders.created_at,
    order_items.product_id,order_items.quantity,order_items.price,
    products.name as ProductName,products.image as ProductImage from orders inner join order_items on orders.id = order_items.order_id left join products on order_items.product_id = products.id 
    where orders.id =? and orders.user_id =?";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([$order_id,$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    
    public function getAllOrders(){
        $sql  = "select orders.* ,users.name as CustomerName from orders inner join users on orders.user_id = users.id order by orders.id desc";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
public function updateOrderStatus ($order_id,$status){
    $orderStatus = ["Pending","Processing","Delivered","Cancelled"];
    if(!in_array($status,$orderStatus)){
        return "Invalid order status";
    }
        $query = "update orders set order_status =? where id =?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$status,$order_id]);
}
public function updatePaymentStatus($order_id,$status){
    $paymentStatus = ["Paid","Unpaid"];
    if(!(in_array($status,$paymentStatus))){
        return "Invalid payment status";}
    $sql= "update orders set payment_status =? where id =?";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([$status,$order_id]);}

public function filterOrders($search, $order_status, $payment_status){

    $query = "SELECT orders.*, users.name AS CustomerName
              FROM orders
              LEFT JOIN users ON orders.user_id = users.id
              WHERE 1=1";

    $params = [];

    if ($search !== '') {
        $query .= " AND (CAST(orders.id AS CHAR) LIKE ? OR users.name LIKE ?)";
        $params[] = "%" . $search . "%";
        $params[] = "%" . $search . "%";
    }

    $validOrderStatuses = ["Pending", "Processing", "Delivered", "Cancelled"];

    if (in_array($order_status, $validOrderStatuses)) {
        $query .= " AND orders.order_status = ?";
        $params[] = $order_status;
    }

    $validPaymentStatuses = ["Paid", "Unpaid"];

    if (in_array($payment_status, $validPaymentStatuses)) {
        $query .= " AND orders.payment_status = ?";
        $params[] = $payment_status;
    }

    $query .= " ORDER BY orders.id DESC";

    $stmt = $this->conn->prepare($query);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function getOrderDetailsForAdmin($order_id){

    $query = "SELECT
                orders.id AS Order_ID,
                orders.user_id,
                orders.total,
                orders.payment_method,
                orders.payment_status,
                orders.order_status,
                orders.delivery_address,
                orders.phone,
                orders.created_at,
                users.name AS CustomerName,
                order_items.product_id,
                order_items.quantity,
                order_items.price,
                products.name AS ProductName,
                products.image AS ProductImage
              FROM orders
              LEFT JOIN users ON orders.user_id = users.id
              INNER JOIN order_items ON orders.id = order_items.order_id
              LEFT JOIN products ON order_items.product_id = products.id
              WHERE orders.id = ?";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([$order_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}








}


?>