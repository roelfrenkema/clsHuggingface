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

/*
 * Logpath now equals a working directory. All files are saved here like
 * f.i. the history files omitting the need to use a path in their names
 * logfiles will have extension log and history files will have the
 * extension hist.
 */ 
$hug->logAll = false;
$hug->logPath = '/home/';

/*
* pipe setting
* placeholder %prompt% and %answer will be replaced by the prompt and AI answer.
* then it will be executed in a shell.
* 
* $hug->userPipe = 'echo "%prompt%" >> ~/myprompts.txt';
*/


/*
 * The history switch is used to set chatformat on or off at start.
 * When it is on user input and ai out put are stacked to build a
 * true conversation.
 */
$hug->historySwitch = true;

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
