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
ini_set('session.save_handler', 'memcached');
ini_set('session.save_path', 'mc3.dev.ec2.memcachier.com:11211');
ini_set('memcached.sess_binary', 1);
ini_set('memcached.sess_sasl_username', '29667b');
ini_set('memcached.sess_sasl_password', '412a446a87da30a3081abd036ac09202');

session_start();
echo "test1: "; print_r($_SESSION) ;
$_SESSION["testing"] = "tt" ;

?>
