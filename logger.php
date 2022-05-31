<?php

require __DIR__.'/PHPMailer/src/Exception.php';
require __DIR__.'/PHPMailer/src/PHPMailer.php';
require __DIR__.'/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Logger {

    public static function log (ErrorException $e, $type, $tag) {
        $today = date("Y-m-d H:i:s");
        $day = date("Y-m-d");

        $file_config = file_get_contents("/xampp/htdocs/PhP/config.json");
        $config = json_decode($file_config,true);
        $file_path = $config["file"];

        $severity = $config["level"];
        $severity_mail = $config["mail_severity"];
        $email = $config["mail"];

        if($type >= $severity) {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } 
            elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } 
            else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        
        $msg = $tag." Date : ".$today.
        " | IP ".$ip.
        " | Severite : ".$e->getSeverity().
        " | Message : ".$e->getMessage().
        " | Emplacement : ".$e->getFile()." a la ligne : ".$e->getLine().
        " | Erreur ".$e->getCode()."."."\n";
        file_put_contents("../logs/$day"."_AppliLogger.log", $msg, FILE_APPEND);

        echo $msg;
        }

        if($type >= $severity_mail) {
            $mail = new PHPMailer();
            try {

            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      
            $mail->isSMTP();                                           
            $mail->Host       = 'smtp-mail.outlook.com';                    
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = 'axs.sio.2022@outlook.fr';                   
            $mail->Password   = 'safi2022sio';                             
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;           
            $mail->Port       = 587;                                    
        
            // Destinataires (addAddress)
            $mail->setFrom('axs.sio.2022@outlook.fr', 'AXS Mailer');
            $mail->addAddress('safi_mohamed@outlook.fr', 'Safi Mohamed');     

            $mail->isHTML(true);                             
            $mail->Subject = $tag.' Error LOG';
            $mail->Body    = $msg;
            $mail->AltBody = '';
        
            $mail->send();
            echo 'Message has been sent';
            } 
            
            catch(Exception $e) {
                echo "Problème, voir : {$mail->ErrorInfo}";
            }
        }
    }

    static function logDebug ($e) {
        Logger::log($e, 1, "[DEBUG]");
    }

    static function logInfo ($e) {
        Logger::log($e, 2, "[INFO]");
    }

    static function logWarn ($e) {
        Logger::log($e, 3, "[WARN]");
    }

    static function logError ($e) {
        Logger::log($e, 4, "[ERROR]");
    }
}
?>


