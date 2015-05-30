﻿<?php
    Yii::import('application.extensions.phpmailer.JPhpMailer');
class EnviarEmail{

            public function enviar($to,$name,$claveacceso,$documento,$tipo){
//$to='donpool@gmail.com';
                $tipodocumento='-';
                try{
                        $mailer=new JPhpMailer;
                            if (!$mailer->ValidateAddress($to)){
                            return "El mail $to es inválido.";
                          }
                           switch ($tipo) {
                            case 1:  $tipodocumento='Ha recibido una nueva Factura Electrónica' ; break;
                            case 4:  $tipodocumento='Ha recibido una nueva Nota de Crédito Electrónica '; break;
                            case 5:  $tipodocumento='Ha recibido una nueva Nota de Débito Electrónica'; break;
                            case 6:  $tipodocumento='Ha recibido una nueva Guia de Remisión Electrónica'; break;
                            case 7:  $tipodocumento='Ha recibido un nuevo Comprobante de Retención Electrónico'; break;
                            default: break;
                        }
                      
                        $mailer->CharSet = 'utf-8';
                        $mailer->IsSMTP();
                        $mailer->Host = 'localhost';
                        $mail->SMTPAuth=true;
                        $mailer->Username = "comprobantes";
                        $mailer->Password = "F4ctur4s2015"; 
                        $mailer->SetFrom(Yii::app()->params['adminEmail'], 'e-billing');
                        $mailer->Subject= $tipodocumento;
                        $mailer->AddAddress($to,$name);
                        $mailer->IsHTML(true);
                     
                       
                        $mailer->Body='
                                <html>
                                <head>
                                <title>DOCUMENTO ELECTRÓNICO</title>
                                </head>
                                <body>
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center"><img src="http://www.notario35quito.com/logonotario.png" width="150" height="150"></td>
  </tr>
  <tr>
    <td height="113" align="center"><p>&nbsp;</p>
      <p>Estimado(a)<br>
        <strong>'.$name.'</strong></p>
      <p>Tiene un nuevo DOCUMENTO No. '.$documento.', se encuentra 
      disponible para su visualización y descarga. Usted puede ingresar 
      al sistema en www.notario35quito.com/e-billing el usuario y 
      contraseña será su CI/RUC hasta que proceda a cambiarlo en el menú 
      del sistema.</p>
      <p>Clave de acceso del Documento otorgado por S.R.I.:</p>
      <h4>'.$claveacceso.' </h4>
    <p>Si tiene cualquier inquietud escríbanos a: info@notario35quito.com</p>
    <p>&nbsp;</p></td>
  </tr>
  <tr>
    <td align="center">NOTARIO TRIGÉSIMO QUINTO - QUITO D.M.</td>
  </tr>
</table>
                                </body>
                                </html>
                                ';
      			        $xml=Yii::getPathOfAlias('webroot') . '/data/documentos/xml/'.$claveacceso.'.xml';
                        $pdf=Yii::getPathOfAlias('webroot') . '/data/documentos/pdf/'.$claveacceso.'.pdf';
                        $mailer->AddAttachment($xml);
                        $mailer->AddAttachment($pdf);

//                        if($mailer->Send()){
//                            return '1';
//                        }else{
//                            return 'Fallo al enviar el mail!';
//                        }
                }
                  catch (phpmailerException $e){echo $e->errorMessage();} //Pretty error msg from PHPMailer
                  catch (Exception $e){echo $e->getMessage();}
        }
      
}

        
