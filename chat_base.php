<?php

// check https://huggingface.co/digiplay lots of nice models

//base model
$this->useModels[] = [
    'tag' => 'mixtral',
    'model' => 'mistralai/Mixtral-8x7B-Instruct-v0.1',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'nousmix',
    'model' => 'NousResearch/Nous-Hermes-2-Mixtral-8x7B-DPO',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'cohere',
    'model' => 'CohereForAI/c4ai-command-r-plus',
    'pre' => '',
    'past' => '',
];
// only the small model on serverless
$this->useModels[] = [
    'tag' => 'gemma7b',
    'model' => 'google/gemma-1.1-7b-it',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'zephyr',
    'model' => 'HuggingFaceH4/zephyr-orpo-141b-A35b-v0.1',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'llama',
    'model' => 'meta-llama/Meta-Llama-3-70B-Instruct',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'microsoft',
    'model' => 'microsoft/Phi-3-mini-128k-instruct',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'dolphin',
    'model' => 'cognitivecomputations/dolphin-2.5-mixtral-8x7b',
    'pre' => '',
    'past' => '',
];
