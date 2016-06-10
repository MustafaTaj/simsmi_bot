<?php
session_start();
/*
* This file is part of GeeksWeb Bot (GWB).
*
* GeeksWeb Bot (GWB) is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License version 3
* as published by the Free Software Foundation.
* 
* GeeksWeb Bot (GWB) is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.  <http://www.gnu.org/licenses/>
*
* Author(s):
*
* © 2015 Kasra Madadipouya <kasra@madadipouya.com>
*
*/
require 'vendor/autoload.php';

$client = new Zelenin\Telegram\Bot\Api('183692296:AAEsT63R1yvvYMsWCm0t9NEhUz-OYEByA3c'); // Set your access token
$url = 'https://oiu-medicine.herokuapp.com/'; // URL RSS feed
$update = json_decode(file_get_contents('php://input'));
$SessionAction = $_SESSION[$update->message->chat->id . "action"];

if (! isset($SessionAction)) {
    //your app
    try {
        if ($update->message->text == '/about' || $update->message->text == '/start') {
            $response = $client->sendChatAction(['chat_id' => $update->message->chat->id,
                'action' => 'typing']);
            $response = $client->sendMessage(['chat_id' => $update->message->chat->id,
                'text' => "التطبيق الرسمي لجامعة أمدرمان الإسلامية كلية الطب والعلوم الصحية \nهذا البوت خاص ببرنامج تليجرام فقط ولا يعمل على نظام غيره, يمكنك الإستفادة من خدمات الموقع مباشرةً من خلال هذا البوت \n برمجة وتطوير : مصطفى تاج السر ( الدفعة 25 ) \n للبدء بإستخدام البوت فضلاً إستخدام الأمر /start\nللحصول على قائمة أوامر المساعدة إستخدم الأمر /help"]);

        } elseif ($update->message->text == '/contact') {
            $response = $client->sendChatAction(['chat_id' => $update->message->chat->id,
                'action' => 'typing']);
            $response = $client->sendMessage(['chat_id' => $update->message->chat->id,
                'text' => "يمكنك التواصل معي عبر البريد : for.u.400@gmail.com \nأو الإتصال بي عبر الرقم: 0914191191 \nمصطفى تاج السر - كلية الطب بجامعة أمدرمان الإسلامية ( الدفعة 25 )"]);

        } elseif ($update->message->text == '/me') {
            $response = $client->sendChatAction(['chat_id' => $update->message->chat->id,
                'action' => 'typing']);
            $response = $client->sendMessage(['chat_id' => $update->message->chat->id,
                'text' => json_encode($update)]);

        } elseif ($update->message->text == '/link') {
            $response = $client->sendChatAction(['chat_id' => $update->message->chat->id,
                'action' => 'typing']);
            $response = $client->sendMessage(['chat_id' => $update->message->chat->id,
                'text' => "فضلاً قم بكتابة مفتاح API الخاص بعضويتك لربطها بتطبيق التلجرام\n للحصول على مفتاح API فضلاً توجه للصفحة : http://oiu.edu.sd/medicine/student_cp.php?do=api"]);
            $_SESSION[$update->message->chat->id . "action"] = "link";

        } elseif ($update->message->text == '/help') {
            $response = $client->sendChatAction(['chat_id' => $update->message->chat->id,
                'action' => 'typing']);
            $response = $client->sendMessage(['chat_id' => $update->message->chat->id,
                'text' => "قائمة الأوامر المتاحة :\n /link -> ربط البوت مع عضوية الموقع\n /about -> معلومات حول البوت \n /contact -> بيانات الإتصال \n /help -> إظهار قائمة الأوامر المتاحة"]);

        } elseif ($update->message->text == '/latest') {
            Feed::$cacheDir = __dir__ . '/cache';
            Feed::$cacheExpire = '5 hours';
            $rss = Feed::loadRss($url);
            $items = $rss->item;
            $lastitem = $items[0];
            $lastlink = $lastitem->link;
            $lasttitle = $lastitem->title;
            $message = $lasttitle . " \n " . $lastlink;
            $response = $client->sendChatAction(['chat_id' => $update->message->chat->id,
                'action' => 'typing']);
            $response = $client->sendMessage(['chat_id' => $update->message->chat->id,
                'text' => $message]);

        } else {
            $response = $client->sendChatAction(['chat_id' => $update->message->chat->id,
                'action' => 'typing']);
            $response = $client->sendMessage(['chat_id' => $update->message->chat->id,
                'text' => "الأمر المدخل غير صحيح, فضلاً إستخدم الأمر /help للحصول على قائمة الأوامر المتاحة '". $update->message->chat->id . "action" ."'"]);
        }

    }
    catch (\Zelenin\Telegram\Bot\NotOkException $e) {

        //echo error message ot log it
        //echo $e->getMessage();

    }
} else {
    if ($action == "link") {
        if ($update->message->text == '/cancel') {
            $_SESSION[$update->message->chat->id . "action"] = '';
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


}
