<?php

// check https://huggingface.co/digiplay lots of nice models

//base model
$this->useModels[] = array(
	'tag' => 'mixtral',
	'model' => 'mistralai/Mixtral-8x7B-Instruct-v0.1',
	'pre' => '',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'cohere',
	'model' => 'CohereForAI/c4ai-command-r-plus',
	'pre' => '',
	'past' => ''
);
// only the small model on serverless
$this->useModels[] = array(
	'tag' => 'gemma7b',
	'model' => 'google/gemma-1.1-7b-it',
	'pre' => '',
	'past' => ''
);
?>
