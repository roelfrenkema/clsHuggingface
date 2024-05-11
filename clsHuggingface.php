<?php
/*
 * clsHuggingface.php © 2024 by Roelfrenkema is licensed under CC BY-NC-SA 4.0.
 * To view a copy of this license,
 * visit http://creativecommons.org/licenses/by-nc-sa/4.0/
 *
 */

/* Updates
 *
 * 02-05-2024 - Added Pipe
 * 01-05-2024 - Made the picture properties public. Now user can
 * 		experiment with them.
 * 30-04-2024 - Changes to listmodels that allow for search now
 *              /listmodels <needle>
 *            - changes to methode /setmodel that now uses the model
 *              number given by /listmodels
 *            - added buildin prompts see /listnp and /getnp
 *
 */

class Huggingface
{
    protected const HELP = '
Commands:

/helpme      
This help

/exit
Leave class

/hmodel
Load Huggingface models

/setmodel  <model>
Set model to shortname of model

/listmodels
Listmodels

/setnp
Set a negative prompt

/listnp
list buildin prompts

/getnp 
Get one of the buildin neg.prompts

/loop <prompt>
Loop through loaded models with prompt.

/loadmodels <path/name>
<path/name> from home starting with slash>

/hmodels
load current huggingface models

/shownp
Show current negative prompt

/addnp
Add to current Negative Prompt
';

    protected const INFERENCE = 'https://api-inference.huggingface.co/models/';

    protected $clsVersion = 'v.0.4.0';

    protected $apiKey;      //secure apiKey

    protected $endPoint;    //containing our endpoint

    public $useModels;      //models

    public $curModel;       //curent model used for exif

    protected $sName = 'base';       //shortname for model

    public $pName = 'base';       //public shortname for model

    public $logAll = false;      //logging?

    public $imgStore = '';     //path to store image1

    public $slMax = 300;        //max value for sleep before passing out

    public $slUpdate = 30;     // sleep incremeter

    public $exiv2 = false;        //use exiv2

    public $userPrompt;       //use for prompt

    protected $nPrompt = [
        ['name' => 'common',
            'np' => 'Ugly,Bad anatomy,Bad proportions,Bad quality ,Blurry,Cropped,Deformed,Disconnected limbs ,Out of frame,Out of focus,Dehydrated,Error ,Disfigured,Disgusting ,Extra arms,Extra limbs,Extra hands,Fused fingers,Gross proportions,Long neck,Low res,Low quality,Jpeg,Jpeg artifacts,Malformed limbs,Mutated ,Mutated hands,Mutated limbs,Missing arms,Missing fingers,Picture frame,Poorly drawn hands,Poorly drawn face,Text,Signature,Username,Watermark,Worst quality,Collage ,Pixel,Pixelated,Grainy,',
            'description' => 'A commonly used NP with a broad impact. But nothing special. Set as default NP.'],
        ['name' => 'nomen',
            'np' => '(man:2.0),(men:2.0),(beard:2.0),(moustache:2.0),(bald:2.0),(masculine:2.0),Ugly,Bad anatomy,Bad proportions,Bad quality ,Blurry,Cropped,Deformed,Disconnected limbs ,Out of frame,Out of focus,Dehydrated,Error ,Disfigured,Disgusting ,Extra arms,Extra limbs,Extra hands,Fused fingers,Gross proportions,Long neck,Low res,Low quality,Jpeg,Jpeg artifacts,Malformed limbs,Mutated ,Mutated hands,Mutated limbs,Missing arms,Missing fingers,Picture frame,Poorly drawn hands,Poorly drawn face,Text,Signature,Username,Watermark,Worst quality,Collage ,Pixel,Pixelated,Grainy,',
            'description' => 'The common prompt tailored to avoid men. This can assist in generation static faces for woman from men bases.'],
        ['name' => 'anatomy',
            'np' => 'Bad anatomy, Bad hands, Amputee, Missing fingers, Missing hands, Missing limbs, Missing arms, Extra fingers, Extra hands, Extra limbs , Mutated hands, Mutated, Mutation, Multiple heads, Malformed limbs, Disfigured, Poorly drawn hands, Poorly drawn face, Long neck, Fused fingers, Fused hands, Dismembered, Duplicate , Improper scale, Ugly body, Cloned face, Cloned body , Gross proportions, Body horror, Too many fingers, Cross Eyes,',
            'description' => 'This one concentrates on the anatomy of the subject. Great for groups etc.'],
        ['name' => 'realistic',
            'np' => 'Cartoon, CGI, Render, 3D, Artwork, Illustration, 3D render, Cinema 4D, Artstation, Octane render, Painting, Oil painting, Anime , 2D , Sketch, Drawing , Bad photography, Bad photo, Deviant art,',
            'description' => 'This one is great for photo realistic work, excluding things like 3D etc. '],
        ['name' => 'nsfw',
            'np' => 'nsfw, uncensored, cleavage, nude, nipples, children,',
            'description' => 'use if you have dangerous prompts and dont want to get confronted with unwanted content'],
        ['name' => 'landscape',
            'np' => 'Overexposed, Simple background, Plain background, Grainy , Portrait, Grayscale, Monochrome, Underexposed, Low contrast, Low quality, Dark , Distorted, White spots , Deformed structures, Macro , Multiple angles,',
            'description' => 'A special prompt developed to enhence your landscape artwork'],
        ['name' => 'object',
            'np' => 'Asymmetry , Parts, Components , Design, Broken, Cartoon, Distorted, Extra pieces, Bad proportion, Inverted, Misaligned, Macabre , Missing parts, Oversized , Tilted,',
            'description' => 'Objects can be difficult, this NP takes the sting out of most problems'],
        ['name' => 'clsv1',
            'np' => 'painting, sketch,  plastic, (3d), cgi, semi-realistic, cartoon, ugly, duplicate, morbid, mutilated, extra fingers, mutated hands, poorly drawn hands, poorly drawn face, mutation, deformed, blurry, bad proportions, cloned face, disfigured, out of frame, extra limbs, (bad anatomy), gross proportions, malformed limbs, missing arms, missing legs, extra arms, extra legs, fused fingers, too many fingers, long neck,',
            'description' => 'Alternative for the common prompt'],
        ['name' => 'clsv2',
            'np' => 'ugly, duplicate, morbid, mutilated, extra fingers, mutated hands, poorly drawn hands, poorly drawn face, mutation, deformed, blurry, bad proportions, cloned face, disfigured, out of frame, extra limbs, (bad anatomy), gross proportions, malformed limbs, missing arms, missing legs, extra arms, extra legs, fused fingers, too many fingers, long neck,',
            'description' => 'Alternative for the common prompt'],
        ['name' => 'clsv3',
            'np' => 'out of frame, duplicate, ugly, poorly drawn hands, poorly drawn face, morbid, mutated hands, extra fingers, deformed, blurry, bad anatomy, bad proportions, extra limbs, long neck, cloned face, watermark, signature, text, poorly drawn, normal quality,',
            'description' => 'Alternative for the common prompt'],
    ];

    public $negPrompt = 'Ugly,Bad anatomy,Bad proportions,Bad quality ,Blurry,Cropped,Deformed,Disconnected limbs ,Out of frame,Out of focus,Dehydrated,Error ,Disfigured,Disgusting ,Extra arms,Extra limbs,Extra hands,Fused fingers,Gross proportions,Long neck,Low res,Low quality,Jpeg,Jpeg artifacts,Malformed limbs,Mutated ,Mutated hands,Mutated limbs,Missing arms,Missing fingers,Picture frame,Poorly drawn hands,Poorly drawn face,Text,Signature,Username,Watermark,Worst quality,Collage ,Pixel,Pixelated,Grainy,';     //negative prompt from user

    protected $prePrompt;    //add before prompt

    protected $pastPrompt;    //add after prompt

    public $exiv2User = 'clsHuggingface';     //get your name stamped

    public $exiv2Copy = 'CC BY-NC-SA 4.0';     //copyright info

    protected $userHome;

    protected $userAgent = '';		//Useragent string

    public $wait_for_model = true; // api waiting for reply

    //Higher guidance scale encourages to generate images that are
    //closely linked to the text prompt, usually at the expense of
    //lower image quality. Default 7.5
    public $guidance_scale = 12;

    //num_inference_steps (int, optional, defaults to 50) — The number
    //of denoising steps. More denoising steps usually lead to a higher
    //quality image at the expense of slower inference.
    public $num_inference_steps = 50;

    //By default, SDXL generates a 1024x1024 image for the best results.
    //You can try setting the height and width parameters to 768x768 or
    //512x512, but anything below 512x512 is not likely to work.
    public $width = 1024;

    public $height = 1024;

    public $intModel = 1; // model number used by setModel and loopModels

    public $userPipe = '';

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
            exit("PHP module imagick is needed to run clsStraico. Please install it. Exiting!\n");
            exit;
        }
        if (getenv('INFERENCE_READ')) {
            $this->apiKey = getenv('INFERENCE_READ');
        } else {
            exit('Could not find the API key. Exiting!');
        }
        $this->hugModels();				//get models from Hug
        $this->setModel(1);	//set first model as base
        $this->userHome = $_ENV['HOME'];
        $this->userAgent = 'clsHuggingface.php '.$this->clsVersion.' (Debian GNU/Linux 12 (bookworm) x86_64) PHP 8.2.7 (cli)';

        echo 'Welcome to clsHuggingface '.$this->clsVersion." - enjoy!\n\n";
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

        if ($this->logAll) {
            $this->logString('Main: Endpoint short name: '.$this->sName."\n");
        }

        $httpMethod = 'POST';

        // Prepare query data
        $data = http_build_query(['inputs' => $prompt,
            'wait_for_model' => $this->wait_for_model,
            'negative_prompt' => $this->negPrompt,
            'guidance_scale' => $this->guidance_scale,
            'num_inference_steps' => $this->num_inference_steps,
            'width' => $this->width,
            'height' => $this->height,
        ],
        );

        // Prepare options
        $options = [
            'http' => [
                'header' => 'Authorization: Bearer '.$this->apiKey."\r\n".
                        "x-use-cache: 0\r\n".
                        "Content-Type: application/json\r\n".
                       'User-Agent: '.$this->userAgent." \r\n",
                'method' => $httpMethod,
                'content' => $data,
            ],
        ];

        // Create stream
        $context = stream_context_create($options);

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

            // Temporarily disable error reporting
            $previous_error_reporting = error_reporting(0);

            // Communicate
            $result = @file_get_contents($this->endPoint, false, $context);

            // Restore the previous error reporting level
            error_reporting($previous_error_reporting);

            // Check if an error occurred
            if ($result === false) {

                $error = error_get_last();

                if ($error !== null) {
                    if (str_contains($error['message'], '503')) {
                        $timer += $this->slUpdate;
                        echo 'Model '.$this->sName." not loaded yet, trying again in $timer seconds\n";
                        if ($this->logAll) {
                            $this->logString('Main: Model '.$this->sName." not loaded yet, trying again in $timer seconds\n");
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

        $answer = $this->writeImage($result);

        if ($this->userPipe) {
            $this->apiPipe();
        }

        return $answer;

    }

    /*
    * Function: apiPipe()
    * Input   : none
    * Output  : a shell pipe
    * Purpose : Use apiOutput elsewhere
    *
    * Remarks:
    *
    */
    private function apiPipe()
    {

        if (! $this->userPipe) {
            return;
        }

        //tokenreplacement
        $temp = str_ireplace('%prompt%', $this->userPrompt, $this->userPipe);

        //        $temp2 = str_ireplace('%answer%', $this->aiAnswer, $temp);

        `$temp`;

    }

    public function checkUserInput($timeout = 0)
    {
        $read = [STDIN];
        $write = [];
        $except = [];

        // Check if there's any available data from standard input within the specified timeout (optional).
        if (stream_select($read, $write, $except, $timeout)) {
            $input = trim(fgets(STDIN));

            return $input;
        }
    }

    public function getNp($userInput)
    {

        foreach ($this->nPrompt as $item) {

            if ($item['name'] == $userInput) {
                $this->negPrompt = $item['np'];

                return 'Prompt set to '.$item['name'];
            }
        }

        return 'Requested prompt not found. Check name.';
    }

    public function setModel($input)
    {
        $this->curModel = $this->useModels[$input - 1]['model'];
        $this->endPoint = Huggingface::INFERENCE.$this->useModels[$input - 1]['model'];

        $this->sName = $this->useModels[$input - 1]['tag'];
        $this->pName = $this->useModels[$input - 1]['tag'];
        $this->prePrompt = $this->useModels[$input - 1]['pre'];
        $this->pastPrompt = $this->useModels[$input - 1]['past'];

        return "\nModel is: $this->sName\n";
    }

    protected function hugModels()
    {
        $endpoint = 'https://api-inference.huggingface.co/framework/diffusers';

        $options = [
            'http' => [
                'header' => "Content-Type: application/json\r\n",
                'method' => 'GET',
            ],
        ];

        // Create stream
        $context = stream_context_create($options);

        $result = file_get_contents($endpoint, false, $context);

        $answer = json_decode($result, true);

        $this->useModels = [];

        //create or own format model list
        foreach ($answer as $model) {

            if ($model['task'] !== 'text-to-image') {
                continue;
            }

            $fname = $model['model_id'];
            $name = explode('/', $fname);
            $tag = $name[1];

            $this->useModels[] = ['tag' => $tag,
                'model' => $fname,
                'pre' => '',
                'past' => '',
            ];
        }
    }

    protected function listModels($searchString = null)
    {
        $modelsFound = [];
        $point = 0;

        // Iterate over models and check if they match the search string (case-insensitive)
        foreach ($this->useModels as $arModel) {
            $point++;

            // If no search string is provided or if the current model matches the search string, proceed to display it
            if (! $searchString || stripos($arModel['model'], $searchString) !== false) {

                // Add the current model to the found models array
                $modelsFound[] = [
                    'counter' => $point,
                    'name' => $arModel['tag'],
                    'model' => $arModel['model'],
                ];

                // Display the model information
                echo "Model $point:\n";
                //               if ($arModel['model'] === $this->aiModel) {
                //                   echo '* ';
                //               }
                echo "- Name: {$arModel['tag']}\n";
                echo "- Model: {$arModel['model']}\n";
                echo "---\n";
            }
        }

        return "INFO: Models list done!\n";
    }

    public function listNp()
    {
        foreach ($this->nPrompt as $item) {
            echo 'Prompt name: '.$item['name'].", \nDescription: ".$item['description']."\n\n";
        }

        return 'INFO: list completed!';
    }

    public function loopModels($prompt)
    {

        //store current model.
        $storeName = $this->intModel;
        $this->userPrompt = $prompt;

        //prevent repetitious pipe
        if ($this->userPipe) {
            $this->apiPipe();
        }
        $storePipe = $this->userPipe;
        $this->userPipe = '';

        $mp = 0;

        foreach ($this->useModels as $model) {

            $response = '';
            $mp++;

            //set endpoint
            $this->setModel($mp);

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
            } elseif ($response == $this->slMax) {
                echo "\nModel skipped due to sleeptimeout, sorry.\n";
                if ($this->logAll) {
                    $this->logString("Loop: Model skipped due to sleeptimeout, sorry.\n");
                }
            } else {
                echo "$response\n";
            }

        }

        // restore endPoint
        $this->setModel($storeName);
        $this->userPipe = $storePipe;
    }

    public function logString($string)
    {

        $log = $this->imgStore.'/HuggingFace.log';

        file_put_contents($log, date('Y-m-d H:i:s').' '.$string, FILE_APPEND);

    }

    /*
    * Function: stopPrompt()
    * Input   : none
    * Output  : stops execution with errorcode 0
    * Purpose : stop app
    *
    * Remarks:
    *
    * Private function used by $this->userPrompt()
    */
    protected function stopPrompt()
    {
        echo 'Join Straico via https://platform.straico.com/signup?fpr=roelf14'."\n";
        echo "Thank you and have a nice day.\n";
        exit(0);
    }

    private function writeImage($blob)
    {

        $image = new Imagick();
        $image->readImageBlob($blob);
        $image->setImageFormat('png');
        // Set metadata using Imagick
        if ($this->exiv2) {
            $image->setImageProperty('Xmp.PostivePrompt', $this->userPrompt);
            $image->setImageProperty('Xmp.NegativePrompt', $this->negPrompt);
            $image->setImageProperty('Xmp.photoshop.Credit', $this->exiv2User);
            $image->setImageProperty('Xmp.dc.rights', $this->exiv2Copy);
            $image->setImageProperty('Xmp.xmp.CreatorTool', 'clsHuggingface '.$this->clsVersion);
            $image->setImageProperty('Xmp.LyconAIModel', $this->curModel);
        }

        $id = $this->imgStore.$this->sName.'-'.date('YmdHis').'.png';
        $image->writeImage($id);

        if ($this->logAll) {
            $this->logString("Main: Image stored as $id\n");
        }

        return "Image stored as $id";
    }

    public function loadModels($tpath)
    {
        if (! is_file($tpath)) {
            echo $tpath." not found!\n";
        } else {
            $this->useModels = [];
            include $tpath;

            return $tpath." loaded!\n";
        }
    }
}
