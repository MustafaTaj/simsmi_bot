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
require 'vendor/autoload.php';
$client = new Zelenin\Telegram\Bot\Api('183692296:AAEsT63R1yvvYMsWCm0t9NEhUz-OYEByA3c'); // Set your access token
$url = 'https://oiu-medicine.herokuapp.com/'; // URL RSS feed
$update = $_GET['do'];
if (empty($update))
die(json_encode(array("status" => "error", "error_message" =>
            "empty request !")));
            
if ($_GET['password'] != '81c7b6dd7bef83eb339178bbbd3c6059')
    die(json_encode(array("status" => "error", "error_message" =>
            "incorrect authincation password !")));

if ($_GET['do'] == 'sendMessage') {
    $ListToSend = json_decode($_POST['ListToSend'], 1);
    foreach ($ListToSend as $SendTo) {
        echo "{$SendTo['chat_id']}, 'text' => {$SendTo['text']}";
        //$response = $client->sendMessage(['chat_id' => $SendTo['chat_id'], 'text' => $SendTo['text']]);
    }
    die(json_encode(array("status" => "success")));
}

?>
