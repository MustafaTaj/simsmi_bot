<?php
if (!defined('OIU')) {
    define('OIU', true);
}
require ($ScriptDirPath . 'includes/class.db.php');

$Config['DataBase']['Host'] = 'localhost'; // سيرفر القاعدة
$Config['DataBase']['User'] = 'be1c4294b9671e'; // مستخدم القاعدة
$Config['DataBase']['Password'] = '56c9cfd2 '; //كلمة مرور القاعدة
$Config['DataBase']['Name'] = 'heroku_7af4014c037034a'; // إسم القاعدة
$url=parse_url(getenv("CLEARDB_DATABASE_URL"));

  $server = $url["host"];
  $username = $url["user"];
  $password = $url["pass"];
  $dbname = substr($url["path"],1);
  
//$db = new Database($Config['DataBase']['Host'], $Config['DataBase']['User'], $Config['DataBase']['Password'],
//    $Config['DataBase']['Name']);
$db = new Database($server, $username, $password,
    $dbname);
$db->Connection("intFunction at Line " . __line__);

echo "Connected to db";
?>
