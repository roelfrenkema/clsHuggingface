<?php

//base model
$hug->useModels[] = array(
	'tag' => 'base',
	'model' => 'stabilityai/stable-diffusion-xl-base-1.0',
	'pre' => '',
	'past' => ''
);

//dall-e model
$hug->useModels[] = array(
	'tag' => 'dalle',
	'model' => 'ehristoforu/dalle-3-xl-v2',
	'pre' => '',
	'past' => ''
);

//midjourney model
$hug->useModels[] = array(
	'tag' => 'midjourney',
	'model' => 'prompthero/openjourney-v4',
	'pre' => '',
	'past' => ''
);

//leonardo model
$hug->useModels[] = array(
	'tag' => 'leonardo_style',
	'model' => 'goofyai/Leonardo_Ai_Style_Illustration',
	'pre' => 'leonardo style',
	'past' => ''
);

//leonardo model
$hug->useModels[] = array(
	'tag' => 'leonardo_illustration',
	'model' => 'goofyai/Leonardo_Ai_Style_Illustration',
	'pre' => 'illustration',
	'past' => ''

);
//leonardo model
$hug->useModels[] = array(
	'tag' => 'leonardo_vector',
	'model' => 'goofyai/Leonardo_Ai_Style_Illustration',
	'pre' => 'vector art',
	'past' => ''
);

?>
