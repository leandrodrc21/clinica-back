<?php
 

class registro extends conection{

    public function nuevo($array)
    {

   
        try{

            $conection = new registro();
            $mysqli = $conection->conections();


            $parametro= "0¬" . test_input(mysqli_real_escape_string($mysqli, $array->nombre)). "¬".
            test_input(mysqli_real_escape_string($mysqli, $array->apellidoPaterno)). "¬".
            test_input(mysqli_real_escape_string($mysqli, $array->apellidoMaterno)) . "¬".
            test_input(mysqli_real_escape_string($mysqli, $array->nickName)) . "¬".
            test_input(mysqli_real_escape_string($mysqli, $array->rfc)) . "¬".
            test_input(mysqli_real_escape_string($mysqli, $array->cedula)) . "¬".
            test_input(mysqli_real_escape_string($mysqli, $array->celular)) . "¬".
             "0". "¬".
            test_input(mysqli_real_escape_string($mysqli, $array->usuario)) ;
     
            $delimitador="¬";
            $password=$conection->encriptaPassword($array->passwords);

        if (!($res = $mysqli->multi_query("CALL  insertUsuario ('$parametro', '$delimitador', '$password','insert',  @p3)")))
        {
            mysqli_close($mysqli);
            $salida = array(
                "estatus" => "error",
                "mensaje" =>"Fallólallamada:(" . $mysqli->errno . ")" . $mysqli->error
            );
            return safe_json_encode($salida);
        }

        $select = mysqli_query($mysqli, 'SELECT @p3');
        $result = mysqli_fetch_assoc($select);
        $valor= $result['@p3'];

        if($valor=="insert"){
            self::enviarCorreo( $array->usuario);
        }
        $salida = array(
            "estatus" => "ok",
            "mensaje" =>   $valor
        );
            return safe_json_encode($salida );
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


    private function enviarCorreo($correo)
    {
    
    try{
          require "../mail/class.phpmailer.php";
                  require "../mail/class.smtp.php";
          // Datos de la cuenta de correo utilizada para enviar vía SMTP
                  $smtpHost    = "mail.mersys.com.mx"; // Dominio alternativo brindado en el email de alta
                  $smtpUsuario = "postmaster@mersys.com.mx"; // Mi cuenta de correo
                  $smtpClave   = "WR8]u4sq+wk$"; // Mi contraseña
                  $email       = $correo;
                  $mensaje     = "";
    
               
                  $mail         = new PHPMailer();
                  $mail->IsSMTP();
                  $mail->SMTPAuth = true;
                  $mail->Port     = 26;
                  $mail->IsHTML(true);
                  $mail->CharSet = "utf-8";
    
          // VALORES A MODIFICAR
                  $mail->Host     = $smtpHost;
                  $mail->Username = $smtpUsuario;
                  $mail->Password = $smtpClave;
    
                  $mail->From     =  $smtpUsuario; // Email desde donde envío el correo.
                  $mail->AddAddress($email); // Esta es la dirección a donde enviamos los datos del formulario
                  $mail->Subject = "Registro exitoso"; // Este es el titulo del email.
                  $mensajeHtml   = nl2br($mensaje);
                  $folio= self::recuperaFolio($correo);
                  $mensaje       =  self::plantillaMail($folio) ;
                  $mail->Body = $mensaje;
    
                  $mail->AltBody     = "{$mensaje} \n\n "; // Texto sin formato HTML
                  $mail->SMTPOptions = array(
                      'ssl' => array(
                          'verify_peer'       => false,
                          'verify_peer_name'  => false,
                          'allow_self_signed' => true,
                      ),
                  );
                  $estadoEnvio = $mail->Send();
           
    
              if ($estadoEnvio) {
                  return true;
              } else {
                  return false;
              }
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


    private function recuperaFolio($correo){
        try
        {
            $conection = new registro();
            $mysqli = $conection->conections();
            
            $sql = "SELECT m.folio_dc as folio FROM medico m
            join usuarios u on u.id_medico = m.id_medico
            and u.cve_usuario= ?";

            $result_array = array();
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $result = $stmt->get_result();
            $folio="-----";
            if ($result->num_rows >= 1) {
                while ($row = $result->fetch_assoc()) {
                   $folio="{$row['folio']}";
                }
            } 
            mysqli_close($mysqli);
            return $folio;
        } catch (InvalidArgumentException $e) {
            return "Error de argumento inválido: " . $e->getMessage();
        } catch (TypeError $e) {
            return "Error de tipo: " . $e->getMessage();
        } catch (ErrorException $e) {
            // este bloque no se ejecuta, no coincide el tipo de excepción
            return 'ErrorException' . $e->getMessage();
        } catch (Exception $e) {
            // este bloque captura la excepción
            return 'Exception' . $e->getMessage();
        }
    }
    private function  plantillaMail($folio){

        $plantilla="<!DOCTYPE html>
        <html>
        <head>
        <title></title>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge' />
        <style type='text/css'>
         
            @media screen {
                @font-face {
                  font-family: 'Lato';
                  font-style: normal;
                  font-weight: 400;
                  src: local('Lato Regular'), local('Lato-Regular'), url(https://fonts.gstatic.com/s/lato/v11/qIIYRU-oROkIk8vfvxw6QvesZW2xOQ-xsNqO47m55DA.woff) format('woff');
                }
                
                @font-face {
                  font-family: 'Lato';
                  font-style: normal;
                  font-weight: 700;
                  src: local('Lato Bold'), local('Lato-Bold'), url(https://fonts.gstatic.com/s/lato/v11/qdgUG4U09HnJwhYI-uK18wLUuEpTyoUstqEm5AMlJo4.woff) format('woff');
                }
                
                @font-face {
                  font-family: 'Lato';
                  font-style: italic;
                  font-weight: 400;
                  src: local('Lato Italic'), local('Lato-Italic'), url(https://fonts.gstatic.com/s/lato/v11/RYyZNoeFgb0l7W3Vu1aSWOvvDin1pK8aKteLpeZ5c0A.woff) format('woff');
                }
                
                @font-face {
                  font-family: 'Lato';
                  font-style: italic;
                  font-weight: 700;
                  src: local('Lato Bold Italic'), local('Lato-BoldItalic'), url(https://fonts.gstatic.com/s/lato/v11/HkF_qI1x_noxlxhrhMQYELO3LdcAZYWl9Si6vvxL-qU.woff) format('woff');
                }
            }
            
         
            body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
            table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
            img { -ms-interpolation-mode: bicubic; }
        
         
            img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
            table { border-collapse: collapse !important; }
            body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }
         
            a[x-apple-data-detectors] {
                color: inherit !important;
                text-decoration: none !important;
                font-size: inherit !important;
                font-family: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
            }
            
         
            @media screen and (max-width:600px){
                h1 {
                    font-size: 32px !important;
                    line-height: 32px !important;
                }
            }
        
         
            div[style*='margin: 16px 0;'] { margin: 0 !important; }
        </style>
        
        </head>
        <body style='background-color: #f4f4f4; margin: 0 !important; padding: 0 !important;'>
        
         
        <table border='0' cellpadding='0' cellspacing='0' width='100%'>
            
            <tr>
                <td bgcolor='#fafafa' align='center' style='background-color: #fafafa;'>
                  
                    <table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;' >
                        <tr>
                            <td align='center' valign='top' style='padding: 40px 10px 40px 10px;'>
                                <a href='https://www.mersys.com.mx' target='_blank'>
                                    <img alt='Logo' src='https://www.mersys.com.mx/assets/img/logo.png' width='250'  style='display: block; width: 250px;    font-family: 'Lato', Helvetica, Arial, sans-serif; color: #ffffff; font-size: 18px;' border='0'>
                                </a>
                            </td>
                        </tr>
                    </table> 
                </td>
            </tr>
         
            <tr>
                <td bgcolor='#fafafa' align='center' style='padding: 0px 10px 0px 10px;background-color: #fafafa;'>
               
                    <table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;' >
                        <tr>
                            <td bgcolor='#ffffff' align='center' valign='top' style='padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;'>
                              <h1 style='font-size: 48px; font-weight: 400; margin: 0;'>Estimado Médico.</h1>
                            </td>
                        </tr>
                    </table>
                  
                </td>
            </tr>
         
            <tr>
                <td bgcolor='#f4f4f4' align='center' style='padding: 0px 10px 0px 10px;'>
                   
                    <table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px;' >
                     
                      <tr>
                        <td bgcolor='#ffffff' align='left' style='padding: 20px 30px 40px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;' >
                        <div style='padding-left: 40px;'>                 
                        <p >Agradecemos su interés por el Dossier Clínico en la nube. <br>
                            Le informamos que quedo registrado con el folio <strong>$folio</strong> <br>
                           Dispone del servicio del sistema por 30 días <br>
                            Para poder utilizarlo por un año realice su transferencia de $100.00 a la  cuenta xxxx de BBVA <br>
                           Luego enviar el número de transferencia con su folio. <br><br>
                            Mersys S.C.</p>
                           </div>
                       </td>
                        </td>
                      </tr>
                   
                      <tr>
                        <td bgcolor='#ffffff' align='left'>
                          <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                              <td bgcolor='#ffffff' align='center' style='padding: 20px 30px 60px 30px;'>
                                <table border='0' cellspacing='0' cellpadding='0'>
                                  <tr>
                                      <td align='center' style='border-radius: 3px;' bgcolor='#01A9DB'><a href='https://www.mersys.com.mx' target='_blank' style='font-size: 20px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; color: #ffffff; text-decoration: none; padding: 15px 25px; border-radius: 2px; border: 1px solid ; display: inline-block;'>Iniciar Sesion</a></td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                    
                </td>
            </tr> 
         
        </table>
        
        </body>
        </html>
        ";

        return $plantilla;

    }
}