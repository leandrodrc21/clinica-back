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
    require_once ("../querys/nuevoPaciente.php");
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
        "estatus" => "denegado" 
    );
  
    echo safe_json_encode($response );
}else{
    $arr = explode(" ", $authHeader);
    $jwt = $arr[1];
    $action= test_input(mysqli_real_escape_string($mysqli, $array->action));
    switch ($action)
    {
        case "estados":
            mysqli_close($mysqli);
            $validaSesion = new validaSesion();
            $datosToken= $validaSesion->validaToken($jwt);
            if($datosToken == "denegado"){
                $response = array(
                    "estatus" => "denegado"
                );
              
                echo safe_json_encode($response );
            }else{
                $ip= $datosToken->ip;
                $fechaAcceso=$datosToken->fechaAcceso;
                $idMedico=$datosToken->idMedico;
                $idUsuario=$datosToken->idUsuario;
                
                if( $validaSesion->compararIp($ip) == "ok"){
                    if($validaSesion->validaTiempo($fechaAcceso)=="ok"){
                        $valor=test_input(  $array->valor);
                        $nuevoPaciente = new nuevoPaciente();
                        $response = array(
                            "estatus" => "ok", 
                            "datos"=>$nuevoPaciente->estados( $valor)
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
                        "estatus" => "denegado"
                    );
                  
                    echo safe_json_encode($response );
                }
                 
              



            }
          

    
        break;
        case "municipios":
            mysqli_close($mysqli);
            $validaSesion = new validaSesion();
            $datosToken= $validaSesion->validaToken($jwt);
            if($datosToken == "denegado"){
                $response = array(
                    "estatus" => "denegado"
                );
              
                echo safe_json_encode($response );
            }else{
                $ip= $datosToken->ip;
                $fechaAcceso=$datosToken->fechaAcceso;
                $idMedico=$datosToken->idMedico;
                $idUsuario=$datosToken->idUsuario;
                
                if( $validaSesion->compararIp($ip) == "ok"){
                    if($validaSesion->validaTiempo($fechaAcceso)=="ok"){
                        $valor=test_input(  $array->valor);
                        $idEstado=test_input(  $array->idEstado);
                        $nuevoPaciente = new nuevoPaciente();
                        $response = array(
                            "estatus" => "ok", 
                            "datos"=>$nuevoPaciente->municipios( $idEstado,$valor)
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
                        "estatus" => "denegado"
                    );
                  
                    echo safe_json_encode($response );
                }
                 
              



            }
          

    
        break;
        case "localidad":
            mysqli_close($mysqli);
            $validaSesion = new validaSesion();
            $datosToken= $validaSesion->validaToken($jwt);
            if($datosToken == "denegado"){
                $response = array(
                    "estatus" => "denegado"
                );
              
                echo safe_json_encode($response );
            }else{
                $ip= $datosToken->ip;
                $fechaAcceso=$datosToken->fechaAcceso;
                $idMedico=$datosToken->idMedico;
                $idUsuario=$datosToken->idUsuario;
                
                if( $validaSesion->compararIp($ip) == "ok"){
                    if($validaSesion->validaTiempo($fechaAcceso)=="ok"){
                        $valor=test_input(  $array->valor);
                        $idEstado=test_input(  $array->idEstado);
                        $idMunicipio=test_input(  $array->idMunicipio);
                        $nuevoPaciente = new nuevoPaciente();
                        $response = array(
                            "estatus" => "ok", 
                            "datos"=>$nuevoPaciente->localidad( $idEstado, $idMunicipio,$valor)
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
                        "estatus" => "denegado"
                    );
                  
                    echo safe_json_encode($response );
                }
                 
              



            }
          

    
        break;
        case "codigoPostal":
            mysqli_close($mysqli);
            $validaSesion = new validaSesion();
            $datosToken= $validaSesion->validaToken($jwt);
            if($datosToken == "denegado"){
                $response = array(
                    "estatus" => "denegado"
                );
              
                echo safe_json_encode($response );
            }else{
                $ip= $datosToken->ip;
                $fechaAcceso=$datosToken->fechaAcceso;
                $idMedico=$datosToken->idMedico;
                $idUsuario=$datosToken->idUsuario;
                
                if( $validaSesion->compararIp($ip) == "ok"){
                    if($validaSesion->validaTiempo($fechaAcceso)=="ok"){
                        $cp=test_input(  $array->cp);
                         
                        $nuevoPaciente = new nuevoPaciente();
                        $response = array(
                            "estatus" => "ok", 
                            "datos"=>$nuevoPaciente->codigoPostal( $cp )
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
                        "estatus" => "denegado"
                    );
                  
                    echo safe_json_encode($response );
                }
                 
              



            }
          

    
        break;
        case "localidadCP":
            mysqli_close($mysqli);
            $validaSesion = new validaSesion();
            $datosToken= $validaSesion->validaToken($jwt);
            if($datosToken == "denegado"){
                $response = array(
                    "estatus" => "denegado"
                );
              
                echo safe_json_encode($response );
            }else{
                $ip= $datosToken->ip;
                $fechaAcceso=$datosToken->fechaAcceso;
                $idMedico=$datosToken->idMedico;
                $idUsuario=$datosToken->idUsuario;
                
                if( $validaSesion->compararIp($ip) == "ok"){
                    if($validaSesion->validaTiempo($fechaAcceso)=="ok"){
                        $cp=test_input(  $array->cp);
                         
                        $nuevoPaciente = new nuevoPaciente();
                        $response = array(
                            "estatus" => "ok", 
                            "datos"=>$nuevoPaciente->localidadCP( $cp )
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
                        "estatus" => "denegado"
                    );
                  
                    echo safe_json_encode($response );
                }
                 
              



            }
          

    
        break;
        case "guardar":
            mysqli_close($mysqli);
            $validaSesion = new validaSesion();
            $datosToken= $validaSesion->validaToken($jwt);
            if($datosToken == "denegado"){
                $response = array(
                    "estatus" => "denegado"
                );
              
                echo safe_json_encode($response );
            }else{
                $ip= $datosToken->ip;
                $fechaAcceso=$datosToken->fechaAcceso;
                $idMedico=$datosToken->idMedico;
                $idUsuario=$datosToken->idUsuario;
                
                if( $validaSesion->compararIp($ip) == "ok"){
                    if($validaSesion->validaTiempo($fechaAcceso)=="ok"){
                        $cp=test_input(  $array->cp);
                         
                        $nuevoPaciente = new nuevoPaciente();
                        $response = array(
                            "estatus" => "ok", 
                            "mensaje"=>$nuevoPaciente->guardar( $idMedico, $array )
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
                        "estatus" => "denegado"
                    );
                  
                    echo safe_json_encode($response );
                }
                 
              



            }
          

    
        break;
        case "buscarPaciente":
            mysqli_close($mysqli);
            $validaSesion = new validaSesion();
            $datosToken= $validaSesion->validaToken($jwt);
            if($datosToken == "denegado"){
                $response = array(
                    "estatus" => "denegado"
                );
              
                echo safe_json_encode($response );
            }else{
                $ip= $datosToken->ip;
                $fechaAcceso=$datosToken->fechaAcceso;
                $idMedico=$datosToken->idMedico;
                $idUsuario=$datosToken->idUsuario;
                
                if( $validaSesion->compararIp($ip) == "ok"){
                    if($validaSesion->validaTiempo($fechaAcceso)=="ok"){
                        $idPaciente=test_input(  $array->idPaciente);
                         
                        $nuevoPaciente = new nuevoPaciente();
                        $response = array(
                            "estatus" => "ok", 
                            "mensaje"=>$nuevoPaciente->buscarPaciente( $idMedico, $idPaciente )
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
                        "estatus" => "denegado"
                    );
                  
                    echo safe_json_encode($response );
                }
                 
              



            }
          

    
        break;
        default:
        $response = array(
            "estatus" => "denegado"
        );
      
        echo safe_json_encode($response );
        break;
    }
}




}