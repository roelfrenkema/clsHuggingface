#!/usr/bin/php

<?php
/*
 * clsHugginface.php © 2024 by Roelfrenkema is licensed under CC BY-NC-SA 4.0.
 * To view a copy of this license,
 * visit http://creativecommons.org/licenses/by-nc-sa/4.0/
 *
 */


/*
 *  We asume for this example that your installation lives in
 *  the directory git under your homedirectory. You have to make
 *  changes yourself if needed
 */

$home = $_ENV['HOME'];

// setting paths and including what we need
set_include_path(get_include_path() . PATH_SEPARATOR . $home.'/git/clsHuggingface');

require_once $home.'/git/clsHuggingface/vendor/autoload.php';

use function Laravel\Prompts\info;
use function Laravel\Prompts\textarea;

require_once('clsHuggingface.php');
require_once('clsCli.php');


$hug = new Cli;


$hug->imgStore = $home.'/git/clsHuggingface/';
$hug->exiv2 = false;
$hug->exiv2User = 'Roelf Renkema';
$hug->exiv2Copy = 'CC BY-NC-SA 4.0';
$hug->logAll = false;
$hug->slMax = 300;
$hug->slUpdate = 30;

/*
 * Set your own models array
 * If you don't models will be retrieved from Huggingface
 */
//$hug->loadModels($home.'/git/clsHuggingface/mdl_base.php');

/*
 * Set startprompt. Needed to find your model a start.
 */
//$hug->setModel('base');


/*
 * You can also set one of the buildin negative prompts
 * find there names with /listnp on the prompt
 */

$hug->getNp('common');

/*
    Start looping till finished with /exit
*/
$aiMessage = '';

while ($aiMessage !== '/exit') {

    //prompt
    $prompt = textarea('<fg=white>Prompting: '.$hug->pName.'</>');

    // process prompt
    $aiMessage = $hug->cliPrompt($prompt);

    // no input available?
    if (! $aiMessage) {
        continue;
    }

    // native answer
    echo $aiMessage."\n";

    //lavarel answer
    //info('<fg=cyan>'.$aiMessage.'</>');

}
?>
