<?php

/**

 * Created by PhpStorm.

 * User: Timothy

 * Date: 2/13/2015

 * Time: 5:30 PM

 */

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$firstName = $request->firstName;
$lastName = $request->lastName;
$email = $request->email;
$body = $request->body;
$itemName = $request->itemName;
$itemType = $request->itemType;
$question = $request->question;


$subject = 'subject';

$message = 'message';

$to = 'mark@bfstrings.com';

$to_tim = 'timothyfranzke@gmail.com';
$type = 'plain'; // or HTML

$charset = 'utf-8';

$subject = $question . ' - ' . $itemName;

$message = "Item ID: " . $itemID . "\nItem Name: " . $itemName . "\nItem Type: " . $itemType . "\nFirst Name: " . $firstName . "\n" . "Last Name: " . $lastName . "\n" . "Comment: " . $body;


$mail     = 'no-reply@'.str_replace('www.', '', $_SERVER['SERVER_NAME']);

$uniqid   = md5(uniqid(time()));

$headers  = 'From: '.$mail."\n";

$headers .= 'Reply-to: '.$email."\n";

$headers .= 'Return-Path: '.$mail."\n";

$headers .= 'Message-ID: <'.$uniqid.'@'.$_SERVER['SERVER_NAME'].">\n";

$headers .= 'MIME-Version: 1.0'."\n";

$headers .= 'Date: '.gmdate('D, d M Y H:i:s', time())."\n";

$headers .= 'X-Priority: 3'."\n";

$headers .= 'X-MSMail-Priority: Normal'."\n";

$headers .= 'Content-Type: multipart/mixed;boundary="----------'.$uniqid.'"'."\n\n";

$headers .= '------------'.$uniqid."\n";

$headers .= 'Content-type: text/'.$type.';charset='.$charset.''."\n";

$headers .= 'Content-transfer-encoding: 7bit';



mail($to, $subject, $message, $headers);


echo $firstName . " " .$request->lastName;

