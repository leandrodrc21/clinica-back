<?php
 
 require_once '../vendor2/autoload.php';
 require_once '../auth.php';


class validaSesion extends conection{

    public function validaToken($jwt)
    {

   
        try{

            $conection = new validaSesion();
            $mysqli = $conection->conections();
            $SignIn= new Auth();
            $validaToken= $SignIn->Check($jwt);
            if($validaToken){
                $datosToken= $SignIn->GetData($jwt);
               
                mysqli_close($mysqli);
                return $datosToken;
            }else{
                              
                mysqli_close($mysqli);
                return "denegado";

            }

            
           
 
        }
        catch(Exception $e)
        {
            // este bloque captura la excepción
            $response = array(
                "estatus" => "error",
                "mensaje" => 'Error Exception' . $e->getMessage()
                
            );
          
            mysqli_close($mysqli);
            return safe_json_encode($response );
        }
        catch(InvalidArgumentException $e)
        {

             // este bloque captura la excepción
             $response = array(
                "estatus" => "error",
                "mensaje" => "Error de argumento inválido: " . $e->getMessage()
                
            );
          
            mysqli_close($mysqli);
            return safe_json_encode($response);
           
        }
        catch(TypeError $e)
        {
         
            $response = array(
                "estatus" => "error",
                "mensaje" =>  "Error de tipo: " . $e->getMessage()
                
            );
          
            mysqli_close($mysqli);
            return safe_json_encode($response );
        }
        catch(ErrorException $e)
        {
            // este bloque no se ejecuta, no coincide el tipo de excepción
            
            $response = array(
                "estatus" => "error",
                "mensaje" =>   'Error Exception' . $e->getMessage()
                
            );
          
            mysqli_close($mysqli);
            return safe_json_encode($response );
             
        }
    }


public function  compararIp($ip){

    $ipLocal= self:: buscarIp();

    if($ip== $ipLocal){
        return "ok";
    }else{
        return "fail";

    }

    
}


public function refrescaToken( $idMedico, $idUsuario){

    $GeneraToken= new Auth();
    $ip    = self:: buscarIp();
    $token= $GeneraToken->SignIn(['ip' => $ip,'idMedico' => $idMedico,'idUsuario' => $idUsuario,'fechaAcceso'=>date("Y-n-j H:i:s")]);
    return $token;

}
public function validaTiempo($tiempoincial){
    $ahora = date("Y-n-j H:i:s");
    $tiempo_transcurrido = (strtotime($ahora) - strtotime($tiempoincial));
    if ($tiempo_transcurrido >= 1800) {
        return "finalizado";
    }else{
        return "ok";
    }
    
}

    private function validaSitio()
    {
        $direccion = $_SERVER["HTTP_HOST"];
    
        if ($direccion != "localhost") {
    
            if (strcmp($direccion, "https://mersys.com.mx") === 0) {
                return 'ip';
            } else if (strcmp($direccion, "mersys.com.mx") === 0) {
                return 'ip';
            } else if (strcmp($direccion, "www.mersys.com.mx") === 0) {
                return 'ip';
            } else {
                return 'localhost';
            }
    
        } else {
            return 'localhost';
        }
    }
 

    private function buscarIp()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } else if (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } else if (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } else if (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } else if (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $ipaddress = $_SERVER["HTTP_CLIENT_IP"];
        } else if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ipaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_X_FORWARDED"])) {
            $ipaddress = $_SERVER["HTTP_X_FORWARDED"];
        } else if (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
            $ipaddress = $_SERVER["HTTP_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_FORWARDED"])) {
            $ipaddress = $_SERVER["HTTP_FORWARDED"];
        } else {
            $ipaddress = 'inaccesible';
        }
        return $ipaddress;
    }
}