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
session_start();
/*$sessionfile = fopen("sessionfile.txt", "r");
session_decode(fputs($sessionfile,  4096) );
fclose($sessionfile);*/

echo "Session - " . $_SESSION["testing"];
$_SESSION["testing"] = "tt" ;

echo "<br />" . session_encode( ) ;
$sessionfile = fopen("sessionfile.txt", "w");
fputs($sessionfile, session_encode( ) );
fclose($sessionfile);
?>=======================================*/
session_start();
$sessionfile = fopen("sessionfile.txt", "r");
session_decode(fputs($sessionfile,  4096) );
fclose($sessionfile);

echo "Session - " . $_SESSION["testing"];
$_SESSION["testing"] = "tt" ;

echo "<br />" . session_encode( ) ;
$sessionfile = fopen("sessionfile.txt", "w");
fputs($sessionfile, session_encode( ) );
fclose($sessionfile);
?>
