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
	'tag' => 'nousmix',
	'model' => 'NousResearch/Nous-Hermes-2-Mixtral-8x7B-DPO',
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
$this->useModels[] = array(
	'tag' => 'zephyr',
	'model' => 'HuggingFaceH4/zephyr-orpo-141b-A35b-v0.1',
	'pre' => '',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'llama',
	'model' => 'meta-llama/Meta-Llama-3-70B-Instruct',
	'pre' => '',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'microsoft',
	'model' => 'microsoft/DialoGPT-large',
	'pre' => '',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'dolphin',
	'model' => 'cognitivecomputations/dolphin-2.5-mixtral-8x7b',
	'pre' => '',
	'past' => ''
);

?>
