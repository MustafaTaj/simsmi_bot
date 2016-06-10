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
function CurlRequest2($fields = array("do" => "nothing"))
{
    global $UniqID;
    $url = "http://oiu.edu.sd/medicine/api/telegram/index.php?username=$UniqID&chatid=" .
        $update->message->chat->id . "&realusername=" . $update->message->chat->
        username;
    $fields_string = '';
    foreach ($fields as $key => $value) {
        $fields_string .= $key . '=' . urlencode($value) . '&';
    }
    rtrim($fields_string, '&');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    $result = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $result);
    return json_decode($result, true);
}
$client = new Zelenin\Telegram\Bot\Api('183692296:AAEsT63R1yvvYMsWCm0t9NEhUz-OYEByA3c'); // Set your access token
$url = 'https://oiu-medicine.herokuapp.com/'; // URL RSS feed
$update = json_decode(file_get_contents('php://input'));
$UniqID = $update->message->chat->id . "::" . $update->message->chat->username;


//your app
try {
    if ($update->message->text == '/me') {
        $response = $client->sendChatAction(['chat_id' => $update->message->chat->id,
            'action' => 'typing']);
        $response = $client->sendMessage(['chat_id' => $update->message->chat->id,
            'text' => json_encode($update)]);

    } else {
        $responses = $client->sendChatAction(['chat_id' => $update->message->chat->id,
            'action' => 'typing']);
        $fields = array("replay" => $update->message->text);
        $response = CurlRequest2($fields);
        if ($response["return_type"] == "text")
            $response = $client->sendMessage(['chat_id' => $update->message->chat->id,
                'text' => $response["return_text"]]);
    }
}
catch (\Zelenin\Telegram\Bot\NotOkException $e) {
    $response = $client->sendMessage(['chat_id' => $update->message->chat->id,
        'text' => "خطأ: " . $e->getMessage()]);
    //echo error message ot log it
    //echo $e->getMessage();

}
/*} else {
if ($action == "link") {
if ($update->message->text == '/cancel') {
$_SESSION[$UniqID] = '';
$response = $client->sendChatAction(['chat_id' => $update->message->chat->id,
'action' => 'typing']);
$response = $client->sendMessage(['chat_id' => $update->message->chat->id,
'text' => "تم إلغاء ربط العضوية الخاصة بك بتطبيق التلجرام"]);
exit();
}
if (empty($update->message->text)) {
$response = $client->sendChatAction(['chat_id' => $update->message->chat->id,
'action' => 'typing']);
$response = $client->sendMessage(['chat_id' => $update->message->chat->id,
'text' => "فضلاً تحقق من كتابتك لمفتاح API الخاص بعضويتك !\nإختر الأمر /cancel لإلغاء الأمر"]);
exit();
}
if (strlen($update->message->text) != 40) {
$response = $client->sendChatAction(['chat_id' => $update->message->chat->id,
'action' => 'typing']);
$response = $client->sendMessage(['chat_id' => $update->message->chat->id,
'text' => "مفتاح API المدخل غير صحيح !\nإختر الأمر /cancel لإلغاء الأمر"]);
exit();
}
}
}*/

?>
