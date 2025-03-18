<?php 

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion(){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'c39839982590cc';
        $mail->Password = '3fc9b788946388';

        $mail->setFrom('diego@UpTask.com');
        $mail->addAddress('diego@UpTask.com', 'uptask.com');
        $mail->Subject = 'Confirma tu Cuenta';
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= '<p><strong>Hola' . $this->nombre .'</strong> Has creado tu cuenta en UpTask, 
        solo debes confrimarla en el siguiente enlace</p>';
        $contenido .= "<p>Presiona Aqui: <a href='http://localhost:3000/confirmar?
        token=" . $this->token ."'>Confirmar Cuenta</a></p>";
        $contenido .= 'Si tu no creaste esta cuenta, ignora este correo';
        $contenido .= '<html>';

        $mail->Body = $contenido;

        // Enviar email: 
        $mail->send();

    }
}