<?php

// check https://huggingface.co/digiplay lots of nice models

//base model
$this->useModels[] = [
    'tag' => 'base',
    'model' => 'stabilityai/stable-diffusion-xl-base-1.0',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'yntecgasm',
    'model' => 'Yntec/epiCPhotoGasm',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'stablegasm',
    'model' => 'stablediffusionapi/epicphotogasm-6985',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'afrodite',
    'model' => 'stablediffusionapi/afrodite-xl-v2',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'stockphotov2',
    'model' => 'stablediffusionapi/realistic-stock-photo-v2',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'photon1',
    'model' => 'digiplay/Photon_v1',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'photoreal2',
    'model' => 'dreamlike-art/dreamlike-photoreal-2.0',
    'pre' => 'photo',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'dalle3xl',
    'model' => 'ehristoforu/dalle-3-xl-v2',
    'pre' => '',
    'past' => ', <lora:dalle-3-xl-lora-v2:0.8>',
];
$this->useModels[] = [
    'tag' => 'playground2',
    'model' => 'playgroundai/playground-v2-1024px-aesthetic',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'dreamshaper',
    'model' => 'stablediffusionapi/dreamshaperxlturbov21fix5',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'sdphoto',
    'model' => 'circulus/sd-photoreal-v2.8',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'landscape',
    'model' => 'digiplay/Landscape_PhotoReal_v1',
    'pre' => '',
    'past' => 'kkw-ph1',
];
$this->useModels[] = [
    'tag' => 'photoland',
    'model' => 'EnD-Diffusers/photography-and-landscapes',
    'pre' => 'phtdzk1',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'selfie',
    'model' => 'artificialguybr/selfiephotographyredmond-selfie-photography-lora-for-sdxl',
    'pre' => '',
    'past' => 'instagram model, discord profile picture',
];
$this->useModels[] = [
    'tag' => 'filmgrain',
    'model' => 'artificialguybr/filmgrain-redmond-filmgrain-lora-for-sdxl',
    'pre' => '',
    'past' => 'Film Grain, FilmGrainAF',
];
$this->useModels[] = [
    'tag' => 'nsfw',
    'model' => 'MysteriousAI/NSFW-gen',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'redshift',
    'model' => 'nitrosocke/redshift-diffusion',
    'pre' => ' redshift style',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'silhouette-a',
    'model' => 'DoctorDiffusion/doctor-diffusion-s-stylized-silhouette-photography-xl-lora',
    'pre' => 'sli artstyle',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'silhouette-f',
    'model' => 'DoctorDiffusion/doctor-diffusion-s-stylized-silhouette-photography-xl-lora',
    'pre' => 'in front of',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'silhouette-w',
    'model' => 'DoctorDiffusion/doctor-diffusion-s-stylized-silhouette-photography-xl-lora',
    'pre' => 'in water',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'sdxllora',
    'model' => 'ostris/photorealistic-slider-sdxl-lora',
    'pre' => 'more realistic',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'photo',
    'model' => 'spitfire4794/photo',
    'pre' => 'photo',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'drone',
    'model' => 'lordjia/drone-photography',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'moviefinal',
    'model' => 'Yntec/photoMovieXFinal',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'photosphere',
    'model' => 'Yntec/Photosphere',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'fantasy',
    'model' => 'theintuitiveye/FantasyMix',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'stickers',
    'model' => 'artificialguybr/StickersRedmond',
    'pre' => 'Stickers, Sticker',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'victorian',
    'model' => 'proxima/darkvictorian_artstyle',
    'pre' => 'darkvictorian artstyle',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'newreality',
    'model' => 'stablediffusionapi/newrealityxl-global-nsfw',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'nsfwdif',
    'model' => 'digiplay/CamelliaMix_NSFW_diffusers_v1.1',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'freedom',
    'model' => 'stablediffusionapi/explicit-freedom-nsfw-wai',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'nsfwsdxl',
    'model' => 'stablediffusionapi/pyros-nsfw-sdxl',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'nsfwxl',
    'model' => 'Dremmar/nsfw-xl',
    'pre' => '<lora:nsfw-xl-2.0:1>',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'sdnsfw',
    'model' => 'Kernel/sd-nsfw',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'openjourney',
    'model' => 'prompthero/openjourney-v4',
    'pre' => '',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'storysketch',
    'model' => 'blink7630/storyboard-sketch',
    'pre' => 'storyboard sketch',
    'past' => '',
];
$this->useModels[] = [
    'tag' => 'juggernaut10',
    'model' => 'RunDiffusion/Juggernaut-X-v10',
    'pre' => '',
    'past' => '',
];
/*
 *
 * ? stablediffusionapi/photopedia-xl-v45
 * ? UnfilteredAI/NSFW-gen-v2
 * + EnD-Diffusers/duskfalls-artificial-photography has trigger
 * + sam749/LEOSAM-s-ilmGirl-ltra-Ultra-ase-odel
 * + stablediffusionapi/uber-realistic-porn-merge - interesting
 * + stablediffusionapi/epicrealism-xl - doet zelfs de fisheye
 * + stablediffusionapi/edge-of-realism
 * + timlenardo/tdmx-edge-of-realism-dreambooth - weird
 */
