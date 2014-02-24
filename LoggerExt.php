<?php

class LoggerExt extends CApplicationComponent
{
	public $autoFlush=10000;
	public $autoDump=false;

	public $modules = array();
	public $controllers = array();
	public $commands = array();
	//public $logFilePattern = '%m.%c.%a.%p.log';

	//public $paramPattern = '%n_%v';

	//public $joinCharacter = '_';

	public $route = array();

	public function init()
	{
		Yii::getLogger()->autoFlush=$this->autoFlush;
		Yii::getLogger()->autoDump=$this->autoDump;
	}

}
