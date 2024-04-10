<?php
/*
 * clsStraico.php © 2024 by Roelfrenkema is licensed under CC BY-NC-SA 4.0. 
 * To view a copy of this license, 
 * visit http://creativecommons.org/licenses/by-nc-sa/4.0/
 *
 * visit Straico https://platform.straico.com/signup?fpr=roelf14
 */

class Huggingface {
	
	private const INFERENCE = 'https://api-inference.huggingface.co/models/';
	private const NEGATIVE = 'painting, sketch,  plastic, (3d), cgi, semi-realistic, cartoon, ugly, duplicate, morbid, mutilated, extra fingers, mutated hands, poorly drawn hands, poorly drawn face, mutation, deformed, blurry, bad proportions, cloned face, disfigured, out of frame, extra limbs, (bad anatomy), gross proportions, malformed limbs, missing arms, missing legs, extra arms, extra legs, fused fingers, too many fingers, long neck, (bad eyes)';

	private $apiKey;      //secure apiKey
	private $endPoint;    //containing our endpoint
	private $models;      //models
	private $sName;       //shortname for model
	public $imgStore;     //path to store image

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

	function __construct(){
	

	//check for module tidy
		if (! extension_loaded('tidy')) {
		  echo "PHP module tidy is needed to run clsStraico. Please install it. Exiting!\n";
		  exit;
		}
		if (! extension_loaded('readline')) {
		  echo "PHP module readline is needed to run clsStraico. Please install it. Exiting!\n";
		  exit;
		}
		if (! extension_loaded('imagick')) {
		  echo "PHP module imagick is needed to run clsStraico. Please install it. Exiting!\n";
		  exit;
		}
		if ( getenv("INFERENCE_READ") ){
			$this->apiKey = getenv('INFERENCE_READ');
		}else{
			echo "Could not find the API key. Exiting!";
			exit(-1);
		}
		$this->models = json_decode(file_get_contents(__DIR__.'/models.json'), true);
		
		 
		$this->endPoint = Huggingface::INFERENCE.$this->models['stablexl'];
		$this->sName = 'stablexl';
		$this->imgStore = '';
		
		echo "Welcome to clsHuggingface v0.1.0 - enjoy!\n\n";

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

	public function userPrompt() {
	
		$input = readline('> ');
	
		// Add  to session history
		readline_add_history($input);
			
		// End cls session on cli		
		if ( $input == "/exit" ){
			exit;

		// Set model	
		}elseif( substr($input,0,9) == "/setmodel"){
			 $this->changeModel(substr($input,10));

		// Save models	
		}elseif( $input == "/savemodels"){
			file_put_contents(__DIR__."models.json",json_encode($this->models));

		// Add model	
		}elseif( substr($input,0,9) == "/addmodel"){
			$granate = explode(" ",substr($input,10));
			if(! array_key_exists($granate[0],$this->models)){
				$smodel = array_search($granate[1], $this->models);
				if(! $smodel){
					$this->models[$granate[0]] = $granate[1];
				}else{
					echo "\nModel already available as $smodel\n";
				}
			}else{
				echo "\nShortname already in use!\n";
			}
		// Remove model	
		}elseif( substr($input,0,9) == "/delmodel"){
			$granate = trim(substr($input,10));
			unset($this->models[$granate]);

		// Save models	
		}elseif( $input == "/getmodels"){
			$this->models = json_decode(file_get_contents(__DIR__.'models.json'), true);

		// list model	
		}elseif( $input == "/listmodels"){

			foreach ($this->models as $key => $value) {
				echo "$key \n";
			}

		// list model	to textfile
		}elseif( $input == "/models2txt"){
			$atext="";
			foreach ($this->models as $key => $value) {
				$atext .= "$key - $value\n";
			}
			$id = $this->imgStore.'/models.txt';
			file_put_contents($id,$atext);
			echo "List saved to $id\n";
     
		//  help	
		}elseif( $input == "/helpme"){
			$this->help();

		//  lastcheck on not existing command	
		}elseif( substr($input,0,1) == "/"){
			echo "\nWARNING COMMAND: $input UNKNOWN\n\n";
			$this->help();


		// Proccess
		}else{
			$answer = $this->apiCompletion($input);
		}
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

	public function apiCompletion($aiMessage){

if(substr($aiMessage,0,1) == '/') return;

echo "\n\nEndpoint short name: ".$this->sName."\n\n";

		$httpMethod = 'POST';


		// Prepare query data
		$data = http_build_query(array('inputs' => $aiMessage,
										'parameters' => array(
											'wait_for_model' => true,
											'negative_prompt' => Huggingface::NEGATIVE,
											'x-use-cache' => 0,
//											'guidance_scale' => 30,
//												'num_inference_steps' => 30,
											'width' => 768,
											'height' => 1024)
										
										
										));

		// Prepare options
		$options = array(
			'http' => array(
			'header' => "Authorization: Bearer ".$this->apiKey."\r\n" .
						"Content-Type: application/jason\r\n",
						"User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.84 Safari/537.36\r\n",
			'method' => $httpMethod,
			'content' => $data
			)
		);


		// Create stream
		$context = stream_context_create($options);


		// Temporarily disable error reporting
		$previous_error_reporting = error_reporting(0);


		// Start pulling session
		$timer = 0;
		$pointer = true;
		
		while ( $pointer = true){
			sleep($timer);
			
			// Communicate
			$result = @file_get_contents($this->endPoint, false, $context);


			// Check if an error occurred
			if ($result === false) {
			
				$error = error_get_last();
//				var_dump($error);
			
				if ($error !== null) {
					if(str_contains($error['message'],'503')){
						$timer += 30;
						echo "Model not loaded yet, trying again in $timer seconds\n";
						$result="";
						continue;
					}elseif(str_contains($error['message'],'500')){
						echo "Server error 500, returning.\n";
						return;
					}elseif((str_contains($error['message'],'429')) || (str_contains($error['message'],'403')) ){
						echo "Too many request. Returning.";
						return;
					}else{
						$timer += 30;
						$granate = explode(":",$error['message']);
						echo $granate[5] ." not acted on.\n\n";
						return;
					}
				} else {
					echo "An unknown error occurred while fetching the webpage. Please try again!\n";
					return;
				}
			}
			break; // we have a picture
		} //end while
		
		// Restore the previous error reporting level
		error_reporting($previous_error_reporting);

		// Decode the image blob
		$image = new Imagick();
		$image->readImageBlob($result);
		$image->setImageFormat('png');
		$id = $this->imgStore.'/'.$this->sName.date('jmdHms').'.png';
		$image->writeImage($id);
		
		echo "\nImage stored as $id\n";

	} 
	
	private function changeModel($input){
		
		foreach ($this->models as $key => $value) {

			if($input == $key ){
				$this->endPoint = Huggingface::INFERENCE.$value;
				$this->sName = $key;
			}
		}

		echo "\nModel is: $this->sName\n";
		
		return;
		
	}
	
	private function help(){
echo'
Commands:

/helpme      
This help

/exit
Leave class

/setmodel  <model>
Set model to shortname of model

/savemodels
Savemodels

/addmodel <short> <model>
Addmodel choose a shortname and add Hugging model
model has format like
stabilityai/stable-diffusion-xl-base-1.0
Just click on the copy icon in Hugging 

/delmodel <short>
Delete model with sortname

/getmodels
load the models from file

/listmodels
Listmodels

/models2txt
Creates a txt list of models, easy if you want to keep notes.

';
	}
}
?>
