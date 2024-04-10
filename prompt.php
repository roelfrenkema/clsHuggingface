#!/usr/bin/php

<?php
/*
 * clsStraico.php © 2024 by Roelfrenkema is licensed under CC BY-NC-SA 4.0. 
 * To view a copy of this license, 
 * visit http://creativecommons.org/licenses/by-nc-sa/4.0/
 * 
 * visit Straico https://platform.straico.com/signup?fpr=roelf14
 */

/*
   Initialize start values.
*/

include('clsHuggingface.php');

$aiMessage = "";
$hug = new Huggingface;

$hug->imgStore = '/home/roelf/clsHuggingface/';

/* 
    Welcome message with model info.
*/


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
