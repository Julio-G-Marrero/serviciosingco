<?php 

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    public $email;
    public $nombre;
    public $token;

    public function __construct($email,$nombre,$token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;

    }

    public function enviarConfirmacion(){
        //Crear el objeto del Email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;

        $mail->Username = 'juliogarciam785@gmail.com';
        $mail->Password = 'rrxlnfdevnqybnqw';

        $mail->setFrom('Admin@upTask.com');
        $mail->addAddress($this->email, $this->nombre);
        $mail->Subject = "Confirma tu cuenta";

        //Set html
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola ". $this->nombre ."</strong> Has creado tu cuenta en nuestra Aplicaicón de Indicadores, solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido .= "<p>Presiona aqui: <a href= localhost:3000/confirmar?token=".$this->token."'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta puedes ignorar el mensaje</p>";
        $contenido .= "<p>En caso de que el enlace no funciona copie este link el siguiente link en el navegador: <br> localhost:3000/confirmar?token=".$this->token." </p>";
        $contenido .= '</html>';

        $mail->Body = $contenido;
        //Enviar email
        $mail->send();
    }

    public function enviarInstrucciones() {
      //Crear el objeto del Email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;

        $mail->Username = 'juliogarciam785@gmail.com';
        $mail->Password = 'rrxlnfdevnqybnqw';

        $mail->setFrom('Admin@upTask.com');
        $mail->addAddress($this->email, $this->nombre);
        $mail->Subject = "Confirma tu cuenta";

        //Set html
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola ". $this->nombre ."</strong> has solicitado restalbecer tu password sigue el siguiente enlace para hacerlo</p>";
        $contenido .= "<p>Presiona aqui: <a href='http://localhost:3000/restablecer?token=".$this->token."'>Restablecer Cuenta</a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta puedes ignorar el mensaje</p>";
        $contenido .= "<p>En caso de que el enlace no funciona copie este link el siguiente link en el navegador: <br> localhost:3000/restablecer?token=".$this->token." </p>";
        $contenido .= '</html>';

        $mail->Body = $contenido;
        //Enviar email
        $mail->send();
    }
}