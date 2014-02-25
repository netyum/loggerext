loggerext
=========

logger extensions for Yii1.x

# INSTALLATION

Move loggerext folder to extensions folder.

# CONFIGURATION

In config/main.php for web application


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
				'logPathPattern'=>'%c',
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

In config/console.php for console application

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
		'autoFlush'=>1,
		'autoDump'=>true,
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
	       
		'route'=>array(
			'class'=>'FileDailyLogRoute',
			'levels'=>'info', //log level
			'keepDays'=>7,  // keep log file at 7 days
			'logPath'=>'/data/logs'
		),
	)
)
```

# FILENAME PATTERN

* %m is moduleId
* %c is controllerId / commandId
* %a is actionId
* %p is params is paramPattern 
  * %n params name 
  * %v params value

# QUICK START

create your controller and extend LoggerExtController

e.g

```
class TestController extends LoggerExtController
{
	public function actionIndex() {
		Yii::log('Hello Controller', CLogger::LEVEL_INFO);
	}
}
```

run it in browers

```
http://path/to/index.php?r=test
```

look to runtime folder and find test.index_(Y-m-d).log file.


another create your command and extend LoggerExtCommand

e.g.

```
class QueueController extends LoggerExtCommand
{
	public function actionWorker($qname) {
		Yii::log('Hello Command', CLogger::LEVEL_INFO);
	}
}
```

run it in command line.

```
yiic queue worker --qname=default
```

look to runtime folder and find console.queue.worker.qname_default_(Y-m-d).log
