loggerext
=========

logger extensions for Yii1.x

# Configure

In config/main.php or config/console.php
```
'preload'=>array('log', 'logext'),
'import' =>array(
	...
	'application.extensions.loggerext.*'
),
'components' => array(
	...
	'logext'=>array(
	        'class'=>'LoggerExt',
	        'modules'=>array(
	                '*'=>array(
	                        'default'=>array(
	                                'logFilePattern'=>'%m.%c.%a.log'
	                        ),
	                )
	        ),
	        'controllers'=>array(
	                'site'=>array(
	                        'logFileUseParam'=>true,  //enable param used in logfile name
	                        //Parameter Name,'view, name'，first request POST, next request GET
	                        'params'=>'view',  
	                        'logFilePattern'=>'%c.%a.%p.log',
	                        'paramPattern'=>'%n_%v',
	                        'joinCharacter'=>'.'
	                ),
	                '*'=>array(
	                        'logFilePattern'=>'%c.%a.log'
	                )
	
	        ),

		// for console
                'commands'=>array(
                        'queue'=>array(
                                'logFileUseParam'=>true,
                                'params'=>'qname', 
                                'logFilePattern'=>'console.%c.%a.%p.log',
                                'paramPattern'=>'%n_%v',
                                'joinCharacter'=>'.'
                        ),
                        '*'=>array(
                                'logFilePattern'=>'%c.%a.log'
                        )

                ),
	        'route'=>array(
	                'class'=>'FileDailyLogRoute',
	                'levels'=>'info', //log level
	                'keepDays'=>7,  // keep log file at 7 days
	        ),
	)
)
```

# Filename Pattern

* %m is moduleId
* %c is controllerId / commandId
* %a is actionId
* %p is params is paramPattern 
  * %n params name 
  * %v params value
