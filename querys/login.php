<?php
 
 require_once '../vendor2/autoload.php';
 require_once '../auth.php';


class login extends conection{

    public function validarLogin($array)
    {

   
        try{

            $conection = new login();
            $mysqli = $conection->conections();
            $usuario=$array->usuario;
            $password=$array->password;
 
            $sql    = "SELECT * FROM  vw_medico   where cve_usuario = '$usuario'";
            $result = $mysqli->query($sql);
            
            $idMedico =0;
            $idUsuario=0;
            $passEncriptado="";
            $nombre="";
            $diasVigentes = -1;
            if ($result->num_rows == 1) {
            
                while ($row = $result->fetch_assoc()) {
                    $idMedico    = "{$row['id_medico']}";
                    $idUsuario       = "{$row['id_usuario']}";
                    $passEncriptado         = "{$row['password']}";
                    $nombre    = "{$row['nombre']}";
                    $diasVigentes = "{$row['dias_vigencia']}"; 
        
                }

                $validaPassword=$conection->validaPassword($password, $passEncriptado);
                if (strcmp($validaPassword, 1) === 0) {
                    if($diasVigentes > -1){

                    $GeneraToken= new Auth();
                    $ip    =  $conection-> buscarIp();
                    $tokenIp= $GeneraToken->SignIn(['ip' => $ip,'idMedico' => $idMedico,'idUsuario' => $idUsuario,'fechaAcceso'=>date("Y-n-j H:i:s")]);
                    
                        $response = array(
                            "estatus" => "ok",
                            "mensaje" => 'ok' ,
                            "token" =>  $tokenIp ,
                            "nombre"=>$nombre                            
                        );
                        return safe_json_encode($response );

                    }else{
                        $response = array(
                            "estatus" => "ok",
                            "mensaje" => 'sinVigencia' 
                            
                        );
                        return safe_json_encode($response );
                    }
                
                }else{
                    $response = array(
                        "estatus" => "ok",
                        "mensaje" => 'usuarioInvalido'
                        
                    );
                    return safe_json_encode($response );
                }



            }else{
                $response = array(
                    "estatus" => "ok",
                    "mensaje" => 'usuarioInvalido' 
                    
                );
              
                mysqli_close($mysqli);
                return safe_json_encode($response );
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