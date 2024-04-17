<?php

// check https://huggingface.co/digiplay lots of nice models

//base model
$this->useModels[] = array(
	'tag' => 'base',
	'model' => 'stabilityai/stable-diffusion-xl-base-1.0',
	'pre' => '',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'yntecgasm',
	'model' => 'Yntec/epiCPhotoGasm',
	'pre' => '',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'stablegasm',
	'model' => 'stablediffusionapi/epicphotogasm-6985',
	'pre' => '',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'afrodite',
	'model' => 'stablediffusionapi/afrodite-xl-v2',
	'pre' => '',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'stockphotov2',
	'model' => 'stablediffusionapi/realistic-stock-photo-v2',
	'pre' => '',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'photon1',
	'model' => 'digiplay/Photon_v1',
	'pre' => '',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'photoreal2',
	'model' => 'dreamlike-art/dreamlike-photoreal-2.0',
	'pre' => 'photo',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'dalle3xl',
	'model' => 'ehristoforu/dalle-3-xl-v2',
	'pre' => '',
	'past' => ', <lora:dalle-3-xl-lora-v2:0.8>'
);
$this->useModels[] = array(
	'tag' => 'playground2',
	'model' => 'playgroundai/playground-v2-1024px-aesthetic',
	'pre' => '',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'dreamshaper',
	'model' => 'stablediffusionapi/dreamshaperxlturbov21fix5',
	'pre' => '',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'sdphoto',
	'model' => 'circulus/sd-photoreal-v2.8',
	'pre' => '',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'landscape',
	'model' => 'digiplay/Landscape_PhotoReal_v1',
	'pre' => '',
	'past' => 'kkw-ph1'
);
$this->useModels[] = array(
	'tag' => 'photoland',
	'model' => 'EnD-Diffusers/photography-and-landscapes',
	'pre' => 'phtdzk1',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'selfie',
	'model' => 'artificialguybr/selfiephotographyredmond-selfie-photography-lora-for-sdxl',
	'pre' => '',
	'past' => 'instagram model, discord profile picture'
);
$this->useModels[] = array(
	'tag' => 'filmgrain',
	'model' => 'artificialguybr/filmgrain-redmond-filmgrain-lora-for-sdxl',
	'pre' => '',
	'past' => 'Film Grain, FilmGrainAF'
);
$this->useModels[] = array(
	'tag' => 'nsfw',
	'model' => 'MysteriousAI/NSFW-gen',
	'pre' => '',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'redshift',
	'model' => 'nitrosocke/redshift-diffusion',
	'pre' => ' redshift style',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'silhouette-a',
	'model' => 'DoctorDiffusion/doctor-diffusion-s-stylized-silhouette-photography-xl-lora',
	'pre' => 'sli artstyle',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'silhouette-f',
	'model' => 'DoctorDiffusion/doctor-diffusion-s-stylized-silhouette-photography-xl-lora',
	'pre' => 'in front of',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'silhouette-w',
	'model' => 'DoctorDiffusion/doctor-diffusion-s-stylized-silhouette-photography-xl-lora',
	'pre' => 'in water',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'sdxllora',
	'model' => 'ostris/photorealistic-slider-sdxl-lora',
	'pre' => 'more realistic',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'photo',
	'model' => 'spitfire4794/photo',
	'pre' => 'photo',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'drone',
	'model' => 'lordjia/drone-photography',
	'pre' => '',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'moviefinal',
	'model' => 'Yntec/photoMovieXFinal',
	'pre' => '',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'photosphere',
	'model' => 'Yntec/Photosphere',
	'pre' => '',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'fantasy',
	'model' => 'theintuitiveye/FantasyMix',
	'pre' => '',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'stickers',
	'model' => 'artificialguybr/StickersRedmond',
	'pre' => 'Stickers, Sticker',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'victorian',
	'model' => 'proxima/darkvictorian_artstyle',
	'pre' => 'darkvictorian artstyle',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'newreality',
	'model' => 'stablediffusionapi/newrealityxl-global-nsfw',
	'pre' => '',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'nsfwdif',
	'model' => 'digiplay/CamelliaMix_NSFW_diffusers_v1.1',
	'pre' => '',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'freedom',
	'model' => 'stablediffusionapi/explicit-freedom-nsfw-wai',
	'pre' => '',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'nsfwsdxl',
	'model' => 'stablediffusionapi/pyros-nsfw-sdxl',
	'pre' => '',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'nsfwxl',
	'model' => 'Dremmar/nsfw-xl',
	'pre' => '<lora:nsfw-xl-2.0:1>',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'sdnsfw',
	'model' => 'Kernel/sd-nsfw',
	'pre' => '',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'openjourney',
	'model' => 'prompthero/openjourney-v4',
	'pre' => '',
	'past' => ''
);
$this->useModels[] = array(
	'tag' => 'storysketch',
	'model' => 'blink7630/storyboard-sketch',
	'pre' => 'storyboard sketch',
	'past' => ''
);

?>
