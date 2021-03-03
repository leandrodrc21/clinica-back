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

    require_once ("../querys/validaSesion.php");
    require_once ("../querys/buscarPaciente.php");
    $conection = new conection();
    $mysqli = $conection->conections();
 
 
$json = file_get_contents('php://input');

// decode the json data
 
$array = json_decode( $json );

$authHeader = "vacio";


foreach (getallheaders() as $nombre => $valor) {
    if($nombre=="Authorization"){
        $authHeader= $valor;
    }

 
    
}


if($authHeader == "vacio"){
    $response = array(
        "estatus" => "denegado vacio".$authHeader  
    );
  
    echo safe_json_encode($response );
}else{
    $arr = explode(" ", $authHeader);
    $jwt = $arr[1];
    $action= test_input(mysqli_real_escape_string($mysqli, $array->action));

switch ($action)
{
    case "buscar":
        mysqli_close($mysqli);
        $validaSesion = new validaSesion();
        $datosToken= $validaSesion->validaToken($jwt);
        if($datosToken == "denegado"){
            $response = array(
                "estatus" => "denegado-token"
            );
          
            echo safe_json_encode($response );
        }else{
            $ip= $datosToken->ip;
            $fechaAcceso=$datosToken->fechaAcceso;
            $idMedico=$datosToken->idMedico;
            $idUsuario=$datosToken->idUsuario;
             
            if( $validaSesion->compararIp($ip) == "ok"){
                if($validaSesion->validaTiempo($fechaAcceso)=="ok"){
                
                    $buscarPaciente = new buscarPaciente();
                    $response = array(
                        "estatus" => "ok", 
                        "datos"=>$buscarPaciente->pacientes( $idMedico, $array )
                    );
                  
                    echo safe_json_encode($response );
                }else{
                    $response = array(
                        "estatus" => "tiempo" 
                    );
                  
                    echo safe_json_encode($response );
                }
               
            }else{
                $response = array(
                    "estatus" => "denegado- se mano"
                );
              
                echo safe_json_encode($response );
            }
             
          



        }
      


    break;
    default:
    $response = array(
        "estatus" => "denegado-qu pedo".$action,       
        
    );
  
    echo safe_json_encode($response );
    break;
}
}




}