<?php
 
 
class nuevoPaciente  {

    public function estados($valor)
    {

   
        try{

            $conection = new conection();
            $mysqli = $conection->conections();
             
            $datos = "%" . mb_strtoupper($valor, 'UTF-8') . "%";
            $sql = "SELECT      id_estado as id ,
              nombre_estado  as text
              from estados
              WHERE UPPER(nombre_estado) LIKE ?";

            $result_array = array();
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $datos);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows >= 1) {
                while ($row = $result->fetch_assoc()) {

                    array_push($result_array, $row);
                }
            } else {
                $result_array[] = array(
                    'id' => null,
                    'text' => "No se encotraron resultados...",
                );

            }
            mysqli_close($mysqli);
            return safe_json_encode($result_array, $options = 0, $depth = 512, $utfErrorFlag = false);
           
 
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


    public function municipios($idEstado,$valor)
    {

   
        try{

            $conection = new conection();
            $mysqli = $conection->conections();
             
            $datos = "%" . mb_strtoupper($valor, 'UTF-8') . "%";
            $sql = "SELECT      id_municipio as id ,
              nombre_municipio  as text
              from municipios
              WHERE  id_estado = ?
              and UPPER(nombre_municipio) LIKE ?";

            $result_array = array();
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("is", $idEstado,$datos);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows >= 1) {
                while ($row = $result->fetch_assoc()) {

                    array_push($result_array, $row);
                }
            } else {
                $result_array[] = array(
                    'id' => null,
                    'text' => "No se encotraron resultados...",
                );

            }
            mysqli_close($mysqli);
            return safe_json_encode($result_array, $options = 0, $depth = 512, $utfErrorFlag = false);
           
 
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

    public function localidad($idEstado,$idMunicipio,$valor)
    {

   
        try{

            $conection = new conection();
            $mysqli = $conection->conections();
             
            $datos = "%" . mb_strtoupper($valor, 'UTF-8') . "%";
            $sql = "SELECT
            l.asentamiento as text,
            concat (l.id_localidad,'-',  LPAD(
                CAST(l.cp as CHAR(5)),
                5,
                '00000'
            )) as id
            
        FROM
            localidad l
        JOIN estados e ON
            e.id_estado = ?
        JOIN municipios m ON
            m.id_estado = e.id_estado AND m.id_municipio = ?
        WHERE
            l.idEstado = e.id_estado
            AND l.idMunicipio = m.cve_municipio
            AND UPPER(l.asentamiento) LIKE ?";

            $result_array = array();
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("iis", $idEstado,$idMunicipio,$datos);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows >= 1) {
                while ($row = $result->fetch_assoc()) {

                    array_push($result_array, $row);
                }
            } else {
                $result_array[] = array(
                    'id' => null,
                    'text' => "No se encotraron resultados...",
                );

            }
            mysqli_close($mysqli);
            return safe_json_encode($result_array, $options = 0, $depth = 512, $utfErrorFlag = false);
           
 
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


    public function codigoPostal($cp)
    {

   
        try{

            $conection = new conection();
            $mysqli = $conection->conections();
             
            $datos = "%" . mb_strtoupper($cp, 'UTF-8') . "%";
            $sql = "SELECT
           
            e.id_estado as idEstado,
            e.nombre_estado as estado,
            muni.id_municipio as idMunicipio,
            muni.nombre_municipio as municipio
          
            
        FROM
            codigo_postal cp
        USE INDEX
            (IDX_COPIGO_POSTAL)
        JOIN municipios muni ON
            muni.id_estado = cp.cve_estado
            AND muni.cve_municipio = cp.cve_mnpio
        JOIN estados e ON
            e.cve_estado = cp.cve_estado
          
        WHERE
            cp.codigo_postal LIKE ?";

            $result_array = array();
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s",$datos);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows >= 1) {
                while ($row = $result->fetch_assoc()) {

                    array_push($result_array, $row);
                }
            } else {
                $result_array[] = array(
                    'id' => null,
                    'text' => "No se encotraron resultados...",
                );

            }
            mysqli_close($mysqli);
            return safe_json_encode($result_array, $options = 0, $depth = 512, $utfErrorFlag = false);
           
 
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
 

    public function localidadCP($cp)
    {

   
        try{

            $conection = new conection();
            $mysqli = $conection->conections();
             
          
            $sql = "SELECT
            id_localidad as id,
            asentamiento as text,
            cp
        FROM
            localidad
        WHERE
            cp = ?";

            $result_array = array();
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("i",$cp);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows >= 1) {
                while ($row = $result->fetch_assoc()) {

                    array_push($result_array, $row);
                }
            } else {
                $result_array[] = array(
                    'id' => null,
                    'text' => "No se encotraron resultados...",
                );

            }
            mysqli_close($mysqli);
            return safe_json_encode($result_array, $options = 0, $depth = 512, $utfErrorFlag = false);
           
 
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

    public function guardar($idMedico, $array )
    {

   
        try{

            $conection = new conection();
            $mysqli = $conection->conections();

            $validacion=$array->validacion;
            $parametros=$array->idPaciente."¬".
                        $idMedico."¬".
                        addslashes($array->nombre)."¬".
                        addslashes($array->appPaterno)."¬".
                        addslashes($array->appMaterno)."¬".
                        addslashes( $array->genero)."¬".
                        addslashes($array->fechaNacimiento)."¬".
                        addslashes($array->curp)."¬".
                        addslashes($array->expediente)."¬".
                        addslashes($array->clinica)."¬".
                        $array->idEstado."¬".
                        $array->idMunicipio."¬".
                        $array->idLocalidad."¬".
                        $array->cp."¬".
                        $array->celular."¬".
                        $array->telefono."¬".
                        addslashes( $array->correo)."¬".
                        $array->edad."¬".
                        addslashes( $array->escolaridad)."¬" ;

            $delimitador="¬";
            if (!($res = $mysqli->multi_query(" CALL    paciente('$validacion', '$parametros', '$delimitador',@p4)"))) {
                mysqli_close($mysqli);
                echo "Falló la llamada: (" . $mysqli->errno . ") " . $mysqli->error;
            }

            $select = mysqli_query($mysqli, 'SELECT @p4 ');
            $result = mysqli_fetch_assoc($select);
            mysqli_close($mysqli);
            return $result['@p4'];
           
 
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
 

    public function buscarPaciente($idMedico, $idPaciente)
    {

   
        try{

            $conection = new conection();
            $mysqli = $conection->conections();
             
          
            $sql = "SELECT
            p.nombre,
            IFNULL(p.app_paterno, '') appPaterno,
            IFNULL(app_materno, '') appMaterno,
            IFNULL(p.clinica_medica, '') clinica,
            IFNULL(p.no_expediente, '') expediente,
            IFNULL(p.curp, '') curp,
            p.cve_genero,
            IFNULL( DATE_FORMAT(p.fecha_nacimiento, '%e-%c-%Y'), '') fechaN,
            p.edad,
            IFNULL(p.escolaridad, '') escolaridad,
            IFNULL(p.codigo_postal, '') cp,
            p.id_estado idEstado,
            IFNULL(e.nombre_estado, '') estado,
            p.id_municipio idMunicipio,
            IFNULL(m.nombre_municipio, '') municipio,
            p.id_localidad idLocalidad,
            ifnull(l.municipio,'')localidad,
    IFNULL(m.nombre_municipio, '') municipio,
            IFNULL(p.celular, '') cel,
            IFNULL(p.telefono, '') tel,
            IFNULL(p.correo, '') correo
        FROM
            paciente p
        LEFT JOIN estados e ON
            e.id_estado = p.id_estado
        LEFT JOIN municipios m ON
            m.id_municipio = p.id_municipio
        LEFT JOIN localidad l ON
            l.id_localidad = p.id_localidad
            
            where p.id_medico=?
            and p.id_paciente=?";

            $result_array = array();
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ii",$idMedico, $idPaciente);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows >= 1) {
                while ($row = $result->fetch_assoc()) {

                    array_push($result_array, $row);
                }
            } else {
                $result_array[] = array(
                    'id' => null,
                    'text' => "No se encotraron resultados...",
                );

            }
            mysqli_close($mysqli);
            return safe_json_encode($result_array, $options = 0, $depth = 512, $utfErrorFlag = false);
           
 
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



}