#!/usr/bin/env php
<?php
class Cli extends Huggingface
{
/*
 * Dit wordt de uiteindelijke stuurfunctie. Hier worden alle commando's
 * gelezen en de uiteindelijke uitvoering gestuurd.
 * De bedoeling is dat daarmee de hoofd class beter herbruikbaar word. 
 */
 
    public function cliPrompt($input)
    {
        $input = trim($input);

        // End cls session on cli
        if ($input == '/exit') {
            parent::stopPrompt();

            //loadmodels
        } elseif (substr($input, 0, 11) == '/loadmodels') {
            $answer = parent::loadModels(substr($input, 12));

            // List available models
        } elseif (substr($input, 0, 11) == '/listmodels') {
            $answer = parent::listModels(substr($input, 12));

            // Set model
        } elseif (substr($input, 0, 9) == '/setmodel') {
            $answer = parent::setModel(substr($input, 10));

            // List negative prompts
        } elseif (substr($input, 0, 7) == '/listnp') {
            $answer = parent::listNp(substr($input, 8));

/*
            // Set negPromp
        } elseif (substr($input, 0, 6) == '/setnp') {
            $this->negPrompt = substr($input, 7);
            $answer = "Negative prompt: $this->negPrompt\n";

            // Get buildin promp
        } elseif (substr($input, 0, 6) == '/getnp') {
            $answer = $this->getNp(substr($input, 7));

            // Add to negPromp
        } elseif (substr($input, 0, 6) == '/addnp') {
            $this->negPrompt .= substr($input, 7);
            $answer = "Negative prompt: $this->negPrompt\n";

            // Set model
        } elseif (substr($input, 0, 9) == '/setmodel') {
            $answer = $this->setModel(substr($input, 10));

            // Show current negative prompt
        } elseif ($input == '/shownp') {
            $answer = "Negative prompt: $this->negPrompt\n";


            // List negative prompts
        } elseif (substr($input, 0, 7) == '/listnp') {
            $this->listNp(substr($input, 8));
            $answer = '';

            // loop prompt through models
        } elseif (substr($input, 0, 5) == '/loop') {
            $this->loopModels(substr($input, 6));
            $answer = "Loop done. \n\n";

            // logon
        } elseif ($input == '/logon') {
            $this->logAll = true;
            $answer = 'Log enabled.';

            // logon
        } elseif ($input == '/logoff') {
            $this->logAll = false;
            $answer = 'Log disabled.';

            //  help
        } elseif ($input == '/helpme') {
            $answer = Huggingface::HELP;

            //  huggingface models
        } elseif ($input == '/hmodels') {
            $answer = $this->hugModels();

            //  lastcheck on not existing command
        } elseif (substr($input, 0, 1) == '/') {
            $answer = Huggingface::HELP;
            $answer .= "\nWARNING COMMAND: $input UNKNOWN\n\n";

            // Proccess
        } else {
            $this->userPrompt = trim($this->prePrompt.' '.$input.' '.$this->pastPrompt);
            $answer = $this->apiCompletion($this->userPrompt);
*/  
        }

        return $answer;
    }
}

?>
