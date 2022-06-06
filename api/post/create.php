<?php
// Headers

header('Access-Control-Allow-Origin: *');
header('Content-Type: text/plain');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Products.php';


// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate blog product object
$product = new Products($db);

// Get raw posted data
$data = file_get_contents("php://input");

if (isset($data) && !empty($data)) {
    $data = json_decode($data);

    $product->sku = $data->sku;
    $product->name = $data->name;
    $product->price = $data->price;
    $product->type = $data->type;
    $product->size = $data->size;
    // Create product

    if ($product->create()) {
        echo json_encode(
            array('message' => 'Product added')
        );
    } else {
        echo json_encode(
            array('message' => 'Product not added')
        );
    }
}