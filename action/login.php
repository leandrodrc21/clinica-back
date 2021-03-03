<?php
include "../conection/conection.php";
include "funciones.php";
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Access-Control-Allow-Methods:  POST');


if ($_SERVER['REQUEST_METHOD'] === 'POST')
{

  
    require_once ("../querys/login.php");
    $conection = new conection();
    $mysqli = $conection->conections();


 
$json = file_get_contents('php://input');

// decode the json data
 
$array = json_decode( $json );

$action= test_input(mysqli_real_escape_string($mysqli, $array->action));

switch ($action)
{
    case "login":
        mysqli_close($mysqli);
        $login = new login();
        echo $login->validarLogin($array);

    break;
    default:
    $response = array(
        "estatus" => "denegado",       
       
    );
  
    echo safe_json_encode($response );
    break;
}



}