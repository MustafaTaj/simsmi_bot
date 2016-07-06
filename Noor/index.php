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


require_once dirname(FILE) . "/Class.noor.php"; // Load Noor Class ...
$Noor = new Noor; // Call Noor Class
$Runn = $Noor->Get($_GET['q']); // Get Answer // Return is array
echo $Runn['answer']; // Answer of question


?>
