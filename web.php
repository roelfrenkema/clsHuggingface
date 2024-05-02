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

require_once './clsHugChat.php';
require_once './vendor/autoload.php';

$hug = new HugChat;
$hug->aiLog = true;
$hug->logPath = '/';
$hug->historySwitch = true;
$hug->loadModels('chat_base.php');
$hug->setModel(1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    echo "\n\n<br><br>".$_POST['prompt']."\n<br>";

    $text = $hug->userPrompt($_POST['prompt']);
    echo "\n\n<br>".$text."\n<br><br>";
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
  <label for="prompt">Prompt:</label><br>
  <textarea name="prompt" id="prompt" rows="4" cols="50"></textarea><br>
  <input type="submit" value="Submit">
</form>
