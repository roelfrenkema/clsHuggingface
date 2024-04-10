# clsHuggingface

## Work in progress

This class is aiming for developers. It is still very raw and depending on the free interference api.  
Use it at your own risk.

## Help

### Commands

#### /helpme      

This help

#### /exit

Leave class

#### /setmodel  \<model\>

Set model to shortname of \<model\>, aka the name you see in /listmodels

#### /savemodels

Savemodels currentmodels to file. Suggested use after /addmodels

#### /addmodel \<short\> \<model\>

choose a shortname \<short\> to show in listmodels and add Hugging \<model\>.  
A model has format like: **stabilityai/stable-diffusion-xl-base-1.0**  
The modelname can be retrieved on Hugginface by clicking the copy icon next to its name.

#### /delmodel \<short\>
Delete model with sortname \<short\>. The shortname is the name from /listmodels.

#### /getmodels

Load the models from file

#### listmodels

List loaded models

## TODO 

* retry on 503 with pause
* return on other errors

## License

clsHuggingface.php © 2024 by Roelfrenkema is licensed under CC BY-NC-SA 4.0. To view a copy of this license, visit [http://creativecommons.org/licenses/by-nc-sa/4.0/](http://creativecommons.org/licenses/by-nc-sa/4.0/).
