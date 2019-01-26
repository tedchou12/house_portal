<?php

namespace Library;


class Notification
{
    var $notify;

    function __construct($type='email')
    {
        $this->type = $type;
        $this->setMethod($type);
    }

    function setMethod($type='')
    {
        switch ($type) {
            case 'push':
                break;
            case 'email':
            default:
                require_once BETHLEHEM_LIB_DIR .'/PHPMailer/PHPMailerAutoload.php';
                $this->notify = new PHPMailer();
                $this->notify->IsSMTP();
                $this->notify->CharSet = 'utf-8';
                $this->notify->Encoding = 'base64';
                $this->notify->Host = 'smtp.gmail.com';
                $this->notify->SMTPAuth = true;
                $this->notify->Username = 'jimmy.chou@step30.org';
                $this->notify->Password = '5.ru6au3';
                $this->notify->SMTPSecure = 'tls';
                $this->notify->Port = 587;
                $mail->setFrom('jimmy.chou@step30.org', 'Step30.Info');
                $mail->isHTML(true);
                break;
        }
    }

    function send($subject='', $recipient=[], $content='')
    {
        $this->data['subject'] = $subject;
        $this->data['recipient'] = $recipient;
        $this->data['content'] = $content;

        switch ($type) {
            case 'push':
                break;
            case 'email':
            default:
                $this->sendMail();
                break;
        }
    }

    function sendMail()
    {
        $this->notify->Subject = $this->data['subject'];
        $this->notify->Body = $this->data['content'];
        // $this->notify->AltBody = $this->data['content'];
    }
}