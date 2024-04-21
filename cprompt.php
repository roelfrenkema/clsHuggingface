#!/usr/bin/php

<?php
/*
 * clsHugginface.php © 2024 by Roelfrenkema is licensed under CC BY-NC-SA 4.0. 
 * To view a copy of this license, 
 * visit http://creativecommons.org/licenses/by-nc-sa/4.0/
 * 
 */

//Only for external classes pulled with composer
//require_once'vendor/autoload.php

$home = $_ENV['HOME'];

/*
   Initialize start values.
*/

set_include_path($home.'/git/clsHuggingface');

include('clsHugChat.php');

$aiMessage = "";
$hug = new HugChat;

// Logging true/false
$hug->logAll = false;

// Timeout for /loop
$hug->slMax = 300;

// Amount to add to sleep at each loop
$hug->slUpdate = 30;

//getmodels
$hug->loadModels('/git/clsHuggingface/chat_base.php');
$hug->setModel = "meta-llama/Meta-Llama-3-70B-Instruct";

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
