<?php
include "../parameters/parameters.php";
class conection
{
    public function conections()
    {
        try
        {
           
            $objs = new parameters();
            $valores = $objs->parametroSalida();
            $mysqli = new mysqli($valores["host"], $valores["usuario"], $valores["password"], $valores["database"]);
          
            $mysqli->set_charset("utf8");
            if (mysqli_connect_errno())
            {
                echo 'Conexion Fallida : ', mysqli_connect_error();
                exit();
            }
            return $mysqli;
        }
        catch(InvalidArgumentException $e)
        {
            return "Error de argumento inválido: " . $e->getMessage();
        }
        catch(TypeError $e)
        {
            return "Error de tipo: " . $e->getMessage();
        }
        catch(ErrorException $e)
        {
            // este bloque no se ejecuta, no coincide el tipo de excepción
            return 'Error Exception' . $e->getMessage();
        }
        catch(Exception $e)
        {
            // este bloque captura la excepción
            return 'Error Exception' . $e->getMessage();
        }
    }



    public function encriptaPassword($password)
    {
        try {

            $objs = new parameters();
            $valores = $objs->parametroSalida();

            $passwordEncriptado = "";
            $version = $valores["version"];
            if ($version == "local") {
                //$passwordEncriptado = password_hash($password, PASSWORD_ARGON2I, ['memory_cost' => 5 << 17, 'time_cost' => 4, 'threads' => 2]);
                $passwordEncriptado = password_hash($password, PASSWORD_DEFAULT, [15]);
            } else if ($version == "servidor") {
                $passwordEncriptado = sodium_crypto_pwhash_str(
                    $password,
                    SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
                    SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE
                ); // 97 bytes
            }

            return $passwordEncriptado;

        } catch (InvalidArgumentException $e) {
            return "Error de argumento inválido: " . $e->getMessage();
        } catch (TypeError $e) {
            return "Error de tipo: " . $e->getMessage();
        } catch (ErrorException $e) {
            // este bloque no se ejecuta, no coincide el tipo de excepción
            return 'Error Exception' . $e->getMessage();
        } catch (Exception $e) {
            // este bloque captura la excepción
            return 'Error Exception' . $e->getMessage();
        }

    }

    public function validaPassword($password, $passwordEncriptado)
    {
        try {

            $objs = new parameters();
            $valores = $objs->parametroSalida();

            
            $version = $valores["version"];
            $salida = 0;
            
            if ($version == "local") {
                if (password_verify($password, $passwordEncriptado)) {
                    $salida = 1;
                }
            } else if ($version = "servidor") {
                $salida = sodium_crypto_pwhash_str_verify($passwordEncriptado, $password);
            }

            return $salida;
        } catch (InvalidArgumentException $e) {
            return "Error de argumento inválido: " . $e->getMessage();
        } catch (TypeError $e) {
            return "Error de tipo: " . $e->getMessage();
        } catch (ErrorException $e) {
            // este bloque no se ejecuta, no coincide el tipo de excepción
            return 'Error Exception' . $e->getMessage();
        } catch (Exception $e) {
            // este bloque captura la excepción
            return 'Error Exception' . $e->getMessage();
        }
    }
}


