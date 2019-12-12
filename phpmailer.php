<?php

use PHPMailer\PHPMailer\PHPMailer;

require 'vendor/autoload.php';
$mainToken='tTy9k6hkO1!==';
$token = isset(apache_request_headers()['token'])?apache_request_headers()['token']:null;
if($token==null || $mainToken!=$token)
{
    http_response_code(401);
    return print_r(json_encode(['error'=>'token may be invalid.']));
}
ob_start();
$subject='test Mail';
$subject=isset($_POST['subject'])?$_POST['subject']:$subject;
$adress='achraf@hostinger-tutorials.com';
$name='achraf';
$body='toto';
//$adress=$_POST['adress'];
//$name=isset($_POST['name'])?$_POST['name']:null;
//$body=$_POST['body'];

$mail = new PHPMailer;
$mail->isSMTP();
//to ovh
$mail->Priority = 3;
$mail->SMTPDebug = 2;
//end to ovh
$mail->SMTPSecure = 'tls';
$mail->Host = 'smtp.mailtrap.io';
$mail->Port = 2525;
$mail->SMTPAuth = true;
$mail->Username = '75d5c55f77756a';
$mail->Password = '1cfda865e9f33f';
$mail->setFrom('test@hostinger-tutorials.com', 'Your Name');
$mail->addReplyTo('reply-box@hostinger-tutorials.com', $name);
$mail->addAddress($adress, 'Receiver Name');
$mail->Subject = $subject;
$mail->Body = $body;
//$mail->msgHTML(file_get_contents('message.html'), __DIR__);
//$mail->AltBody = 'This is a plain text message body';
//$mail->addAttachment('test.txt');
$send=$mail->send();
ob_end_clean();
if (!$send) {
    http_response_code(400);
    return print_r(json_encode(['error'=>'Mailer Error: ' . $mail->ErrorInfo]));
} else {
    http_response_code(200);
    return print_r(json_encode(['success'=>'The email message was sent.']));
}