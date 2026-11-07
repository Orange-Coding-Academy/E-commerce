<?php
class Cart {
    private $conn;

    public function  __construct($db){
        $this->conn = $db;}

    public function addToCart($user_id,$product_id,$quantity =1 ){
        $checkProduct = "select id, stock from products where id =? and deleted_at is null";
        $stmt = $this->conn->prepare($checkProduct);
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$product){
            return "Product not found";
        }
        if($quantity < 1){
            return "Invalid quantity";
        }
        if($quantity > $product['stock']){
            return "Not enough stock";
        }

        $checkCart = "select id ,quantity from cart_items 
        where user_id =? and product_id =?";

        $stmt = $this->conn->prepare($checkCart);
        $stmt->execute([$user_id,$product_id]);
        $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);

        if($cartItem){
            $newQuanity = $cartItem['quantity'] + $quantity;
            if($newQuanity > $product['stock']){
                return "Not enough stock";
            }

            $query = "update cart_items set quantity =? where id =?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                $newQuanity,
                $cartItem['id']
            ]);

        }

        else 
            {
                $query = "insert into cart_items (user_id,product_id,quantity) values (?,?,?)";
                $stmt = $this->conn->prepare($query);
                return $stmt->execute([$user_id,$product_id,$quantity]);
            }
    }

   public function getcart($user_id){

       $query = "select cart_items.id,cart_items.product_id,cart_items.quantity,products.name,products.price,products.image,(cart_items.quantity * products.price) as subtotal
            from cart_items
            inner join products on cart_items.product_id = products.id
            where cart_items.user_id = ? and products.deleted_at is null
            order by cart_items.id desc";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([$user_id]);
              return $stmt->fetchAll(PDO::FETCH_ASSOC);
   }

public function updateCart($user_id,$product_id,$quantity){

    if($quantity < 1){
        return "Invalid quantity";
    }

    $query = "select stock from products where id =? and deleted_at is null";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$product){
        return "Product not found";
    }

    if($quantity > $product['stock']){
        return "Not enough stock";
    }

    $sql = "select id from cart_items where user_id =? and product_id =?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$user_id,$product_id]);

    if(!$stmt->fetch()){
        return "Cart item not found";
    }

    $sql = "update cart_items set quantity =? where user_id =? and product_id =?";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([$quantity,$user_id,$product_id]);
}

   public function deleteItem($user_id,$product_id){
    $sql = "delete from cart_items where user_id =? and product_id =?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$user_id,$product_id]);
   return $stmt->rowCount() > 0;
   }

public function cartTotal($user_id){
    $sql = "select sum(cart_items.quantity * products.price) as total from cart_items inner join products on cart_items.product_id = products.id 
    where cart_items.user_id =? and products.deleted_at is null";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$user_id]);
    $total = $stmt->fetch(PDO::FETCH_ASSOC);
    return $total['total'] ?? 0;
}












}











?>