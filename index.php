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
require_once ("exams/courses_list.php");

function ClearThisSession()
{
    global $session;
    $session->write("course_step", "");
    $session->write("courseid", "");
    $session->write("showcourses", "");
    $session->write("action", "");
    $session->write("year", "");
}
if ($_POST['replay'] == 'إلغاء الأمر' || $_POST['replay'] == '/cancel') {
    ClearThisSession();
    die(json_encode(array("return_type" => "text", "return_text" => $registered_return)));
}
if (!in_array($session->read("year"), array(
    1,
    2,
    3,
    4,
    5)) && !in_array($_POST['replay'], array(
    1,
    2,
    3,
    4,
    5))) {
    die($YearKeyboard);
} elseif (!in_array($session->read("year"), array(
    1,
    2,
    3,
    4,
    5)) && is_numeric($_POST['replay'])) {
    $session->write("year", $_POST['replay']);
}

$SelectedCourse = strtok($_POST['replay'], '.');

if ($session->read("courseid") == "" && $session->read("course_step") == '') {
    $CourseList = $courses_list[$session->read("year", $_POST['replay'])];
    // die(json_encode(array("return_type" => "text", "return_text" => "Selected Course : $CourseList<br />" . count($CourseList))));
    if (count($CourseList) == 0) {
        ClearThisSession();
        die(json_encode(array("return_type" => "text", "return_text" =>
                "السنة المحددة [ " . $OIU->ConvertYear($session->read("year", $_POST['replay'])) .
                " ] لا تحتوي على كورسات في قاعدة البيانات في الوقت الحالي, الرجاء المحاولة مرة أخرى في وقت لاحق. \n\n/help")));
    }
    foreach ($CourseList as $key => $value) {
        $CoursesLoad[][] = "$key. $value";
    }
    $CoursesLoad[][] = 'إلغاء الأمر';

    $keyboard = $CoursesLoad;
    $resp = array(
        "keyboard" => $keyboard,
        "resize_keyboard" => true,
        "one_time_keyboard" => true);
    $reply = json_encode($resp);
    $courses_keyboard = json_encode(array(
        "return_type" => "keyboard",
        "return_text" => "فضلاً إختر أحد الكورسات أدناه لعرض الإمتحانات الخاصة بها",
        "replay_keyb" => $reply));
    $session->write("course_step", 1);
    die($courses_keyboard);
} else // ($session->read("courseid") == "" && is_numeric($SelectedCourse))

    $session->write("courseid", intval($SelectedCourse));

if ($session->read('course_step') == 1 && $_POST['replay'] !=
    "رفع ملف/صورة امتحانات" && $_POST['replay'] != "أرشيف الإمتحانات") {
    $keyboard = array(array("رفع ملف/صورة امتحانات"), array("إلغاء الأمر",
                "أرشيف الإمتحانات"));
    $resp = array(
        "keyboard" => $keyboard,
        "resize_keyboard" => true,
        "one_time_keyboard" => true);
    $reply = json_encode($resp);
    $academic_keyboard = json_encode(array(
        "return_type" => "keyboard",
        "return_text" => "يمكنك إضافة المزيد من المواد لأرشيف هذا الكورس وذلك عبر رفع ملفات امتحانات سابقة ( صورة, ملفات ...الخ )",
        "replay_keyb" => $reply));
    die($academic_keyboard);
}
if ($session->read('course_step') == 1 && $_POST['replay'] ==
    "رفع ملف/صورة امتحانات") {
    $session->write("course_step", 2);
    die(json_encode(array("return_type" => "text", "return_text" =>
            "فضلاً قم بإرسال الملفات أو الصور المراد إضافتها لأرشيف الإمتحانات\nيمكنك إستخدام إعادة توجيه الرسائل لإعادة توجيه الملفات من المحادثات الأخرى دون الحاجة إلى رفع الملفات \n\nلإلغاء رفع الملفات فضلاً إستخدم الأمر  /cancel")));

} elseif ($session->read('course_step') == 1 && $_POST['replay'] ==
"أرشيف الإمتحانات") {
    $sql = $db->query_read("select * from exams_archive where year = '" . intval($session->
        read('year')) . "' and courseid = '" . intval($session->read('courseid')) . "'");
    if ($db->num_rows($sql) == 0) {
        ClearThisSession();
        die(json_encode(array("return_type" => "text", "return_text" =>
                "الكورس المحدد لا يحتوي على إمتحانات في قاعدة البيانات في الوقت الحالي, الرجاء إعادة المحاولة في وقت لاحق أو المساهمة برفع ملفات أو صور إمتحانات سابقة \n\n/help")));
    }
    while ($recordFiles = $db->record($sql)):
        $FilesList[] = array(
            "return_text" => $recordFiles["filename"],
            "type" => $recordFiles["type"],
            "fileid" => $recordFiles["file_linkid"]);
    endwhile;
    ClearThisSession();
    die(json_encode(array("return_type" => "mutlifile", "files_list" => $FilesList)));

}

if ($session->read("course_step", 0) == 2) {
    $returnArrary = json_decode($_POST['update_content'], 1);
    if ($returnArrary['message']['document']['file_name'] == '' && $returnArrary["message"]["photo"][3]['file_id'] ==
        '')
        die(json_encode(array("return_type" => "text", "return_text" => "Count: " .
                "فضلاً تحقق من إرسالك لملف/صورة الإمتحان بطريقة صحيحة !\nيمكنك إستخدام إعادة توجيه الرسائل من المحادثات الأخرى لرفع ملفات/صور الإمتحان ! \nلإلغاء رفع ملفات الإمتحانات إستخدم الأمر /cancel")));
    $FilesCount = 0;
    $PicturesCount = 0;
    if ($returnArrary['message']['document']['file_name'] != '') {
        $db->query_read("insert into exams_archive set 
        file_linkid = '" . $OIU->mksafe($returnArrary['message']['document']['file_id'], false, true) .
            "',
        filesize = '" . $OIU->mksafe($returnArrary['message']['document']['file_size'], false, true) .
            "',
        filename = '" . $OIU->mksafe($returnArrary['message']['document']['file_name'], false, true) .
            "',
            type = 'document',
            year = '" . $session->read("year") . "',
            courseid = '" . $session->read("courseid") . "',
            userid = '" . $userinfo['userid'] . "',
            dateadd = '" . time() . "'");
    $FilesCount++;
    }
    if ($returnArrary["message"]["photo"][3]['file_id'] != ''){
        $db->query_read("insert into exams_archive set 
        file_linkid = '" . $OIU->mksafe($returnArrary['message']["photo"][3]['file_id'], false, true) .
            "',
        filesize = '" . $OIU->mksafe($returnArrary['message']["photo"][3]['file_size'], false, true) .
            "',
        filename = '" . $OIU->createRandomKey(10) . ".jpg',
        type = 'photo',
            year = '" . $session->read("year") . "',
            courseid = '" . $session->read("courseid") . "',
            userid = '" . $userinfo['userid'] . "',
            dateadd = '" . time() . "'");
    $PicturesCount++;
    }
    die(json_encode(array("return_type" => "text", "return_text" =>
            "تمت إضافة الملف المطلوب بنجاح,برجاء إضافة المزيد من الملفات لإثراء أرشيف الإمتحانات \nعدد الملفات المضافة: $FilesCount\nعدد الصور المضافة: $PicturesCount\n\nأو إختر الأمر /cancel لإلغاء الأمر")));

}

ClearThisSession();
die(json_encode(array("return_type" => "text", "return_text" => "$returnInfo \n\n/help")));

?>
