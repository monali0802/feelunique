# feelunique


Change localhost connection from config->connection.php file

feelunique.sql is database export file for import database

order.xml file is xml which is given by team as example

data_load_from_xml.php file is used for display loaded data from the xml file of perticular order_id

index.php controller->order_controller.php file are used for display data, update, delete data using databse

customer_id we can use when more customers in our database for now its 1 

Below are the api and parameter for api:

Order data from order_id
url: http://localhost/feelunique/index.php?order_id=1
method: get
reponse:
{"order":{"id":"1","product_detail":[{"id":"1","title":"GHD Hair
Straighteners","price":"99.99"},{"id":"2","title":"Redken Shampure
Shampoo","price":"19.99"}],"currency":"GBP","date":"2022-04-12","customer_id":"1","total":"119.98"},"response_code":200,"response_desc":"Get
data from order_id"}



Delete order using order_id (I have not hard delete order except used status for not saw deleted order)
url: http://localhost/feelunique/index.php

method: post

body:form-data

order_id:1

action:delete

reponse:
{"order":null,"response_code":200,"response_desc":"Record deleted successfully"}


Add order
url: http://localhost/feelunique/index.php

method: post

body:form-data

product_id:3,4

customer_id:1

action:add

reponse:
{"order":3,"response_code":200,"response_desc":"Record inserted successfully"}



update order using order_id
url: http://localhost/feelunique/index.php

method: post

body:form-data

order_id:2

product_id:3,2

customer_id:1

action:edit

reponse:
{"order":{"order_id":"2","product_id":"3, 2","customer_id":"1"},"response_code":200,"response_desc":"Record updated
successfully"}
