<?php
require_once "config/connection.php";
include 'controller/order_controller.php';
global $db;

$main = new Main();

if(!empty($_POST) && $_POST['action'] != 'delete') {
	$come_from = 'add';
	if($_POST['action'] == 'edit') {
		$order['order_id'] = mysqli_real_escape_string($db, $_POST['order_id']); 
		$come_from = 'edit';
	}
    $order['product_id'] = mysqli_real_escape_string($db, $_POST['product_id']);
    $order['customer_id'] = mysqli_real_escape_string($db, $_POST['customer_id']);

    $main->add_update_order($order, $come_from);

} else if(!empty($_POST) && $_POST['action'] == 'delete') {
	$order_id = mysqli_real_escape_string($db, $_POST['order_id']); 
    
    $main->delete_order($order_id);

} else if(!empty($_GET) && $_GET['order_id'] != "") {
	$order_id = mysqli_real_escape_string($db, $_GET['order_id']);

	$main->show_order($order_id);

} else {
	$response['response_code'] = 400;
	$response['response_desc'] = "Something wrong with URL.";
	
	$json_response = json_encode($response);
	echo $json_response;
	exit;
}

?>