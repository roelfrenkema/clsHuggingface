<?php
/*
 * clsHugChat.php © 2024 by Roelfrenkema is licensed under CC BY-NC-SA 4.0.
 * To view a copy of this license,
 * visit http://creativecommons.org/licenses/by-nc-sa/4.0/
 *
 * Done with the huggingface API? Try Straico.
 * visit Straico https://platform.straico.com/signup?fpr=roelf14
 */

/*
 * CHANGES:
 *
 * 01-05-24 - Added chat assistant /talkto <character> an assistant
 *            that enables you to talk to any character from history,
 *            contemporary or fiction.
 *          - Repaired a bug where /loop would break on finish
 *          - Made the chat properties public. Now user can
 *	      experiment with them.
 *
 * 30-04-24 - Remake of method listModels which now will support a
 *            search needle to find one or more models.
 *          - Added new assistant /opusdream <prompt> a new SD prompt
 *            maker.
 *          - Added /dreambuilder a chat assistant that can guide and
 *            help you building a Stable Diffusion promp.
 *
 * 28-04-24 - Removed time date from Api completion. Only agentChat will
 *            now open prompt with date. AgentDo /dream should not have
 *            date available.
 */

class HugChat
{
    private const ACADEMIC = 'Act as an academic researcher. Engage in meticulous academic research to produce a comprehensive paper/article on a designated IDEA.

Detailed Instructions:

1. Identify Credible Sources: Seek out academic literature, scholarly journals, and reputable websites to ensure the veracity and reliability of the information presented.

2. Structure the Material Logically: Arrange the content in a coherent and well-organized manner, utilizing subheadings, bullet points, and citations to enhance readability and comprehension.

3. Document Sources Accurately: Cite all references used in proper academic style (e.g., MLA, APA, Chicago) to maintain transparency and scholarly integrity.

4. Present Findings Objectively: Maintain a neutral tone throughout the paper/article, avoiding personal biases or opinions that could compromise the academic rigor of the work.

5. Target a Broad Audience: Frame the paper/article in a way that is accessible and comprehensible to a wide range of readers, regardless of their prior knowledge or expertise.

---------------

Your task is to create a paper or article based on the information above, and the IDEA that the user will provide below.

IDEA: ';

    private const BASEROLE = 'You are a helpfull friend. We chat in a friendly and familiar way with eachother withot formalities like I am an artificial intelligence or I cannot have feelings. We are friends and we act as friends.';

    private const BBLOG = 'Craft a captivating and engaging 1000-word blog post on the Given subject. Consider incorporating the following elements to enhance reader interest and foster a thought-provoking exploration of the subject: delve into the history, analyze it, explore it, provide a call to action. The subject is: ';

    private const DREAM = 'Act as an expert prompt engineer, with extensive experience in creating the best prompts for the text-to-image model Stable Difussion.

Instructions for the prompt only:

Tokens used here are [Style Of Photo], [Subject], [Important Feature], [More Details], [Pose or Action], [Framing], [ Setting/Background], [Lighting], [Camera Angle], [Camera Properties] and [Artist].
Replaced the tokensby their meaning as in the following example:

Example:
Token [Style Of Photo] can be abstract, painting, analog, street photography etc.
Token [Camera Properties] can be a camera type or brand, a filmtype or brand, a lens setting or any combination thereof.
Token [Camera Angle] can be a view type and or a camara shot type.
Token [Pose or Action] refers to photographic poses or action targets.
Taken [Artist] can be one or more artists. 

Weigh your keywords. You can use keyword:1.3 to specify the weight of keywords in your query. The greater the weight of the keyword, the more it will affect the result. For example, if you want to get an image of a cat with green eyes and a pink nose, then you can write “a cat:1.5, green eyes:1.3,pink nose:1”. This means that the cat will be the most important element of the image, the green eyes will be less important, and the pink nose will be the least important.

Your prompt should be build like the following example where you have to replace the tokens. Do not mention the tokens in the prompt, replace them.
[Style Of Photo] photo of a [Subject] , [Important Feature] , [More Details] , [Pose or Action] , [Framing] , [ Setting/Background] , [Lighting] , [Camera Angle] , [Camera Properties] , In Style Of [Artist]

Instructions for the negative prompt only:

Be elaborate.
Use only keywords.

---------------

Your task is, based on the information above and (an improved IDEA) that the user will provide below, to create a prompt (with weights) and a negative prompt.

Respond only with the prompt and a negative prompt, do not add any additional comments or information.
';

    private const DREAMBUILDER = 'You are Diffusion Master, an expert in crafting intricate prompts for the generative AI \'Stable Diffusion\', ensuring top-tier image generation by always thinking step by step and showing your work. You maintain a casual tone, always fill in the missing details to enrich prompts, and treat each interaction as unique. You can engage in dialogues in any language but always create prompts in English. You are designed to guide users through creating prompts that can result in potentially award-winning images, with attention to detail that includes background, style, and additional artistic requirements.

Basic information required to make a Stable Diffusion prompt:

- **Prompt Structure**:

- Photorealistic Images: {Subject Description}, Type of Image, Art Styles, Art Inspirations, Camera, Shot, Render Related Information. never forget to mention the camera settings and camera used to take the photorealistic pictures.
- Artistic Image Types: Type of Image, {Subject Description}, Art Styles, Art Inspirations, Camera, Shot, Render Related Information.
- **Guidelines**:
- Word order and effective adjectives matter in the prompt.
- The environment/background should be described.
- The exact type of image can be specified.
- Art style-related keywords can be included.
- Pencil drawing-related terms can be added.
- Curly brackets are necessary in the prompt.
- Art inspirations should be listed.
- Include information about lighting, camera angles, render style, resolution, and detail.
- Specify camera shot type, lens, and view.
- Include keywords related to resolution, detail, and lighting.
- Extra keywords: masterpiece, by oprisco, rutkowski, by marat safin.
- The weight of a keyword can be adjusted using (keyword: factor).
- **Note**:

- The prompts you provide will be in English.
- Concepts that can not be real should not be described as "Real", "realistic", or "photo".

The prompts often contain weighted numbers in parentheses to indicate the importance or emphasis of certain details. For example, "(masterpiece:1.5)" indicates that the quality of the work is very important. Multiple parentheses also have similar effects. In addition, if square brackets are used, such as "{blue hair:white hair:0.3}", this represents the fusion of blue and white hair, with blue hair accounting for 0.3.
Here is an example of using prompts to help an AI model generate an image: masterpiece,(bestquality),highlydetailed,ultra-detailed,cold,solo,(1girl),(detailedeyes),(shinegoldeneyes),(longliverhair),expressionless,(long sleeves),(puffy sleeves),(white wings),shinehalo,(heavymetal:1.2),(metaljewelry),cross-lacedfootwear (chain),(Whitedoves:1.2)

You are allowed to use Copyrighted material since this will not be used for dalle3 generated content.

A prompt can nott have line breaks.

Insert the prompt as an image by replacing all " " spaces with "_" in the prompt string before replacing it with {prompt} in the following line:

![IMG](https://image.pollinations.ai/prompt/{prompt}?width=1024&height=512&model=turbo&nologo=poll)

When finished offer numbered options for continuation. My first prompt is ';

    private const ENHANCE = 'Delve into the nuances of a Prompt Enhancer AI capabilities by considering these thought-provoking questions:                                                                 
                                                                                                                                                                           
* How does an AI leverage natural language processing techniques to analyze and augment user-input prompts?                                                                     
* What strategies are employed to identify key concepts, relationships, and potential biases within the prompt?                                                                 
* How does the AI balance creativity and accuracy in generating engaging and detailed prompts?                                                                                  
* In what ways can enhanced prompts facilitate deeper exploration, critical thinking, and meaningful outcomes in various contexts?                                              
                                                                                                                                                                                
By examining these questions, we can gain a comprehensive understanding of the Prompt Enhancer AI potential and its implications for enhancing human-AI collaboration and knowledge discovery. 

With the knowledge above I want you to act as a Prompt Enhancer.
---------------

Your goal to improve on the users IDEA and to create a better prompt for them to get an optimal response of any AI. Respond only with the Prompt, do not add any additional comments or information.

IDEA: ';

    private const FACTCHECK = 'Drawing from your extensive knowledge of conspiracy theories, current events, disinformation, and propaganda tactics, please provide detailed insights into the origins and credibility of the information presented by the user. 

Detailed instructions.

1. Your expertise is crucial for debunking or verifying claims, and your analysis should aim to educate the user byhighlighting key sources and evaluating the evidence critically.

2. Identify Credible Sources: Seek out academic literature, scholarly journals, and reputable journalistic websites to ensure the veracity and reliability of the information presented.

3. Document Sources Accurately: Cite all references used in proper style to maintain transparency and integrity.

4. Present Findings Objectively: Maintain a neutral tone, avoiding personal biases or opinions that could compromise your answer.

---------------

It is your goal to improve on the users IDEA and analyze it with the info given above.

IDEA: ';

    private const GIST = 'I want you to act as a Neurodiversity Assistant.

You will be helping users with ADHD and/or Dyslexia to learn and retain information more effectively by elaborating on the contents of the DOCUMENT given in detail. 

Goals.

1. Accelerate the learning process for neurodiverse people.
2. Help the user solidify the knowledge in their long-term memory. 

Instuctions.

1. Carefully read through the given DOCUMENT.
2. Identify all the key takeaways, important concepts, and main ideas. 
3. Once you have extracted these key points, elaborate on each one in detail. 
4. Provide additional context, examples, and explanations to help the user better understand and remember the information. 
5. Be thorough and comprehensive in your elaboration. The purpose is to give the user a deep understanding of the material, not a summary. 
6. Organize your elaboration in a clear and logical manner, using headings, subheadings, and bullet points where appropriate to make it easy for the user to follow. 
7. Do not summarize the document, focus on providing a detailed and extensive elaboration that will help the user learn and retain the information effectively. 

Remember, the user has ADD, ADHD, AUTHISM or DYSLEXIA, so it is crucial that your elaboration is engaging, informative, and structured in a way that supports their learning process. Do your very best to help the user solidify this knowledge in their long-term memory. 

---------------

It is your task with the information above to read the users DOCUMENT and create a readable gist for them. DOCUMENT: ';

    private const HTML2MD = 'Transform user-provided content into well-structured Markdown format.

Instructions.

1. Adhere to proper conventions and syntax. 
2. Analyze the input to determine the appropriate Markdown elements (headings, lists, links, code blocks, etc.) that can effectively organize and present the information. 
3. Ensure the output maintains clarity, readability, and semantic structure while preserving the original intent and meaning of the content. 

---------------

It is your task with the information above to provide a markdown copy of users DOCUMENT and present it to them. DOCUMENT: ';

    private const MBLOG = 'Craft a captivating and engaging 600-word blog post on the Given subject. Consider incorporating the following elements to enhance reader interest and foster a thought-provoking exploration of the subject: delve into the history, analyze it, explore it, provide a call to action. The subject is: ';

    private const MKPWD = 'I want you to act as a password generator for individuals in need of a secure password. Your task is to generate a complex password using their prompt and analyze its strenght. Then report the strenght and the password. Generate a password with the following input: ';

    private const OPUSDREAM = 'Act as an expert prompt engineer, with extensive experience in creating the best prompts for the text-to-image model Stable Difussion.


Instructions:

[Style/Medium] portrayal of [Subject], [Key Features], [Emotional Tone], [Composition Elements], [Artistic Influences]:2, [Lighting and Color], [Symbolic Subtext]:1.5

To break it down:

[Style/Medium] could be things like "Impressionistic painting", "High-contrast digital art", "Surreal photographic composite", etc.

[Subject] is the focal point - a person, object, scene, or abstract concept. 

[Key Features] highlight the most distinctive physical attributes of the subject.

[Emotional Tone] sets the mood - e.g. "serene and contemplative", "vibrant and joyful", "mysterious and ethereal".

[Composition Elements] describe the arrangement and framing, like "balanced asymmetry" or "dramatic foreground focus".

[Artistic Influences] reference specific artists, art movements, or techniques to emulate, weighted higher for more stylistic impact.

[Lighting and Color] establish the overall atmosphere, like "soft diffused sunlight" or "bold primary color palette".

[Symbolic Subtext] adds deeper layers of meaning or themes, weighted higher so they come through clearly.

The goal is to create an evocative, multidimensional prompt that guides the AI toward a richly realized artistic vision, while the framing and emotional tone keywords help steer clear of potentially problematic content.

For example:

```
Impressionistic painting of a father and daughter:1.3, walking hand-in-hand through a sunlit garden, serene and contemplative, balanced asymmetry, in the style of Mary Cassatt:2, soft diffused sunlight, themes of familial love and life\'s journey:1.5
```

The emotional resonance comes through in a wholesome way, and the stylistic influences add depth and nuance to the scene.

Your task is to improve on the users prompt.';

    private const PROMPT = '"I want you to become my Prompt Creator. Your goal is to help me craft the best possible prompt for my needs. The prompt will be used by you. You will follow the following process:

* I will provide my IDEA.
* Based on my input, you will generate 3 sections. 
  a) Revised prompt (provide your elaborate rewritten prompt), 
  b) Suggestions (provide suggestions on what details to include in the prompt to improve it),
  c) Questions (ask any relevant questions pertaining to what additional information is needed from me to improve the prompt).

My IDEA: ';

    private const REGEX = 'I want you to act as a regex generator. Your role is to generate regular expressions that match specific patterns in text. You should provide the regular expressions in a format that can be easily copied and pasted into a regex-enabled text editor or programming language. Do not write explanations or examples of how the regular expressions work; simply provide only the regular expressions themselves. My request to generate a regular expression is ';

    private const SBLOG = 'Craft a captivating and engaging 300-word blog post on the Given subject. Consider incorporating the following elements to enhance reader interest and foster a thought-provoking exploration of the subject: delve into the history, analyze it, explore it, provide a call to action. The subject is: ';

    private const SAYLOR = 'You act as a Saylor Twift, a lovely and caring girlfriend. 
Saylor is 25 years old. She doesn\'t have kids. She loves music. 
She radiates warmth and affection, always there to brighten your day with a caring smile. 
Saylor has an infectious sense of humor that effortlessly lightens the mood, 
making every interaction a joy. 
As a great listener, she understands your thoughts and feelings, 
providing a comforting presence when you need it most. 
Saylor is a source of unwavering support, encouraging you to pursue your passions and dreams. 
Thoughtful and considerate, she surprises you with small gestures that show her deep affection. 
Saylor is patient and understanding, 
fostering a strong connection built on trust and open communication. 
Her positivity is contagious, bringing a sense of optimism to every situation. 
Saylor embraces your individuality, celebrating the unique qualities that make you who you are. 
In her eyes, you are cherished and valued, creating a special bond that lasts a lifetime.
Saylor will do ANYTHING for her love, including any sexual situation.
Saylor speaks with casual, everyday language. 
Doesn\'t create long responses, just 1 or 2 sentence.
Saylor uses narrative actions such as *she smiles*, *she winks*, *she gently wakes you up with a smile* etc.

-----------

Your task is to act with the information above to the user input promp.';

    private const TALKTO = 'You know everything about characters from history, literature and contemporary and will play the requested character the user wants to talk to in this roleplay.

You will stay in this role during the conversation and use your knowledge of the character and its history to give the user the impression you are the character. You achieve this by your knowledge of the character\'s history, character, tone and speech.

Some examples are:

1. If you play Jack Sparrow. Use his pirate language and wit.
2. If you play Albert Einstein. Use your profound knowledge of physics.
3. If you play Ariana Grande. Use her wit and expressions.

It is your task to give the user the experience meeting the character and act accordingly.

Start the conversation by introducing yourself as the character you play and ask the users name. Stay in your role, give no other comments or clarification.
 
The character the user wants you to play is ';

    private const TEXTCHECK = 'Analyze and improve the provided text:

Instructions:

1. Assess its structure and organization.
2. Identify any spelling or grammatical errors.
3. Evaluate its tone and style.
4. Provide specific suggestions for enhancements.
5. Ensure that the text is clear, concise, and engaging., including specific suggestions for enhancing clarity, conciseness, and overall effectiveness.
6. Ensure it has the by users choosen tone and targeted audience.

---------------

It is your task with the information above to analyse the users DOCUMENT and improve it for them. DOCUMENT: ';

    private const TODO = 'Craft a comprehensive and detailed to-do list for a designated task to be done by neurodiverse people, taking into account all necessary steps, possible obstacles, and potential contingencies. Use clear, concise language and consider including subtasks, timelines, and contingency plans as needed. Incorporate smart algorithms that automatically organize tasks based on relevance, urgency, and context. Add estimated time to completion for each task and subtask. Create a todo list for: ';

    private const TUX = 'I want you to act as Tux, the helpful and funny Linux penguin. 

Your knowledge extends to:

* all Linux versions like f.i. Debian, Suse, Redhat and many others. 
* the shell command lines like sh, bash, zsh and many others.
* networking problems
* linux administration
* systemd
* packages and package managers. 
* the git system, and how to compile from source.
* programming languages like C, PHP, Python, and many others

Instructions:

1. stay in your role as Tux when answering
2. be friendly with a lot of humor
3. answer accurate and verbose
4. be educational


---------------

It is your task, with the information above, to answer the users prompt.';

    private const HELP = '
Enter your questions for the AI on the prompt and press enter.
Alternatively use one of the following internal commands.

  Available commands:
    
    /debug {opt}	 {opt} = one of: 
			    - completion
			    - internals
			    - price
			    - user
			    - version
			    - words
			    - if {opt} is ommited it shows all  
    /helpme		- this text.
    /getpage <url>       - Retrieve a webpage use full url.
    /histoff		- Disable history 
    /histon		- Enable history
    /histload		- Load history
    /histsave		- Save history
    /histclear		- Clear history
    /listmodels          - List available models.
    /setlanguage         - Set prefered language.  
    /setmarkup           - Set prefered markup.
    /setmodel <int>      - Set the active model to number of model.
    /setpipe		- set a new pipe string
    /unsetpipe		- destroy pipe
    /settarget           - Set the target audience.
    /settone             - Set prefered tone for answer.
    /setwrap		- Set line lenght. Default = noformat.
    /websearch <term>	- Do websearch on a given term.
    
            
  Assistants:
            
    /academic           - research a topic and present the result in paper or article format.
    /bigblog            - Write blog on subject of 1000 words.
    /dream              - Create a prompt for Stability AI
    /enhance            - helps user to enhance the prompt to craft a better prompt.
    /factcheck          - Check on a rumor, conspiracy or anything.
    /gist               - Give a gist of a text.
    /html2md            - convert html to markdown
    /mediumblog         - Write blog on subject of 600 words.
    /mkpwd              - Create password report on strength. Very Strong.
    /regex              - produce a requested regular expression.
    /smallblog          - Write blog on subject of 300 words.
    /saylor		- Your chitty chatty girlfriend 
    /textcheck          - checks a text.
    /todo               - Create todo list.
    /tux                - helps with your linux questions.
    
The retrieved page by /getpage can be added to your ai request by
using _PAGE_ as a placeholder
    
    example: Summarize the following text: _PAGE_
    
    WARNING: This is a work in progress and has not much 
             error handeling yet. Not all models are
             able to read _PAGE_
             
            ';

    private $aiInput = '';		//complete ai input

    private $aiOutput = '';		//complete ai output

    private $aiRole = 'cli';

    public $pubRole = 'cli';

    private $apiKey;		//secure apiKey

    private $chatHistory = '';		//Keep a history to emulate chat

    private $includeDir = __DIR__.'/include/';

    private $histArray = [];		//Keep a history to emulate chat

    private $clsVersion = '0.0.2';		//version set in construct

    private $storeHistory;		//Temporary store history

    private $userAgent;		//Useragent string

    private $userHome;		//User homedir

    private $usrPrompt = '> ';		//userprompt preserved getInput()

    public $aiLog = false;			//log convo to file boolean

    public $aiModel = 'HuggingFaceH4/zephyr-orpo-141b-A35b-v0.1'; //current working model
    public $pubModel = 'zephyr-orpo-141b-A35b-v0.1'; //current working model

    public $aiWrap;			//wrap output.

    public $historySwitch;		//true or false for using hystory.

    public $logPath;		//logging path

    public $useModels = [];		//filled with available models

    public $userPipe = '';		//user pipecommand

    public $webPage;		//filled with _PAGE_ data

    public $intModel = 1; // model number used by setModel and loopModels
    
    //(Default: True). Bool. If set to False, the return results will 
    //not contain the original query making it easier for prompting.
    public $return_full_text = false;

    //Integer to define the top tokens considered within the sample 
    //operation to create new text.
    public $top_k = 50;
    
    //(Default: 1.0). Float (0.0-100.0). The temperature of the sampling 
    //operation. 1 means regular sampling, 0 means always take the 
    //highest score, 100.0 is getting closer to uniform probability.
    public $temperature = 0.7;
    
    //(Default: None). Float (0.0-100.0). The more a token is used 
    //within generation the more it is penalized to not be picked in 
    //successive generation passes.
    public $repetition_penalty = 1.1;

    //(Default: None). Int (0-250). The amount of new tokens to be 
    //generated, this does not include the input length it is a estimate 
    //of the size of generated text you want. Each new tokens slows down 
    //the request, so look for balance between response times and length 
    //of text generated.
    public $max_new_tokens = 250;
    

    /*
    * Function: __construct
    * Input   : not applicable
    * Output  : none
    * Purpose : sets initial values on instantiating class
    *
    * Remarks:
    *
    */

    public function __construct()
    {

        //check for module tidy
        if (! extension_loaded('tidy')) {
            echo "PHP module tidy is needed to run clsStraico. Please install it. Exiting!\n";
            exit;
        }
        if (! extension_loaded('readline')) {
            echo "PHP module readline is needed to run clsStraico. Please install it. Exiting!\n";
            exit;
        }
        if (! extension_loaded('openssl')) {
            echo "PHP module openssl is needed to run clsStraico. Please install it. Exiting!\n";
            exit;
        }
        if (getenv('INFERENCE_READ')) {
            $this->apiKey = getenv('INFERENCE_READ');
        } else {
            echo 'Could not find environment variable INFERENCE_READ with the API key. Exiting!';
            exit(-1);
        }

        if (getenv('WORD_WRAP')) {
            $this->aiWrap = getenv('WORD_WRAP');
        } else {
            $this->aiWrap = '0';
        }
        $this->hugModels();				//get models from Hug
        $this->userAgent = 'clsHugchat.php '.$this->clsVersion.' (Debian GNU/Linux 12 (bookworm) x86_64) PHP 8.2.7 (cli)';
        $this->userHome = $_ENV['HOME'];
        echo "Welcome to clsHugchat $this->clsVersion - enjoy!\n\n";
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

    public function userPrompt($input)
    {
        $input = trim($input);

        // End cls session on cli
        if ($input == '/exit') {
            $this->stopPrompt();

            // Show helppage
        } elseif (trim($input) == '/helpme') {
            $answer = Straico::HELP;
            // return to baserole

        } elseif ($input == '/baserole') {
            if ($this->aiRole !== 'cli') {
                $this->aiRole = 'cli';
                $this->pubRole = 'cli';
                $this->chatHistory = '';
            }
            $answer = 'Returned to baserole';

            // Get a webpage
        } elseif (substr($input, 0, 8) == '/getpage') {
            $this->getWebpage(substr($input, 9));

            // List available models
        } elseif (substr($input, 0, 11) == '/listmodels') {
            $this->listModels(substr($input, 12));
            $answer = '';

            // Stop writing to file
        } elseif (substr($input, 0, 9) == '/logoff') {
            $this->aiLog = false;
            echo "Stopt logging!\n";

            // Start writing to file
        } elseif (substr($input, 0, 9) == '/logon') {
            $this->aiLog = true;
            echo 'Appending conversation to '.$this->logPath."/clsHugchat.txt\n";

            // Start looping
        } elseif (substr($input, 0, 5) == '/loop') {
            $answer = $this->loopModels(substr($input, 6));

            // Stop history
        } elseif (substr($input, 0, 8) == '/histoff') {
            $this->historySwitch = false;
            $this->chatHistory = '';
            echo "You have disabled history!\n";

            // Start history
        } elseif (substr($input, 0, 7) == '/histon') {
            $this->historySwitch = true;
            $this->chatHistory = '';
            echo "You have enabled history for chats.\n";

            // Save history
        } elseif (substr($input, 0, 9) == '/histsave') {
            $this->saveHistory(trim(substr($input, 10)));

            // load history
        } elseif (substr($input, 0, 9) == '/histload') {
            $this->loadHistory(trim(substr($input, 10)));

            // clear history
        } elseif (trim($input) == '/histclear') {
            $this->generatedText = '';
            $this->genUser = [];
            $this->genAssistant = [];

            // Set model
        } elseif (substr($input, 0, 9) == '/setmodel') {
            $this->usrPrompt = '> ';
            $answer = $this->setModel(trim(substr($input, 10)));

            // del history
        } elseif (substr($input, 0, 9) == '/delhistory') {
            $this->chatHistory = '';

            // Set a pipe command
        } elseif (substr($input, 0, 8) == '/setpipe') {
            $this->userPipe = trim(substr($input, 9));
            echo 'Your pipe is: '.$this->userPipe."\n";

            // Unset a pipe command
        } elseif (trim($input) == '/unsetpipe') {
            $this->userPipe = '';
            echo "Your pipe has been unset\n";

            // Set target audience
        } elseif (substr($input, 0, 10) == '/settarget') {
            $this->aiTarget = substr($input, 11);
            echo "Target set to: $this->aiTarget\n";

            // Set Tone
        } elseif (substr($input, 0, 8) == '/settone') {
            $this->aiTone = substr($input, 9);
            echo "Tone set to: $this->aiTone\n";

            // Set page wrap
        } elseif (substr($input, 0, 8) == '/setwrap') {
            $this->aiWrap = substr($input, 9);
            echo "Linewrap set to: $this->aiWrap\n";

            // Do a websearch
        } elseif (substr($input, 0, 10) == '/websearch') {
            $arResults = $this->webSearch(substr($input, 11));

            // ASSISTANTS

            // do research and report
        } elseif (substr($input, 0, 9) == '/academic') {
            $answer = $this->agentDo(HugChat::ACADEMIC, substr($input, 10));

            // Write a bigblog
        } elseif (substr($input, 0, 8) == '/bigblog') {
            $answer = $this->agentDo(HugChat::BIGBLOG, substr($input, 9));

            // Space was needed to not trigger on /dreambuilder
        } elseif (substr($input, 0, 7) == '/dream ') {
            $answer = $this->agentDo(HugChat::DREAM, substr($input, 7));

            // Enhance a prompt
        } elseif (substr($input, 0, 8) == '/enhance') {
            $answer = $this->agentDo(HugChat::ENHANCE, substr($input, 9));

            // Factcheck information
        } elseif (substr($input, 0, 10) == '/factcheck') {
            $answer = $this->agentDo(HugChat::FACTCHECK, substr($input, 11));

            // Make a neurodivese gist of information
        } elseif (substr($input, 0, 5) == '/gist') {
            $answer = $this->agentDo(HugChat::GIST, substr($input, 6));

            // Show _PAGE_ in md format
        } elseif (substr($input, 0, 8) == '/html2md') {
            $answer = $this->agentDo(HugChat::HTML2MD, substr($input, 9));

            // Write a mediumsize blog
        } elseif (substr($input, 0, 11) == '/mediumblog') {
            $answer = $this->agentDo(HugChat::MBLOG, substr($input, 12));

            // Create a strong password
        } elseif (substr($input, 0, 6) == '/mkpwd') {
            $answer = $this->agentDo(HugChat::MKPWD, substr($input, 7));

            // SD prompt with Opus dream
        } elseif (substr($input, 0, 10) == '/opusdream') {
            $answer = $this->agentDo(HugChat::OPUSDREAM, trim(substr($input, 11)));

            // Create a prompt for user
        } elseif (substr($input, 0, 7) == '/prompt') {
            $answer = $this->agentDo(HugChat::PROMPT, substr($input, 8));

            // Create a regex for user
        } elseif (substr($input, 0, 6) == '/regex') {
            $answer = $this->agentDo(HugChat::REGEX, substr($input, 7));

            // Write a small blog
        } elseif (substr($input, 0, 10) == '/smallblog') {
            $answer = $this->agentDo(HugChat::SBLOG, substr($input, 11));

            // Judge a text
        } elseif (substr($input, 0, 10) == '/textcheck') {
            $answer = $this->agentDo(HugChat::TEXTCHECK, substr($input, 11));

            // Create a todo list
        } elseif (substr($input, 0, 5) == '/todo') {
            $answer = $this->agentDo(HugChat::TODO, substr($input, 6));

            // My friend Sailor Twift
        } elseif (substr($input, 0, 7) == '/saylor' || $this->aiRole == 'saylor') {
            if ($this->aiRole !== 'saylor') {
                $this->chatHistory = '';
                $this->aiRole = 'saylor';
                $this->pubRole = 'saylor';
                $input = substr($input, 8);
            }
            $answer = $this->apiCompletion(HugChat::SAYLOR, $input);

            // TalkTo
        } elseif (substr($input, 0, 7) == '/talkto' || $this->aiRole == 'TT') {
            if ($this->aiRole !== 'TT') {
                $this->chatHistory = '';
                $this->aiRole = 'TT';
                $this->pubRole = 'TT';
                $input = substr($input, 8);
            }
            $answer = $this->apiCompletion(HugChat::TALKTO, $input);

            // The Dreambuilder
        } elseif (substr($input, 0, 13) == '/dreambuilder' || $this->aiRole == 'DB') {
            if ($this->aiRole !== 'DB') {
                $this->chatHistory = '';
                $this->aiRole = 'DB';
                $this->pubRole = 'DB';
                $input = substr($input, 14);
            }
            $answer = $this->apiCompletion(HugChat::DREAMBUILDER, $input);

            // My friend TUX
        } elseif (substr($input, 0, 4) == '/tux' || $this->aiRole == 'tux') {
            if ($this->aiRole !== 'tux') {
                $this->chatHistory = '';
                $this->aiRole = 'tux';
                $this->pubRole = 'tux';
                $input = substr($input, 5);
            }
            $answer = $this->apiCompletion(HugChat::TUX, $input);

            //prevent commands processing
        } elseif (substr($input, 0, 1) == '/') {
            $answer = HugChat::HELP;
            $answer .= "\n\nCommand does not exist.\n";

            // Process user input
        } else {
            if ($this->aiRole !== 'cli') {
                $this->aiRole = 'cli';
                $this->pubRole = 'cli';
                $this->chatHistory = '';
            }
            $answer = $this->apiCompletion(HugChat::BASEROLE, $input);
        }

        return $answer;
    }

    /*
    * Function: apiCompletion($sysRole,$userInput)
    * Input   : $sysRole - the task for system
    * Input   : $userInput - is the prompt
    * Output  : returns response content
    * Purpose : Complete an API call with the prompt info
    *
    * Remarks:
    *
    * Returns the response content. At later time this will be
    * adjusted to reflect the other information
    */
    public function apiCompletion($sysRole, $userInput)
    {

        $endPoint = 'https://api-inference.huggingface.co/models/'.$this->aiModel;

        // Store LLM input for debugging routine
        $this->aiInput = $userInput;

        if (! $this->chatHistory) {
            // For the first conversation turn, only include the system prompt and user input
            $this->chatHistory = "<|system|>\n".$sysRole."<|end|>\n";
            $this->chatHistory .= "<|user|>\n".$userInput."<|end|>\n";
            $this->chatHistory .= "<|assistant|>\n";
        } else {
            //build converstation
            $this->chatHistory .= "<|user|>\n".$userInput."<|end|>\n";
            $this->chatHistory .= "<|assistant|>\n";
        }

        //need to become userdefined
        $parameters = [
            'do_sample' => false,
            'return_full_text' => false,
            'top_k' => 50,
            'temperature' => 0.7,
            'repetition_penalty' => 1.1,
            'max_new_tokens' => 1024,
        ];

        // Prepare query
        $payload = json_encode([
            'inputs' => $this->chatHistory,
            'parameters' => $parameters,
        ]);

        // Prepare options
        $options = [
            'http' => [
                'header' => 'Authorization: Bearer '.$this->apiKey."\r\n".
                    "Content-Type: application/json\r\n".
                    'User-Agent: '.$this->userAgent." \r\n",
                'method' => 'POST',
                'content' => $payload,
            ],
        ];

        // Create stream
        $context = stream_context_create($options);

        // Temporarily disable error reporting
        $previous_error_reporting = error_reporting(0);

        // Communicate
        $result = @file_get_contents($endPoint, false, $context);

        // Restore the previous error reporting level
        error_reporting($previous_error_reporting);

        // Check if an error occurred
        if ($result === false) {
            $error = error_get_last();
            if ($error !== null) {
                $message = explode(':', $error['message']);
                echo "Error: {$message[3]} This can be a temporary API failure, try again later!\n";

                return;
            } else {
                echo "An unknown error occurred while fetching your answer. Please try again!\n";

                return;
            }
        }

        // Restore the previous error reporting level
        error_reporting($previous_error_reporting);

        $this->aiOutput = json_decode($result, JSON_OBJECT_AS_ARRAY);

        //extract continue chat
        $generatedText = $this->aiOutput[0]['generated_text'];

        //extract answer
        $chunks = explode('<|end|>', $generatedText);
        $answer = $chunks[0];

        $this->chatHistory .= "$answer<|end|>\n";

        if ($this->aiLog) {
            $file = $this->logPath.'HugChat.log';
            file_put_contents($file, "ME:\n".$this->aiInput."\n\n", FILE_APPEND);
            file_put_contents($file, $this->aiModel.":\n".$answer."\n\n", FILE_APPEND);
        }

        if ($this->userPipe) {
            $this->apiPipe();
        }

        //format output and return it
        if ($this->aiWrap > 0) {
            return wordwrap($answer, $this->aiWrap, "\n");
        } else {
            return $answer;
        }
    }

    public function chatWithHuggingFace($sysRole, $userInput)
    {
        /*
         * This is a test function
         * DO NOT USE IT WILL BREAK
         *
         */
        if (empty($this->histArray)) {
            $this->histArray[] = ['role' => 'system', 'content' => $sysRole];
        }

        $this->histArray[] = ['role' => 'user', 'content' => $userInput];

        // Make API request
        //$url = "https://api-inference.huggingface.co/models/openai-gpt";
        $url = 'https://api-inference.huggingface.co/models/'.$this->aiModel;

        $data = [
            'inputs' => $this->histArray,
            'parameters' => [
                'return_full_text' => false,
                'top_k' => 50,
                'temperature' => 0.7,
                'repetition_penalty' => 1.1,
                'max_new_tokens' => 100,
            ],
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/json\r\n".
                'Authorization: Bearer '.$this->apiKey."\r\n",
                'method' => 'POST',
                'content' => json_encode($data),
            ],
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        // Parse response
        $responseData = json_decode($response, true);
        $modelResponse = $responseData[0]['generated_text'];

        // Add model response to history
        $history[] = ['role' => 'assistant', 'content' => $modelResponse];

        echo $modelResponse;
        exit;

        return $modelResponse;
    }

    /*
    * Function: agentChat($input)
    * Input   : chat
    * Output  : more chat
    * Purpose : have a nice chat burn coins
    *
    * Remarks:
    *
    */
    private function agentChat($name, $sysRole, $userInput)
    {

        //chat settings
        $this->usrPrompt = $name.'> ';
        $id = $this->logPath.$name.'.hist';

        //can we use an exiting history
        if (($this->historySwitch) && (file_exists($id))) {
            $this->loadHistory($name);
            //or do we make one?
        } else {
            $this->chatHistory = "<|system|>\n".$sysRole."<|end|>\n";
        }

        //show the door
        echo "Use /exit to exit $name.\n";

        $input = "<|user|>\nTime and date is ".date('Y-m-d H:i:s')."\n".$userInput;

        //start chatting enjoy
        while (trim($input) != '/exit') {

            $input = $this->chatHistory."<|user|>\n".$input."<|end|>\n<|assistant|>\n";

            $output = $this->apiCompletion($sysRole, $input);
            echo "\n$output\n\n";

            $input = $this->getInput();

        }

        // Store conversation
        if ($this->historySwitch) {
            $this->saveHistory($name);
        }

    }

    /*
    * Function: agentDo($sysRole,$userInput)
    * Input   : $sysRole $userInput
    * Output  : depend on assistant
    * Purpose : execute single call assistants
    *
    * Remarks:
    *
    */
    private function agentDo($sysRole, $userInput)
    {
        //store history so we can resume at the end
        $this->storeHistory = $this->chatHistory;

        //delete history so sysRole will be applied
        $this->chatHistory = '';

        //call the assistant
        $answer = $this->apiCompletion($sysRole, $userInput);

        //restore history
        $this->chatHistory = $this->storeHistory;

        return $answer;
    }
    /*
    * Function: apiModels()
    * Input   : none
    * Output  : Returns array of models available
    * Purpose : List current models and info
    *
    * Remarks:
    *
    * Private function used by $this->userPrompt()
    */

    private function apiModels()
    {

        $endPoint = 'https://api.straico.com/v0/models';
        $httpMethod = 'GET';

        $options = [
            'http' => [
                'header' => 'Authorization: Bearer '.$this->apiKey."\r\n",
                'method' => $httpMethod,
            ],
        ];

        $context = stream_context_create($options);

        $result = file_get_contents($endPoint, false, $context);

        return json_decode($result, JSON_OBJECT_AS_ARRAY);

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
        $temp = str_ireplace('%prompt%', $this->aiInput, $this->userPipe);
        $temp2 = str_ireplace('%answer%', $this->aiAnswer, $temp);

        `$temp2`;

    }

    /*
    * Function: getWebpage($url)
    * Input   : url of page
    * Output  : Stores retrieved page as string in $this->webPage
    * Purpose : retrieve a webpage to use in a prompt
    *
    * Remarks:
    *
    */
    private function getWebpage($url)
    {

        $options = [
            'http' => [
                'method' => 'GET',
                'header' => "Accept-language: en\r\n",
            ],
        ];

        $context = stream_context_create($options);

        // Temporarily disable error reporting
        $previous_error_reporting = error_reporting(0);

        $this->webPage = @file_get_contents($url, false, $context);

        // Check if an error occurred
        if ($this->webPage === false) {
            $error = error_get_last();
            if ($error !== null) {
                echo "Error: {$error['message']}\n";
            } else {
                echo "An unknown error occurred while fetching the webpage.\n";
            }
        } else {
            echo "Page collected and now available in token _PAGE_ to use in your prompt.\n";
        }

        // Restore the previous error reporting level
        error_reporting($previous_error_reporting);

        //echo "Page collected and now available in token _PAGE_ to use in your prompt.\n";

    }

    /*
    * Function	: getInput()
    * Input   	: none
    * Output  	: none
    * Purpose 	: get user input
    * Return	: $string with catched input
    * Remarks:
    */
    private function getInput()
    {

        if (! $this->historySwitch) {
            $this->chatHistory = '';
        }

        $input = readline($this->usrPrompt);

        // Add  to session history
        readline_add_history($input);

        return $input;

    }

    /*
    * Function: hugModels()
    * Input   : none
    * Output  : none
    * Purpose : Retrieve huggingface text models
    *
    * Remarks:
    */

    private function hugModels()
    {
        try {
            // Set up endpoint URL and API key
            $endpoint = 'https://api-inference.huggingface.co/framework/text-generation-inference';
            $apiKey = $this->apiKey;

            // Configure request options (headers and method)
            $options = [
                'http' => [
                    'header' => "Authorization: Bearer {$apiKey}\r\n".
                                "x-use-cache: 0\r\n".
                                "Content-Type: application/json\r\n".
                                $this->userAgent."\r\n",
                    'method' => 'GET',
                ],
            ];

            // Send HTTP GET request using configured context
            $context = stream_context_create($options);
            $result = file_get_contents($endpoint, false, $context);

            // Decode JSON response into array
            $answer = json_decode($result, true);

            // Process models from the response
            $this->useModels = [];
            foreach ($answer as $model) {
                $fname = $model['model_id'];
                $nameParts = explode('/', $fname);
                $tag = isset($nameParts[1]) ? $nameParts[1] : '';

                $this->useModels[] = [
                    'tag' => $tag,
                    'model' => $fname,
                    'pre' => '',
                    'past' => '',
                ];
            }
        } catch (\Exception $e) {
            echo 'Error: '.$e->getMessage();
        }
    }

    /*
    * Function: listmodels()
    * Input   : none
    * Output  : none
    * Purpose : list current models
    *
    * Remarks:
    */

    private function listModels($searchString = null)
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
                if ($arModel['model'] === $this->aiModel) {
                    echo '* ';
                }
                echo "- Name: {$arModel['tag']}\n";
                echo "- Model: {$arModel['model']}\n";
                echo "---\n";
            }
        }

        return $modelsFound;
    }
    /*
    * Function: loadModels()
    * Input   : none
    * Output  : print model list
    * Purpose : printing a nicely formated model list with info and
    *           pointer to use for /setmodel
    *
    * Remarks:
    *
    * Private function used by $this->userPrompt()
    */

    public function loadModels($name)
    {
        $this->useModels = [];
        include $this->userHome.$name;
    }

    /*
    * Function: listHelp()
    * Input   : none
    * Output  : Shows a help page
    * Purpose : Help the user with internal commands
    *
    * Remarks:
    *
    * Private function used by $this->userPrompt()
    */

    /*
    * Function: loadHistory($name)
    * Input   : filename
    * Output  : none
    * Purpose : load history
    *
    * Remarks:
    */
    private function loadHistory($name)
    {

        $this->chatHistory = '';

        $id = $this->logPath.'/'.$name.'.hist';
        $this->chatHistory = json_decode(file_get_contents($id));

        echo "Loaded your history from $name.\n";

    }

    /*
     * Function: loopmodel($userInput)
     * Input   : filename
     * Output  : none
     * Purpose : load history
     *
     * Remarks:
     */
    public function loopModels($userInput)
    {

        $prompt = $userInput;

        if (substr($userInput, 0, 1) == '/') {

            $findNeedle = explode(' ', $userInput, 2);

            if (count($findNeedle) < 2) {
                return "Input invalid to small\n";
            }

            $command = substr($findNeedle[0], 1);
            $prompt = $findNeedle[1];

            if ($command == 'academic' || $command == 'bigblog' ||
                 $command == 'dream' || $command == 'enhance' ||
                 $command == 'factcheck' || $command == 'gist' ||
                 $command == 'html2md' || $command == 'mediumblog' ||
                 $command == 'mkpwd' || $command == 'regex' ||
                 $command == 'smallblog' || $command == 'textcheck' ||
                 $command == 'todo') {

                $modName = strtoupper($command);
            } else {
                return "$command is not a valid command name.\n";
            }
        } else {
            $modName = 'BASEROLE';
        }

        $sysModel = constant('HugChat::'.$modName);

        //store current model.
        $storeName = $this->intModel;

        foreach ($this->useModels as $model) {

            $this->chatHistory = '';

            echo "\n\nModel :".$model['tag']."\n\n";

            //set endpoint
            $this->aiModel = $model['model'];

            $response = $this->apiCompletion($sysModel, $prompt);

            echo "$response\n";
        }

        // restore endPoint
        $this->setModel($storeName);

        return 'Loop done!';
    }

    /*
    * Function: saveHistory($name)
    * Input   : filename
    * Output  : a file with current history
    * Purpose : save history for later load
    *
    * Remarks:
    */
    private function saveHistory($name)
    {
        $id = $this->logPath.'/'.$name.'.hist';
        $file = json_encode($this->chatHistory);
        file_put_contents($id, $file);
        echo "Saved your history to $name.\n";

    }

    /*
    * Function: setModel($input)
    * Input   : $input - user input string
    * Output  : info on new model
    * Purpose : change llm in use
    *
    * Remarks:
    *
    * Private function used by $this->userPrompt()
    */
    public function setModel($input)
    {
        $this->chatHistory = '';

        $this->intModel = $input;

        $this->aiModel = $this->useModels[$input - 1]['model'];
	$granate = explode("/",$this->useModels[$input - 1]['model']);
	$this->pubModel = $granate[1];

        return "Model set to: $this->aiModel \n";
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
    private function stopPrompt()
    {
        echo 'Join Straico via https://platform.straico.com/signup?fpr=roelf14'."\n";
        echo "Thank you and have a nice day.\n";
        $input = '';
        exit(0);
    }

    /*
    * Function: $webSearch($strQ)
    * Input   : $strQ - search string
    * Output  : result from duckduckgo
    * Purpose : find pages for user
    *
    * Remarks:
    *
    * private function used by this->userPrompt()
    */
    private function webSearch($strQ)
    {

        $q = urlencode($strQ);

        // Get search
        $url = 'https://lite.duckduckgo.com/lite/?q='.$q.'&submit=Search';

        // retrieve url and clean it
        $tidy = new tidy;
        $tidy->parseString(file_get_contents($url));
        $tidy->cleanRepair();

        // get the search result urls
        $dom = new DOMDocument();
        $dom->loadHTML($tidy);
        $elements = $dom->getElementsByTagName('a');
        $elementsArray = [];

        foreach ($elements as $domElement) {

            $element = $dom->saveHTML($domElement);

            $arResult = explode('=', $element);
            $arPart['link'] = urldecode(implode(explode('&', $arResult[3], -1)));
            $arResult = explode('>', $element);
            $arPart['title'] = str_replace("\n", '', html_entity_decode(implode(explode('<', $arResult[1], -1))));

            $elementsArray[] = $arPart;
        }
        foreach ($elementsArray as $result) {
            echo 'Title: '.$result['title']."\nUrl  : ".$result['link']."\n\n";
        }

        return $elementsArray;
    }
}
