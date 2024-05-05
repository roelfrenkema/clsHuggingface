#!/usr/bin/env php
<?php
class Cli extends Huggingface
{
/*
 * Dit wordt de uiteindelijke stuurfunctie. Hier worden alle commando's
 * gelezen en de uiteindelijke uitvoering gestuurd.
 * De bedoeling is dat daarmee de hoofd class beter herbruikbaar word. 
 */
   public function __construct()
   {

        if (getenv('INFERENCE_READ')) {
            $this->apiKey = getenv('INFERENCE_READ');
        } else {
	    exit('Could not find environment variable INFERENCE_READ with the API key. Exiting!');
        }

        parent::hugModels();				//get models from Hug
        parent::setModel(1);	//set first model as base

 	
        $this->userAgent = 'clsHuggingface.php '.$this->clsVersion.' (Debian GNU/Linux 12 (bookworm) x86_64) PHP 8.2.7 (cli)';
	$this->userPipe ='';
    }
    
    public function cliPrompt($input)
    {
	
        $input = trim($input);

	    // End cls session on cli
        if ($input == '/exit') {
            parent::stopPrompt();

            //  help
        } elseif ($input == '/helpme') {
            $answer = parent::HELP;

            //loadmodels
        } elseif (substr($input, 0, 11) == '/loadmodels') {
            $answer = parent::loadModels(substr($input, 12));

            // List available models
        } elseif (substr($input, 0, 11) == '/listmodels') {
            $answer = parent::listModels(substr($input, 12));

            // Set model
        } elseif (substr($input, 0, 9) == '/setmodel') {
            $answer = parent::setModel(substr($input, 10));

            //  huggingface models
        } elseif ($input == '/hmodels') {
            $answer = parent::hugModels();

            // logon
        } elseif ($input == '/logon') {
            $this->logAll = true;
            $answer = 'Log enabled.';

            // logoff
        } elseif ($input == '/logoff') {
            $this->logAll = false;
            $answer = 'Log disabled.';

            // List negative prompts
        } elseif (substr($input, 0, 7) == '/listnp') {
            $answer = parent::listNp(substr($input, 8));

            // Show current negative prompt
        } elseif ($input == '/shownp') {
            $answer = "Negative prompt: $this->negPrompt\n";

            // Add to negPromp
        } elseif (substr($input, 0, 6) == '/addnp') {
            $this->negPrompt .= substr($input, 7);
            $answer = "Negative prompt: $this->negPrompt\n";

            // Set negPromp
        } elseif (substr($input, 0, 6) == '/setnp') {
            $this->negPrompt = substr($input, 7);
            $answer = "Negative prompt: $this->negPrompt\n";

            // Get buildin promp
        } elseif (substr($input, 0, 6) == '/getnp') {
            $answer = parent::getNp(substr($input, 7));

            // loop prompt through models
        } elseif (substr($input, 0, 5) == '/loop') {
            parent::loopModels(substr($input, 6));
            $answer = "Loop done. \n\n";

            //  lastcheck on not existing command
        } elseif (substr($input, 0, 1) == '/') {
            $answer = parent::HELP;
            $answer .= "\nWARNING COMMAND: $input UNKNOWN\n\n";

            // Proccess
        } else {
            $this->userPrompt = trim($this->prePrompt.' '.$input.' '.$this->pastPrompt);
            $answer = parent::apiCompletion($this->userPrompt);
        }

        return $answer;
    }
}

?>
