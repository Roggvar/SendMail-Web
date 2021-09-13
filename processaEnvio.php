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
    public $status = array('codigoStatus' => null, 'descricaoStatus' => null);

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
    header('Location: index.php');
}

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = false;                                       //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.example.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'user@example.com';                     //SMTP username
    $mail->Password   = 'secret';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('from@example.com', 'Mailer');
    $mail->addAddress($mensagem -> __get('para'));     //Add a recipient
    $mail->addAddress('ellen@example.com');               //Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $mensagem -> __get('assunto');
    $mail->Body    = $mensagem -> __get('mensagem');
    $mail->AltBody = 'Necessário usar um client com suporte HTML';

    $mail->send();
    $mensagem -> status['codigoStatus'] = 1;
    $mensagem -> status['descricaoStatus'] = 'Email enviado com sucesso';
} catch (Exception $e) {
    $mensagem -> status['codigoStatus'] = 2;
    $mensagem -> status['descricaoStatus'] = 'Não foi possível enviar o email. Erro: ' . $mail->ErrorInfo;
}

?>

<html>
    <head>

        <meta charset="utf-8" />
        <title>App Mail Send</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    </head>

    <body>
        
        <div class="container">
            <div class="py-3 text-center">
                <img class="d-block mx-auto mb-2" src="Img/logo.png" alt="" width="72" height="72">
                <h2>Send Mail</h2>
                <p class="lead">Seu app de envio de e-mails particular!</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?php if($mensagem -> status['codigoStatus'] == 1) { ?>
                    
                    <div class="container">
                        <h1 class="display-4 text-sucess">Sucesso</h1>
                        <p><?= $mensagem -> status['descricaoStatus'] ?></p>
                        <a href="index.php" class="btn btn-sucess btn-lg mt-5 text-white">Voltar</a>
                    </div>

                <?php } ?>

                <?php if($mensagem -> status['codigoStatus'] == 1) { ?>
                    
                    <div class="container">
                        <h1 class="display-4 text-danger">Ops</h1>
                        <p><?= $mensagem -> status['descricaoStatus'] ?></p>
                        <a href="index.php" class="btn btn-sucess btn-lg mt-5 text-white">Voltar</a>
                    </div>

                <?php } ?>
            </div>
        </div>

    </body>
</html>