# clsHuggingface

The class will check for 503 errors usually pointing to the fact that the model wasnt loaded yet.
Now it will retry with an incrementing wait time om 30 sec and try again. Other API errors will  

## Work in progress

This class is aiming for developers. It is still very raw and depending on the free interference api.  
Use it at your own risk.

## Help

Commands:

### /helpme 

This help

### /exit 

Leave class

### /setmodel  <model> 

Set model to tagname of model

### /listmodels  

Listmodels format tag - model

### /setnp 

Set a negative prompt

### /loop <prompt> 

Loop through loaded models with prompt.

### /loadmodels <path/name>

<path/name> from shell homedir starting with a slash or
just leave ot the path in name is in your home dir. 





## License

clsHuggingface.php © 2024 by Roelfrenkema is licensed under CC BY-NC-SA 4.0. To view a copy of this license, visit [http://creativecommons.org/licenses/by-nc-sa/4.0/](http://creativecommons.org/licenses/by-nc-sa/4.0/).
