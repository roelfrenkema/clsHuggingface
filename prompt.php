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

//where to store images
$hug->imgStore = $home.'/git/clsHuggingface';

// Exif info for use with exiv2
$hug->exiv2 = false; // set to true if you have exiv2 
$hug->exiv2User = "Roelf Renkema";
$hug->exiv2Copy = 'CC BY-NC-SA 4.0';

// Logging true/false
$hug->logAll = false;

// Timeout for /loop
$hug->slMax = 300;

// Amount to add to sleep at each loop
$hug->slUpdate = 30;

//getmodels
$hug->loadModels('/git/clsHuggingface/mdl_base.php');

// set startmodel
$hug->setModel('base');

// default set neg prompt
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
