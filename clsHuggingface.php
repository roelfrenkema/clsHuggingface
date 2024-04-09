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
			$this->models[$granate[0]] = $granate[1];

		// Remove model	
		}elseif( substr($input,0,9) == "/delmodel"){
			$granate = trim(substr($input,10));
			unset($this->models[$granate]);

		// Save models	
		}elseif( $input == "/getmodels"){
			$this->models = json_decode(file_get_contents(__DIR__.'models.json'), true);

		//  model	
		}elseif( $input == "/listmodels"){

			foreach ($this->models as $key => $value) {
				echo "$key \n";
			}

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

echo "\n\nEndpoint: ".$this->endPoint."\n\n";

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


		// Communicate
		$result = @file_get_contents($this->endPoint, false, $context);


		// Check if an error occurred
		if ($result === false) {
			$error = error_get_last();
			if ($error !== null) {
				$message = explode(":",$error['message']);
				echo "Error: {$message[3]} \n\nThis can be a temporary API failure, try again later!\n";
				return;
			} else {
				echo "An unknown error occurred while fetching the webpage. Please try again!\n";
				return;
			}
		}
		
		// Restore the previous error reporting level
		error_reporting($previous_error_reporting);
		// Decode the image blob
		//$image = imagecreatefromstring($result);
		
		$image = new Imagick();
		$image->readImageBlob($result);
		$image->setImageFormat('png');
		$image->writeImage($this->imgStore.'/'.$this->sName.date('jmdHms').'.png');
		// Save the image to a file
		//imagepng($image, 'storage/shared/backups/'.$this->sName.date('jmdHms').'.png');
		
		echo "Done\n";

	} 
	
	private function changeModel($input){
		
		foreach ($this->models as $key => $value) {

			if($input == $key ){
				$this->endPoint = Huggingface::INFERENCE.$value;
				$this->sName = $key;
			}
		}

		echo "\nCurrent model is : $this->endPoint \n";
		
		return;
		
	}

}
?>
