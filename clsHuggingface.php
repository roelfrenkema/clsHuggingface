<?php
/*
 * clsHuggingface.php © 2024 by Roelfrenkema is licensed under CC BY-NC-SA 4.0.
 * To view a copy of this license,
 * visit http://creativecommons.org/licenses/by-nc-sa/4.0/
 *
 */

class Huggingface
{
    private const INFERENCE = 'https://api-inference.huggingface.co/models/';

    private $apiKey;      //secure apiKey

    private $endPoint;    //containing our endpoint

    public $useModels;      //models

    private $sName;       //shortname for model

    public $logAll;      //logging?

    public $imgStore;     //path to store image1

    public $slMax;        //max value for sleep before passing out

    public $slUpdate;     // sleep incremeter

    public $exiv2;        //use exiv2

    public $userPrompt;       //use for prompt

    public $negPrompt;     //negative prompt from user

    private $prePrompt;    //add before prompt

    private $pastPrompt;    //add after prompt

    public $exiv2User;     //get your name stamped

    public $exiv2Copy;     //copyright info

    private $userHome;

    /*
    * Function: __construct
    * Input   : not applicable
    * Output  : none
    * Purpose : sets initial values on instantiating class
    *
    * Remarks:
    *
    * This is a great place to set your initial values like your
    * own language or the model to work with.
    * TODO: check for module tidy
    */

    public function __construct()
    {

        //check for module tidy
        if (! extension_loaded('readline')) {
            echo "PHP module readline is needed to run clsStraico. Please install it. Exiting!\n";
            exit;
        }
        if (! extension_loaded('openssl')) {
            echo "PHP module openssl is needed to run clsStraico. Please install it. Exiting!\n";
            exit;
        }
        if (! extension_loaded('imagick')) {
            echo "PHP module imagick is needed to run clsStraico. Please install it. Exiting!\n";
            exit;
        }
        if (getenv('INFERENCE_READ')) {
            $this->apiKey = getenv('INFERENCE_READ');
        } else {
            echo 'Could not find the API key. Exiting!';
            exit(-1);
        }
        $this->useModels = [];
        $this->imgStore = '';
        $this->slUpdate = 30;
        $this->slMax = 300;
        $this->exiv2 = false;
        $this->exiv2User = 'clsHuggingface';
        $this->exiv2Copy = 'CC BY-NC-SA 4.0';
        $this->negPrompt = 'distortion';
        $this->logAll = false;
        $this->userHome = $_ENV['HOME'];

        echo "Welcome to clsHuggingface v1.0.0 - enjoy!\n\n";

    }
    /*
    * Function: $userPrompt()
    * Input   : keystrokes.
    * Output  : depends on user input
    * Purpose : run the class
    *
    * Remarks:
    *
    * TODO: public function needs cleanup for readability.
    */

    public function userPrompt()
    {

        $input = readline('> ');

        // Add  to session history
        readline_add_history($input);

        // End cls session on cli
        if ($input == '/exit') {
            exit;

            //loadmodels
        } elseif (substr($input, 0, 11) == '/loadmodels') {
            $this->loadModels(trim(substr($input, 12)));

            // Set negPromp
        } elseif (substr($input, 0, 6) == '/setnp') {
            $this->negPrompt = substr($input, 7);
            echo "Negative prompt: $this->negPrompt\n";

            // Add to negPromp
        } elseif (substr($input, 0, 6) == '/addnp') {
            $this->negPrompt .= substr($input, 7);
            echo "Negative prompt: $this->negPrompt\n";

            // Set model
        } elseif (substr($input, 0, 9) == '/setmodel') {
            $answer = $this->setModel(substr($input, 10));

            // Show current negative prompt
        } elseif ($input == '/shownp') {
            echo "Negative prompt: $this->negPrompt\n";

            // list model
        } elseif ($input == '/listmodels') {

            foreach ($this->useModels as $model) {
                echo $model['tag'].' - '.$model['model']."\n";
            }

            // loop prompt through models
        } elseif (substr($input, 0, 5) == '/loop') {
            $this->loopModels(substr($input, 6));

            // logon
        } elseif ($input == '/logon') {
            $this->logAll = true;
            echo 'Log enabled.';

            // logon
        } elseif ($input == '/logoff') {
            $this->logAll = false;
            echo 'Log disabled.';

            //  help
        } elseif ($input == '/helpme') {
            $this->help();

            //  lastcheck on not existing command
        } elseif (substr($input, 0, 1) == '/') {
            $answer = "\nWARNING COMMAND: $input UNKNOWN\n\n";
            $this->help();

            // Proccess
        } else {
            $this->userPrompt = trim($this->prePrompt.' '.$input.' '.$this->pastPrompt);
            $answer = $this->apiCompletion($this->userPrompt);
        }

        echo "$answer\n";
    }

    /*
     * Function: apiCompletion($aiMessage)
     * Input   : $aiMessage - is the prompt
     * Output  : returns response content
     * Purpose : Complete an API call with the prompt info
     *
     * Remarks:
     *
     * Returns the response content. At later time this will be
     * adjusted to reflect the other information
     */

    public function apiCompletion()
    {

        $prompt = trim($this->prePrompt.' '.$this->userPrompt.' '.$this->pastPrompt);

        echo "\n\nEndpoint short name: ".$this->sName."\n";
        //echo "Prompt : ".$input."\n";

        if ($this->logAll) {
            $this->logString('Main: Endpoint short name: '.$this->sName."\n");
        }

        $httpMethod = 'POST';

        // Prepare query data
        $data = http_build_query(['inputs' => $prompt,
            'wait_for_model' => true,
            'negative_prompt' => $this->negPrompt,
            'guidance_scale' => 30,
            'num_inference_steps' => 50,
            'width' => 1024,
            'height' => 1024,
        ],
        );

        // Prepare options
        $options = [
            'http' => [
                'header' => 'Authorization: Bearer '.$this->apiKey."\r\n".
                        "x-use-cache: 0\r\n".
                        "Content-Type: application/json\r\n".
                        "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.84 Safari/537.36\r\n",
                'method' => $httpMethod,
                'content' => $data,
            ],
        ];

        // Create stream
        $context = stream_context_create($options);

        // Temporarily disable error reporting
        $previous_error_reporting = error_reporting(0);

        // Start pulling session
        $timer = 0;
        $pointer = true;

        while ($pointer = true) {

            if ($timer > $this->slMax) {
                echo "\nModel skipped due to sleeptimeout, sorry.\n";
                if ($this->logAll) {
                    $this->logString("Main: Model skipped due to sleeptimeout, sorry.\n");
                }

                return $this->slMax;
            }

            sleep($timer);

            // Communicate
            $result = @file_get_contents($this->endPoint, false, $context);

            // Check if an error occurred
            if ($result === false) {

                $error = error_get_last();

                // Restore the previous error reporting level
                error_reporting($previous_error_reporting);

                if ($error !== null) {
                    if (str_contains($error['message'], '503')) {
                        $timer += $this->slUpdate;
                        echo "Model not loaded yet, trying again in $timer seconds\n";
                        if ($this->logAll) {
                            $this->logString("Main: Model not loaded yet, trying again in $timer seconds\n");
                        }
                        $result = '';

                        continue;
                    } elseif (str_contains($error['message'], '500')) {
                        echo "Server error 500, returning.\n";
                        if ($this->logAll) {
                            $this->logString("Main: Server error 500, returning.\n");
                        }

                        return 500;
                    } elseif ((str_contains($error['message'], '429')) || (str_contains($error['message'], '403'))) {
                        echo "Too many request. Returning.\n";
                        if ($this->logAll) {
                            $this->logString("Main: Too many request. Returning.\n");
                        }

                        return 429;
                    } else {
                        $timer += 30;
                        $granate = explode(':', $error['message']);
                        echo end($granate)." not acted on.\n";
                        if ($this->logAll) {
                            $this->logString('Main: '.end($granate)." not acted on.\n");
                        }

                        return 254;
                    }
                } else {
                    echo "An unknown error occurred while fetching the webpage. Please try again!\n";
                    if ($this->logAll) {
                        $this->logString("Main: An unknown error occurred while fetching the webpage. Please try again!\n");
                    }

                    return 255;
                }
            }
            break; // we have a picture
        } //end while

        // Write the image.

        $this->writeImage($result);

        return 0;

    }

    public function setModel($input)
    {

        foreach ($this->useModels as $model) {

            if ($model['tag'] == trim($input)) {

                $this->endPoint = Huggingface::INFERENCE.$model['model'];
                $this->sName = $model['tag'];
                $this->prePrompt = $model['pre'];
                $this->pastPrompt = $model['past'];
            }
        }

        return "\nModel is: $this->sName\n";
    }

    private function help()
    {
        echo '
Commands:

/helpme      
This help

/exit
Leave class

/setmodel  <model>
Set model to shortname of model

/listmodels
Listmodels

/setnp
Set a negative prompt

/loop <prompt>
Loop through loaded models with prompt.

/loadmodels <path/name>
<path/name> from home starting with slash>

/shownp
Show negative prompt

/addnp
Add to current Negative Prompt
';
    }

    public function loopModels($prompt)
    {

        //store current model.
        $storeSname = $this->sName;
        $this->userPrompt = $prompt;

        foreach ($this->useModels as $model) {
            $response = '';

            //set endpoint
            $this->setModel($model['tag']);

            $response = $this->apiCompletion();

            if ($response == 429) {
                echo "\nStopping an hour due to API ratelimiting, sorry.\n";
                if ($this->logAll) {
                    $this->logString("Loop:Stopping an hour due to API ratelimiting, sorry.\n");
                }
                sleep(3600);
            } elseif ($response == 500) {
                echo "\nModel skipped due to server error, sorry.\n";
                if ($this->logAll) {
                    $this->logString("Loop: Model skipped due to server error, sorry.\n");
                }
            } elseif (! $response == '0') {
                echo "\nModel skipped due to $response, sorry.\n";
                if ($this->logAll) {
                    $this->logString("Loop: Model skipped due to $response, sorry.\n");
                }
            } elseif ($response == $this->slMax) {
                echo "\nModel skipped due to sleeptimeout, sorry.\n";
                if ($this->logAll) {
                    $this->logString("Loop: Model skipped due to sleeptimeout, sorry.\n");
                }
            }

        }

        // restore endPoint
        $this->setModel($storeSname);

    }

    public function logString($string)
    {

        $log = $this->imgStore.'/log.txt';

        file_put_contents($log, date('Y-m-d H:i:s').' '.$string, FILE_APPEND);

    }

    private function setExif($aiMessage, $id)
    {

        $myM = '-M"set Exif.Image.ImageDescription '."\nPrompt: ".$this->userPrompt."\n\nNeg: ".$this->negPrompt.'"';
        $myM .= '-M"set Iptc.Application2.Subject '."\nPrompt: ".$this->userPrompt."\n\nNeg: ".$this->negPrompt.'"';
        $myM .= ' -M"set Xmp.plus.ImageSupplierName '.$this->exiv2User.'"';
        $myM .= ' -M"set Xmp.dc.creator '.$this->exiv2User.'"';
        $myM .= ' -M"set Xmp.dc.rights '.$this->exiv2Copy.'"';
        $myM .= ' -M"set Iptc.Application2.Copyright '.$this->exiv2Copy.'"';
        $myM .= ' -M"set Xmp.photoshop.Credit '.$this->exiv2User.'"';
        $myM .= ' -M"set Xmp.xmp.CreatorTool clsHuggingface"';
        $myM .= ' -M"set Exif.Image.Software clsHuggingface"';
        $myM .= ' -M"set Exif.Photo.UserComment '.$this->endPoint.'"';
        $myM .= ' -M"set Iptc.Application2.Subject '.$this->endPoint.'"';

        $test = shell_exec("exiv2 $myM ".$id);
    }

    private function writeImage($blob)
    {

        $image = new Imagick();
        $image->readImageBlob($blob);
        $image->setImageFormat('png');
        $id = $this->imgStore.$this->sName.'-'.date('jmdHms').'.png';
        $image->writeImage($id);
        if ($this->exiv2) {
            $this->setExif($aiMessage, $id);
        }
        echo "\nImage stored as $id\n";
        if ($this->logAll) {
            $this->logString("Main: Image stored as $id\n");
        }

        return 0;

    }

    public function loadModels($tpath)
    {
        if (! is_file($this->userHome.$tpath)) {
            echo $this->userHome.$tpath." not found!\n";
        } else {
            $this->useModels = [];
            include $this->userHome.$tpath;
            echo $this->userHome.$tpath." loaded!\n";
        }
    }
}
