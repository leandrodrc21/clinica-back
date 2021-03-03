<?php
 

class parameters
{

    public $host = "localhost";
    public $version = "local"; //puede ser servidor o local o agregar nueva
    public $password = "ship";
    public $usuario = "ship";
    public $dataBase = "clinica";

    public function parametroSalida()
    {
 
        $array = array();
        $array["host"] = $this->host;
        $array["version"] = $this->version;
        $array["password"] = $this->password;
        $array["usuario"] = $this->usuario;
        $array["database"] = $this->dataBase;
        return $array;
    }

   

   
 
}
