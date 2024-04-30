#!/usr/bin/php

<?php
/*
 * clsHugginface.php © 2024 by Roelfrenkema is licensed under CC BY-NC-SA 4.0.
 * To view a copy of this license,
 * visit http://creativecommons.org/licenses/by-nc-sa/4.0/
 *
 */

/*
 * Define some np for testing. this way you can build your own
 * library on top of the buildin ones.
 * 
$nPrompt = ['common' => 'Ugly,Bad anatomy,Bad proportions,Bad quality ,Blurry,Cropped,Deformed,Disconnected limbs ,Out of frame,Out of focus,Dehydrated,Error ,Disfigured,Disgusting ,Extra arms,Extra limbs,Extra hands,Fused fingers,Gross proportions,Long neck,Low res,Low quality,Jpeg,Jpeg artifacts,Malformed limbs,Mutated ,Mutated hands,Mutated limbs,Missing arms,Missing fingers,Picture frame,Poorly drawn hands,Poorly drawn face,Text,Signature,Username,Watermark,Worst quality,Collage ,Pixel,Pixelated,Grainy,',
    'anatomy' => 'Bad anatomy, Bad hands, Amputee, Missing fingers, Missing hands, Missing limbs, Missing arms, Extra fingers, Extra hands, Extra limbs , Mutated hands, Mutated, Mutation, Multiple heads, Malformed limbs, Disfigured, Poorly drawn hands, Poorly drawn face, Long neck, Fused fingers, Fused hands, Dismembered, Duplicate , Improper scale, Ugly body, Cloned face, Cloned body , Gross proportions, Body horror, Too many fingers, Cross Eyes,',
    'realistic' => 'Cartoon, CGI, Render, 3D, Artwork, Illustration, 3D render, Cinema 4D, Artstation, Octane render, Painting, Oil painting, Anime , 2D , Sketch, Drawing , Bad photography, Bad photo, Deviant art,',
    'nsfw' => 'nsfw, uncensored, cleavage, nude, nipples, children,',
    'landscape' => 'Overexposed, Simple background, Plain background, Grainy , Portrait, Grayscale, Monochrome, Underexposed, Low contrast, Low quality, Dark , Distorted, White spots , Deformed structures, Macro , Multiple angles,',
    'object' => 'Asymmetry , Parts, Components , Design, Broken, Cartoon, Distorted, Extra pieces, Bad proportion, Inverted, Misaligned, Macabre , Missing parts, Oversized , Tilted,',
    'clsv1' => 'painting, sketch,  plastic, (3d), cgi, semi-realistic, cartoon, ugly, duplicate, morbid, mutilated, extra fingers, mutated hands, poorly drawn hands, poorly drawn face, mutation, deformed, blurry, bad proportions, cloned face, disfigured, out of frame, extra limbs, (bad anatomy), gross proportions, malformed limbs, missing arms, missing legs, extra arms, extra legs, fused fingers, too many fingers, long neck,',
    'clsv2' => 'ugly, duplicate, morbid, mutilated, extra fingers, mutated hands, poorly drawn hands, poorly drawn face, mutation, deformed, blurry, bad proportions, cloned face, disfigured, out of frame, extra limbs, (bad anatomy), gross proportions, malformed limbs, missing arms, missing legs, extra arms, extra legs, fused fingers, too many fingers, long neck,',
    'clsv3' => 'out of frame, duplicate, ugly, poorly drawn hands, poorly drawn face, morbid, mutated hands, extra fingers, deformed, blurry, bad anatomy, bad proportions, extra limbs, long neck, cloned face, watermark, signature, text, poorly drawn, normal quality,',
];
*/

/*
 *  We asume for this example that your installation lives in
 *  the directory git under your homedirectory. You have to make
 *  changes yourself if needed
 */

$home = $_ENV['HOME'];

// setting paths and including what we need
set_include_path($home.'/git/clsHuggingface');
require_once $home.'/git/clsHuggingface/vendor/autoload.php';
use function Laravel\Prompts\info;
use function Laravel\Prompts\textarea;

include 'clsHuggingface.php';

$hug = new Huggingface;

$hug->imgStore = $home.'/git/clsHuggingface/';
$hug->exiv2 = false;
$hug->exiv2User = 'Roelf Renkema';
$hug->exiv2Copy = 'CC BY-NC-SA 4.0';
$hug->logAll = false;
$hug->slMax = 300;
$hug->slUpdate = 30;

/*
 * Set your own models array
 * If you don't models will be retrieved from Huggingface
 */
//$hug->loadModels($home.'/git/clsHuggingface/mdl_base.php');

/*
 * Set startprompt. Needed to find your model a start.
 */
//$hug->setModel('base');

/* 
 * set your own negative prompt here
$hug->negPrompt = $nPrompt['clsv1'];
*/

/*
 * You can also set one of the buildin negative prompts
 * find there names with /listnp on the prompt
 */

$hug->getNp('common');

/*
    Start looping till finished with /exit
*/
$aiMessage = '';

while ($aiMessage !== '/exit') {

    //prompt
    $prompt = textarea('<fg=white>Prompting: '.$hug->pName.'</>');

    // process prompt
    $aiMessage = $hug->userPrompt($prompt);

    // no input available?
    if ($aiMessage == '') {
        continue;
    }

    // native answer
    //echo $aiMessage."\n\n";

    //lavarel answer
    info('<fg=cyan>'.$aiMessage.'</>');

}
?>
