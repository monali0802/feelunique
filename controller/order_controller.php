<?php
require_once "config/connection.php";
class Main {
    public function show_order($order_id) {
        global $db;
        $sql = "SELECT * FROM orders WHERE id = '$order_id' and status = 1";
        if($result = mysqli_query($db, $sql)) {
            if(mysqli_num_rows($result) > 0) {
                $order_data = [];
                while($row = mysqli_fetch_array($result)) {
                    $product_detail= [];
                    if(!empty($row['product_id'])) {
                        $products = explode(',', $row['product_id']);
                        foreach($products as $product) {
                            $sql = "SELECT * FROM products WHERE id = '$product'";
                            if($result = mysqli_query($db, $sql)) {
                                if(mysqli_num_rows($result) > 0) {
                                    $row_product = mysqli_fetch_assoc($result);
                                    $product_detail[] = $row_product;
                                }
                            }
                        }    
                    }
                    $order_data['id'] = $row['id'];
                    $order_data['product_detail'] = $product_detail;
                    $order_data['currency'] = $row['currency'];
                    $order_data['date'] = $row['date'];
                    $order_data['customer_id'] = $row['customer_id'];
                    $order_data['total'] = $row['price'];
                }
                $this->response($order_data, 200,"Get data from order_id");
            } else {
                $this->response(NULL, 200,"No record found");
            }
        } else {
            $this->response(NULL, 400,"Oops! Something went wrong. Please try again later.");
        }
    }

    public function delete_order($order_id) {
        global $db;
        $sql = "SELECT * FROM orders WHERE id = '$order_id' and status = 1";
        if($result = mysqli_query($db, $sql)) {
            if(mysqli_num_rows($result) > 0) {
                $sql = "UPDATE orders
                  SET status = 0
                  WHERE id = '$order_id' ";
                if ($db->query($sql) === TRUE) {
                    $this->response(NULL, 200,"Record deleted successfully");
                } else {
                    $this->response($db->error, 400,"Error deleting record");
                }
            } else {
                $this->response(NULL, 200,"Order not found");
            }
        } else {
            $this->response(NULL, 400,"Oops! Something went wrong. Please try again later.");
        }
    }

    public function add_update_order($order, $come_from) {
        global $db;
        $product_id = $order["product_id"];
        $customer_id = (int) $order["customer_id"];
        $date = date('Y-m-d');

        if($come_from == 'edit') {
            $order_id = $order["order_id"];
            $sql = "SELECT * FROM orders WHERE id = '$order_id' and status = 1";
            if($result = mysqli_query($db, $sql)) {
                if(mysqli_num_rows($result) == 0) {
                    $this->response(NULL, 200,"Order not found");
                }
            } else {
                $this->response(NULL, 400,"Oops! Something went wrong. Please try again later.");
            }
        }
        $products = explode(',', $product_id);
        $total_price = 0;
        $new_id = [];
        foreach($products as $product) {
            $sql = "SELECT * FROM products WHERE id = '$product'";
            if($result = mysqli_query($db, $sql)) {
                if(mysqli_num_rows($result) > 0) {
                    $row_product = mysqli_fetch_assoc($result);
                    $total_price += $row_product['price'];
                    $new_id[] = $row_product['id'];
                }
            }
        } 

        if(empty($new_id)) {
            $this->response(NULL, 400,"Product not found ");
        }
        $new_product_id = implode(',', $new_id);   
        if($come_from == 'edit') {
            $sql = "UPDATE orders
                  SET product_id = '$new_product_id', customer_id = '$customer_id', price = '$total_price', date = '$date'
                  WHERE id = '$order_id' ";
            if($db->query($sql) === TRUE) {
                $this->response($order, 200,"Record updated successfully");
            } else {
                $this->response($db->error, 400,"Error updating record");
            }
        } else {
            $sql = "INSERT INTO orders (customer_id, product_id, price, date, currency) VALUES ('$customer_id', '$new_product_id', '$total_price', '$date', 'GBP')";  
            if(mysqli_query($db, $sql)) {
                $last_id = $db->insert_id;
                $this->response($last_id, 200,"Record inserted successfully");
            } else {
                $this->response($db->error, 400,"Error inserting record");
            }
        }
    }

    public function response($order,$response_code,$response_desc){
      $response['order'] = $order;
      $response['response_code'] = $response_code;
      $response['response_desc'] = $response_desc;
      
      $json_response = json_encode($response);
      echo $json_response;
      exit;
    }
}


?>