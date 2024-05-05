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

// setting paths and including what we need
set_include_path(get_include_path() . PATH_SEPARATOR . $home.'/git/clsHuggingface');
set_include_path(get_include_path() . PATH_SEPARATOR . $home.'/git/clsStraico');

require_once('clsStraico.php');
require_once('clsHugchatCli.php');

require_once $home.'/git/clsHuggingface/vendor/autoload.php';

use function Laravel\Prompts\info;
use function Laravel\Prompts\textarea;

$hug = new HugchatCli;

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

/*
 * Get your own models
 * If you dont a list will be retrieved at Huggingface
 */
$hug->loadModels('/git/clsHuggingface/chat_base.php');
$hug->setModel = 'meta-llama/Meta-Llama-3-70B-Instruct';

/*
    Start looping till finished with /exit
*/

while( $aiMessage !== "/exit" ){

  
    // lavarel input box
    $prompt = textarea('<fg=white>Prompting: '.$hug->aiModel.' in Role: '.$hug->pubRole.'</>');
    
    // process prompt
    $aiMessage = $hug->sPrompt($prompt);
  
    // no input available?
    if (! $aiMessage) continue;
   
    // native answer
    echo $aiMessage."\n";
}
