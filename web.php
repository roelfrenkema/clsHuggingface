<?php
/*
 * clsHugginface.php © 2024 by Roelfrenkema is licensed under CC BY-NC-SA 4.0. 
 * To view a copy of this license, 
 * visit http://creativecommons.org/licenses/by-nc-sa/4.0/
 *
 * This prompt uses Lavarel their copyright can be found 
 * https://laravel.com/trademark
 * visit http://creativecommons.org/licenses/by-nc-sa/4.0/
 * 
 */

//Only for external classes pulled with composer
//require_once'vendor/autoload.php'

$home = $_ENV['HOME'];

set_include_path($home.'/git/clsHuggingface');

include('clsHugChat.php');
require_once $home.'/git/clsHuggingface/vendor/autoload.php';

use function Laravel\Prompts\textarea;
use function Laravel\Prompts\info;

$hug = new HugChat;
$hug->aiLog = true;
$hug->logPath = $home.'/hugimages/';
$hug->historySwitch = true;
$hug->loadModels('/git/clsHuggingface/chat_base.php');
$hug->setModel(1) ;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prompt = textarea('<fg=cyan>Prompting: '.$hug->aiModel.' in Role: '.$hug->pubRole.'</>');
    $aiMessage = $hug->userPrompt($prompt);
    info('<fg=cyan>'.$aiMessage.'</>');
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <label for="prompt">Prompt:</label><br>
  <textarea name="prompt" id="prompt" rows="4" cols="50"></textarea><br>
  <input type="submit" value="Submit">
</form>
