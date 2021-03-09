<?php
 
 

class buscarPaciente extends conection{
    public function pacientes($idMedico, $parametros)
    {

   
        try{

            $conection = new conection();
            $mysqli = $conection->conections();
             
            $edad= self:: edad($parametros->edad);
            $genero= self:: genero($parametros->genero);
            $datos = "%" . mb_strtoupper( $parametros->nombre, 'UTF-8') . "%";
            $sql = "SELECT id_paciente,   nombre, genero, fecha_nacimiento, edad 
                    FROM vw_paciente 
                    WHERE id_medico= ?
                    $edad $genero

                    and nombre like ?
                    ";
 
 //return $sql;
            $result_array = array();
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("is",$idMedico,$datos);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result-> num_rows > 0) {
                while ($row = $result->fetch_assoc()) {

                    array_push($result_array, $row);
                }
                mysqli_close($mysqli);
                return safe_json_encode($result_array, $options = 0, $depth = 512, $utfErrorFlag = false);
            } else {
                mysqli_close($mysqli);
                return "vacio";
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



    
    private function edad ($edad){

      try{
        $mayorQue = strpos($edad, ">");
        $menorQue = strpos($edad, "<");
        $rango = strpos($edad, "-");
        if( $mayorQue !== false){

            $edad= str_replace(">", "",$edad);
            if(is_numeric($edad)){
                return  " and edad > $edad" ;
            }else{
                return "";
            }

     
        }else if( $menorQue !== false){
            $edad= str_replace("<", "",$edad);



            if(is_numeric($edad)){
                return  " and edad < $edad" ;
            }else{
                return "";
            }
            
          }else if( $rango !== false){

           $edades= explode("-", $edad );
         
           if(count($edades)==2){
               if(is_numeric( $edades[0])  && is_numeric( $edades[1]))
            return  " and edad BETWEEN  $edades[0] and  $edades[1]" ;
           }else{
            return  "";
           }

           
            
          }else{
            if(is_numeric($edad ) && $edad>0){   
              return" and edad in($edad)";

            }else{
                return "";
            
            }
          }
      } catch(Exception $e)
      {
          return "";
      }
          

       


    }


    private function genero($genero){
        if($genero=="H" ){
            return " and genero= 'HOMBRE' ";
        }else if( $genero =="M"){
            return " and genero= 'MUJER' ";
        }else{
            return "";
        }
    }
}