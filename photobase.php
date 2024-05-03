<?php

// check https://huggingface.co/digiplay lots of nice models

// 1 base model
$this->useModels[] = [
    'tag' => 'base',
    'model' => 'stabilityai/stable-diffusion-xl-base-1.0',
    'pre' => '',
    'past' => '',
];
// 2 great reality model
$this->useModels[] = [
    'tag' => 'AR_v1.8.1',
    'model' => 'digiplay/AbsoluteReality_v1.8.1',
    'pre' => '',
    'past' => '',
];
// 3 great reality model
$this->useModels[] = [
    'tag' => 'Duchaiten',
    'model' => 'stablediffusionapi/duchaiten-real3d-nsfw-xl',
    'pre' => '',
    'past' => '',
];
// 4 great reality model
$this->useModels[] = [
    'tag' => 'Fluently',
    'model' => 'fluently/Fluently-XL-v4',
    'pre' => '',
    'past' => '',
];
// 5 great reality model - doet zelfs de fisheye
$this->useModels[] = [
    'tag' => 'Epic Realism',
    'model' => 'stablediffusionapi/epicrealism-xl',
    'pre' => '',
    'past' => '',
];
// 6 great reality model
$this->useModels[] = [
    'tag' => 'Uber realistic',
    'model' => 'stablediffusionapi/uber-realistic-porn-merge',
    'pre' => '',
    'past' => '',
];
