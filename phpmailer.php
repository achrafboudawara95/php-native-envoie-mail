<?php

use PHPMailer\PHPMailer\PHPMailer;

require 'vendor/autoload.php';
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(400);
    return print_r(json_encode(['error'=>'you must provide post method.']));
}
if( !isset(apache_request_headers()['Content-Type']) || apache_request_headers()['Content-Type']!="application/json")
{
    http_response_code(400);
    return print_r(json_encode(['error'=>'body must be json.']));
}
$CONTENTS=json_decode(file_get_contents('php://input'),TRUE);
if( !isset($CONTENTS['address']) || !isset($CONTENTS['body']) )
{
    http_response_code(400);
    return print_r(json_encode(['error'=>'missing fields.']));
}

$token = isset(apache_request_headers()['token'])?apache_request_headers()['token']:null;
if($token==null || $config['token']!=$token)
{
    http_response_code(401);
    return print_r(json_encode(['error'=>'token may be invalid.']));
}
ob_start();
$subject=isset($CONTENTS['subject'])?$CONTENTS['subject']:'';

$address=$CONTENTS['address'];
$name=isset($CONTENTS['name'])?$CONTENTS['name']:null;
$body=$CONTENTS['body'];

$mail = new PHPMailer;
$mail->isSMTP();
//to ovh
$mail->Priority = $config['Priority'];
$mail->SMTPDebug = $config['SMTPDebug'];
//end to ovh
$mail->SMTPSecure = $config['SMTPSecure'];
$mail->Host = $config['Host'];
$mail->Port = $config['Port'];
$mail->SMTPAuth = $config['SMTPAuth'];
$mail->Username = $config['Username'];
$mail->Password = $config['Password'];
$mail->setFrom($config['From']['address'], $config['From']['name']);
$mail->addReplyTo($config['Reply'], $name);
$mail->addAddress($address, 'Receiver Name');
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