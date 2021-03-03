<?php
use Firebase\JWT\JWT;

class Auth
{
    private static $secret_key = 'y/4Dm3yffV.tpT8';
    private static $encrypt = ['HS256'];
    private static $aud = null;

    public static function SignIn($data)
    {
        $time = time();

        $token = array(
            'exp' => $time + (1800) ,
            'aud' => self::Aud() ,
            'data' => $data
        );

        return JWT::encode($token, self::$secret_key);
    }

    public static function Check($token)
    {

        try
        {

            if (empty($token))
            {
                return "fallo";
            }

            try
            {
                $decode = JWT::decode($token, self::$secret_key, self::$encrypt);
            }
            catch(Error $e)
            {
                return "fallss";
            }

            if ($decode->aud !== self::Aud())
            {
                //  throw new Exception("Invalid user logged in.");
                return "fallos";
            }
            else
            {
                return true;
            }

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

    public static function GetData($token)
    {
        return JWT::decode($token, self::$secret_key, self::$encrypt)->data;
    }

    private static function Aud()
    {
        $aud = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $aud = $_SERVER['REMOTE_ADDR'];
        }

        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();

        return sha1($aud);
    }
}

