<?php
/** بسم الله الرحمن الرحيم **/
/*/*====================================================================*\*\
* || #################################################################### ||
* || # Script Coded By  Mustafa Taj Elsir : www.fb.com/kemo2011         # ||
* || # Copyright 2016  All Rights Reserved.                             # ||
* || # This file may not be redistributed in whole or significant part. # ||
* || # ------------------ This IS NOT A FREE SOFTWARE ----------------- # ||
* || # -----------       Mustafa Taj Special Software      -------------- ||
* || #################################################################### ||
* \*======================================================================*/
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
require 'vendor/autoload.php';

$client = new Zelenin\Telegram\Bot\Api('183692296:AAEsT63R1yvvYMsWCm0t9NEhUz-OYEByA3c'); // Set your access token
$url = 'https://oiu-medicine.herokuapp.com/'; // URL RSS feed
$update = json_decode(file_get_contents('php://input'));
$UniqID = $update->message->chat->id . "::" . $update->message->chat->username;

$response = $client->sendChatAction(['chat_id' => "58102614", 'action' =>
    'typing']);
$fields = array("replay" => $update->message->text);
$response = CurlRequest("http://oiu.edu.sd/medicine/api/telegram/index.php?username=$UniqID",
    $fields);
if ($response["return_type"] == "text")
    $response = $client->sendMessage(['chat_id' => $update->message->chat->id,
        'text' => $response["return_text"]]);

echo $response["return_type"];
?>
