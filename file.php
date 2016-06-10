<?php
session_start();
echo "Hello #" . $_SESSION['count'];
if (!isset($_SESSION['count'])) {
    $_SESSION['count'] = 0;
}
$_SESSION['count']++;


?>
