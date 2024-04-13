<?php
/*
 * clsHuggingface.php © 2024 by Roelfrenkema is licensed under CC BY-NC-SA 4.0. 
 * To view a copy of this license, 
 * visit http://creativecommons.org/licenses/by-nc-sa/4.0/
 *
 */

class Huggingface {
	
	private const INFERENCE = 'https://api-inference.huggingface.co/models/';

	private $apiKey;      //secure apiKey
	private $endPoint;    //containing our endpoint
	private $models;      //models
	private $sName;       //shortname for model
	public $logAll;      //logging?
	public $imgStore;     //path to store image1
    public $slMax;        //max value for sleep before passing out
	public $slUpdate;     // sleep incremeter
    public $exiv2;        //use exiv2
    public $prompt;       //use for prompt
	public $negative;     //use for negative prompt
    public $exiv2User;     //get your name stamped
    public $exiv2Copy;     //copyright info
    public $negPrompt;     //negative prompt from user
    
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
		
		foreach( $this->models as $tag => $value){
			$this->sName = $tag;
			$this->endPoint = Huggingface::INFERENCE.$value;
			break;
		}
			 
		$this->imgStore = '';
		$this->slUpdate = 30;
		$this->slMax = 300;
		$this->exiv2 = false;
		$this->exiv2User = 'clsHuggingface';
		$this->exiv2Copy = 'CC BY-NC-SA 4.0';
		$this->negPrompt = "distortion";
		$this->logAll = false;

		
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

		// Import models	
		}elseif( substr($input,0,11) == "/txt2models"){
			 $this->models = array();
			 $this->importModels(substr($input,11));

		// Add model	
		}elseif( substr($input,0,9) == "/addmodel"){
			
			$this->addModel(substr($input,10));

		// Remove model	
		}elseif( substr($input,0,9) == "/delmodel"){
			$granate = trim(substr($input,10));
			unset($this->models[$granate]);
			echo "\nModel removed\n";

		// Get models	
		}elseif( substr($input,0,10) == "/getmodels"){
			$this->getModels($input);

		// list model	
		}elseif( $input == "/listmodels"){

			foreach ($this->models as $key => $value) {
				echo "$key - $value\n";
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


		// loop prompt through models	
		}elseif( substr($input,0,5) == "/loop"){
			$this->loopModels(substr($input,6));

		// logon	
		}elseif( $input == "/logon"){
			$this->logAll = true;
			echo "Log enabled.";

		// logon	
		}elseif( $input == "/logoff"){
			$this->logAll = false;
			echo "Log disabled.";
     
		//  help	
		}elseif( $input == "/helpme"){
			$this->help();

		//  lastcheck on not existing command	
		}elseif( substr($input,0,1) == "/"){
			echo "\nWARNING COMMAND: $input UNKNOWN\n\n";
			$this->help();


		// Proccess
		}else{
			$this->prompt = $input;
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
		if($this->logAll) $this->logString("Main: Endpoint short name: ".$this->sName) ;

		$httpMethod = 'POST';


		// Prepare query data
		$data = http_build_query(array('inputs' => $aiMessage,
										'wait_for_model' => true,
											'x-use-cache' => 0,
											'negative_prompt' => $this->negPrompt,
											'x-use-cache' => 0,
//											'guidance_scale' => 30,
//											'num_inference_steps' => 30,
											'width' => 768,
											'height' => 1024
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
			
			if( $timer > $this->slMax ){
				echo "\nModel skipped due to sleeptimeout, sorry.\n";
				if($this->logAll) $this->logString("Main: Model skipped due to sleeptimeout, sorry.\n") ;
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
					if(str_contains($error['message'],'503')){
						$timer += $this->slUpdate;
						echo "Model not loaded yet, trying again in $timer seconds\n";
						if($this->logAll) $this->logString("Main: Model not loaded yet, trying again in $timer seconds\n") ;
						$result="";
						continue;
					}elseif(str_contains($error['message'],'500')){
						echo "Server error 500, returning.\n";
						if($this->logAll) $this->logString("Main: Server error 500, returning.\n") ;
						return 500;
					}elseif((str_contains($error['message'],'429')) || (str_contains($error['message'],'403')) ){
						echo "Too many request. Returning.\n";
						if($this->logAll) $this->logString("Main: Too many request. Returning.\n") ;
						return 429;
					}else{
						$timer += 30;
						$granate = explode(":",$error['message']);
						echo trim($granate[5]) ." not acted on.\n";
						if($this->logAll) $this->logString("Main: ". trim($granate[5]) . " not acted on.\n") ;
						return 254;
					}
				} else {
					echo "An unknown error occurred while fetching the webpage. Please try again!\n";
					if($this->logAll) $this->logString("Main: An unknown error occurred while fetching the webpage. Please try again!\n") ;
					return 255;
				}
			}
			break; // we have a picture
		} //end while
		

		// Write the image.
		
		$this->writeImage($result);
		
		return 0;

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
k
/helpme      
This help

/exit
Leave class

/setmodel  <model>
Set model to shortname of model

/addmodel <short> <model>
Addmodel choose a shortname and add Hugging model
model has format like
stabilityai/stable-diffusion-xl-base-1.0
Just click on the copy icon in Hugging 

/delmodel <short>
Delete model with sortname

/getmodels
load the original models from file

/listmodels
Listmodels

/models2txt
Creates a txt list of models, easy if you want to keep notes.

/txt2models <textfile
replace current models by <textfile> full path name.

';
	}
	
	function addModel($input){

		$granate = explode( " " , $input );

		if(! array_key_exists($granate[0],$this->models)){

			$smodel = array_search($granate[1], $this->models);

			if(! $smodel){

				$this->models[$granate[0]] = $granate[1];

			}else{

				echo "\nModel already available as $smodel\n";
				return;

			}

		}else{

			echo "\nShortname already in use!\n";
			return;

		}

		echo "Model $input added\n";

		return;
	}
	
	function getModels($input){
		            
		if(strlen($input) == 10){
			
			$name = "models"; // default models

		}else{

			$name = substr($input,11);

		}

		$id = __DIR__."/$name.json";
		
		if(! is_file( $id )){

			echo "\nModel $name not found\n";
			
			return;
			
		}

		$this->models = json_decode(file_get_contents($id), true);
		echo "\nModels loaded\n";

	}
	function loopModels($prompt){
		
		
		//store current endPoint.
		$storeEndpoint = $this->endPoint;
		$storeSname = $this->sName;
	
		foreach( $this->models as $key => $model ) {
			$response="";

			//set endpoint
			$this->endPoint = Huggingface::INFERENCE.$model;
			$this->sName = $key;
			
			$response = $this->apiCompletion($prompt);

			if($response == 429){
				echo "\nStopping an hour due to API ratelimiting, sorry.\n";
				if($this->logAll) $this->logString("Loop:Stopping an hour due to API ratelimiting, sorry.\n") ;
				sleep(3600);
			}elseif($response == 500){
				echo "\nModel skipped due to server error, sorry.\n";
				if($this->logAll) $this->logString("Loop: Model skipped due to server error, sorry.\n") ;
			}elseif(! $response == "0"){
				echo "\nModel skipped due to $response, sorry.\n";
				if($this->logAll) $this->logString("Loop: Model skipped due to $response, sorry.\n"); ;
			}elseif( $response == $this->slMax){
				echo "\nModel skipped due to sleeptimeout, sorry.\n";
				if($this->logAll) $this->logString("Loop: Model skipped due to sleeptimeout, sorry.\n");
			}

		}
		
		// restore endPoint
		$this->endPoint = $storeEndpoint;
		$this->sName = $storeSname;
		
	}
	function logString($string){
		
		$log = $this->imgStore.'/log.txt';
		
		file_put_contents( $log , date("Y-m-d H:i:s") . " " . $string , FILE_APPEND );
		
		return;
	}

	function importModels($string){

$home_dir = getenv('HOME');
$gfile = $home_dir."/".trim($string);
	
		if( is_file( $gfile )) {
			$stack = file_get_contents($gfile);
			$lines = explode( "\n" , $stack );
		}else{
			echo "$gfile - NOT FOUND\n";
			return;
		}
		
		foreach( $lines as $line ){
			
			if( ! $line == "" ){
				echo "Trying to add: $line\n";
				$this->addModel($line);
			}
		}
		
		return;
	}
	
	private function setExif($aiMessage,$id){

		
		$myM =  '-M"set Exif.Image.ImageDescription '."\nPrompt: ". $this->prompt ."\n\nNeg: ".$this->negPrompt.'"';
		$myM .= ' -M"set Xmp.plus.ImageSupplierName '.$this->exiv2User.'"';
		$myM .= ' -M"set Xmp.dc.creator '.$this->exiv2User.'"';
		$myM .= ' -M"set Xmp.dc.rights '.$this->exiv2Copy.'"';
		$myM .= ' -M"set Xmp.photoshop.Credit '.$this->exiv2User.'"';
		$myM .= ' -M"set Xmp.xmp.CreatorTool clsHuggingface"';
		$myM .= ' -M"set Exif.Image.Software clsHuggingface"';
		$myM .= ' -M"set Exif.Photo.UserComment '.$this->endPoint.'"';
		
		$test = shell_exec("exiv2 $myM ".$id);
	}
	
	private function writeImage($blob){
		
		$image = new Imagick();
		$image->readImageBlob($blob);
		$image->setImageFormat('png');
		$id = $this->imgStore.'/'.$this->sName.'-'.date('jmdHms').'.png';
		$image->writeImage($id);
		if($this->exiv2) $this->setExif($aiMessage,$id);
		echo "\nImage stored as $id\n";
		if($this->logAll) $this->logString("Main: Image stored as $id\n") ;
		return 0;

	}
	
	
}
?>
