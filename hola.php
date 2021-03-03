<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods:  POST');
$json = file_get_contents('php://input');
 
// decode the json data
 
$array = json_decode( $json );

print $array->nombre;
class Result {}

$response = new Result();
$response->estatus = 'OK';
$response->mensaje = 'datos modificados';
echo json_encode($response);  
