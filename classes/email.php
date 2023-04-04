<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Dotenv\Dotenv as Dotenv;
$dotenv = Dotenv::createImmutable('../includes/.env');
$dotenv->safeLoad();

class Email {

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token) {

        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }
    
    public function enviarConfirmacion() {

        // Prueba 1
        $mail = new PHPMailer(true); 
        $mail->SMTPOptions = array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
             )
        );
        $mail->SMTPDebug = 2; 
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->Port = 587;// TCP port to connect to
        $mail->CharSet = 'UTF-8';
        $mail->Username = $_ENV['MAIL_USER']; //Email para enviar
        $mail->Password = $_ENV['MAIL_PASSWORD']; //Su password

        // Agregar destinatario
        $mail->setFrom($_ENV['MAIL_USER'], 'Confirmar Cuenta de AppSalon');
        $mail->AddAddress($this->email); // A quien mandar email
        $mail->SMTPKeepAlive = true;  
        $mail->Mailer = "smtp"; 


        // Contenido
        $mail->isHTML(true); // Setear el formato del email a HTML
        $mail->Subject = 'Confirma tu Cuenta de AppSalon';    

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Haz creado tu cuenta en AppSalon, solo debes confirmarla presionando el siguiente enlace </p>";
        $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['SERVER_HOST'] . "confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta </a> </p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje </p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;
    
        if(!$mail->send()) {
            echo 'Error al enviar email';
            echo 'Mailer error: ' . $mail->ErrorInfo;
            } else {
            echo 'Mail enviado correctamente';
            }  
    }

    public function enviarInstrucciones() {

        // Prueba 2
        $mail = new PHPMailer(true); 
        $mail->SMTPOptions = array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
             )
        );
        // $mail->SMTPDebug = 2; 
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->Port = 587;// TCP port to connect to
        $mail->CharSet = 'UTF-8';
        $mail->Username = $_ENV['MAIL_USER']; //Email para enviar
        $mail->Password = $_ENV['MAIL_PASSWORD']; //Su password

        // Agregar destinatario
        $mail->setFrom($_ENV['MAIL_USER'], 'Appsalon.com');
        $mail->AddAddress($this->email); // A quien mandar email
        $mail->SMTPKeepAlive = true;  
        $mail->Mailer = "smtp"; 


        // Contenido
        $mail->isHTML(true); // Setear el formato del email a HTML
        $mail->Subject = 'Reestablece tu Password de Appsalon';    

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Haz solicitado reestablecer tu password en AppSalon, sigue el siguiente enlace para hacerlo </p>";
        $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['SERVER_HOST'] . "recuperar?token=" . $this->token . "'>Reestablecer Password</a></p>";
        $contenido .= "<p>Si tu no solicitaste este cambio, puedes ignorar el mensaje </p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;
    
        $mail->send();
    }  
}
 ?>