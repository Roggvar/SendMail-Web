<?php

require './Lib/PHPMailer/Exception.php';
require './Lib/PHPMailer/OAuth.php';
require './Lib/PHPMailer/POP3.php';
require './Lib/PHPMailer/SMTP.php';
require './Lib/PHPMailer/PHPMailer.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mensagem {

    private $para = null;
    private $assunto = null;
    private $mensagem = null;

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        return $this->$atributo = $valor;
    }

    public function mensagemValida() {
        if(empty($this -> para) || empty($this -> assunto) || empty($this -> mensagem)) {
            return false;
        }else {
            return true;
        }
    }
}

$mensagem = new Mensagem();
$mensagem -> __set('para', $_POST['para']);
$mensagem -> __set('assunto', $_POST['assunto']);
$mensagem -> __set('mensagem', $_POST['mensagem']);

//print_r($mensagem);

if(!$mensagem -> mensagemValida()) {
    echo 'Mensagem Inválida';
    die();
}

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 2;                                       //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.example.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'user@example.com';                     //SMTP username
    $mail->Password   = 'secret';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('from@example.com', 'Mailer');
    $mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
    $mail->addAddress('ellen@example.com');               //Name is optional
    $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');

    //Attachments
    $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Não foi possível enviar o email. Erro: {$mail->ErrorInfo}";
}