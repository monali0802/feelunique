<?php

header("Content-Type:application/json");
if (isset($_GET['order_id']) && $_GET['order_id']!="") {
	$order_id = $_GET['order_id'];
	if (file_exists('order.xml')) {
	    $xml = simplexml_load_file('order.xml');
	    $orders = (array) $xml;
	    if(!empty($orders['order'])) {
	    	foreach ($orders['order'] as $order) {
	    		if($order->id == $order_id) {
	    			response($order, 200,"Data load successfully");
	    		}
	    	}
	    	response(NULL, 400,"No Data Found");
	    }
	} else {
	    response(NULL, 400,"No file exists");
	}
} else {
	response(NULL, 400,"Invalid Request");
}
function response($order,$response_code,$response_desc){
	$response['order'] = $order;
	$response['response_code'] = $response_code;
	$response['response_desc'] = $response_desc;
	
	$json_response = json_encode($response);
	echo $json_response;
	exit;
}
?>