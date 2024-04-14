#!/usr/bin/php

<?php
/*
 * clsHugginface.php © 2024 by Roelfrenkema is licensed under CC BY-NC-SA 4.0. 
 * To view a copy of this license, 
 * visit http://creativecommons.org/licenses/by-nc-sa/4.0/
 * 
 */

$home = $_ENV['HOME'];

/*
   Initialize start values.
*/

set_include_path($home.'/git/clsHuggingface');

include('clsHuggingface.php');

$aiMessage = "";
$hug = new Huggingface;

$hug->imgStore = $home.'/git/clsHuggingface';
$hug->exiv2 = true;
$hug->exiv2User = "Roelf Renkema";
$hug->exiv2Copy = 'CC BY-NC-SA 4.0';
$hug->logAll = true;
$hug->slMax = 300;
$hug->slUpdate = 30;

//getmodels
include($hug->imgStore.'mdl_base.php');

// set startmodel
$hug->setModel('base');

// default set your favorite neg prompt
$hug->negPrompt = '-((urban elements)):0.8, -((daytime)):0.7, -((animals)):0.9, -((people)):0.9, -((vehicles)):0.8, -((modern buildings)):0.7.';


/*
    Start looping till finished with /exit
*/

while( $aiMessage !== "/exit" ){

  //prompt
  $aiMessage = $hug->userPrompt();
  echo "\n";
  
  //No input available?
  if ($aiMessage == "") continue;
  
}
?>
