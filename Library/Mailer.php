<?php

namespace Library;

class Mailer
{
  var $charset;

  function __construct()
  {
    $path_phpmailer = dirname(__FILE__) .'/PHPMailer/PHPMailerAutoload.php';

    if (file_exists($path_phpmailer)) {
      require_once $path_phpmailer;
    } else {
      die('PHPMailer has not been installed');
    }

    $this->mail = new \PHPMailer;

    $this->charset = 'utf-8';
    $this->encoding = 'base64';

    // Enable verbose debug output
    //$this->mail->SMTPDebug = 3;
  }

  function send($receipients='', $subject='', $message='', $replyto='', $replyname='')
  {
    $this->mail->IsSMTP();
    $this->mail->CharSet = $this->charset;
    $this->mail->Encoding = $this->encoding;
    $this->mail->Host = 'smtp.gmail.com';                               // Specify main and backup SMTP servers
    $this->mail->SMTPAuth = true;                                      // Enable SMTP authentication
    $this->mail->Username = 'no-reply@3d-products.com';               // SMTP username
    $this->mail->Password = 'VT2VPqQxNja8Nv65';                         // SMTP password
    $this->mail->SMTPSecure = 'tls';                                   // Enable TLS encryption, `ssl` also accepted
    $this->mail->Port = 587;                                           // TCP port to connect to

    if ($replyto && $replyname) {
      $this->mail->AddReplyTo($replyto, $replyname);
    }
    $this->mail->setFrom('no-reply@3d-products.com', 'Cloud-Files 3D-Products');

    if (is_array($receipients)) {
      foreach ($receipients as $receipient) {
        // Add a recipient
        $this->mail->addAddress($receipient);
      }
    } else {
      $this->mail->addAddress($receipients);
    }

    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $this->mail->isHTML(true);                                  // Set email format to HTML

    $this->mail->Subject = $subject;
    $this->mail->Body    = $message;
    //text-body
    $this->mail->AltBody = strip_tags($message);

    if(!$this->mail->send()) {
      return $this->mail->ErrorInfo;
    } else {
      return true;
    }
  }
}
