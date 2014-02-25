<?php

class LoggerExtCommandBehavior extends CConsoleCommandBehavior
{
	public function beforeAction($event)
	{
        $actionId = strtolower($event->action);
        $commandId = strtolower($event->sender->getName());
		$logCommands = Yii::app()->logext->commands;

		$logCommand = array();
        if (isset($logCommands[$commandId]) ) {
            $logCommand = $logCommands[$commandId];
        }
        else if (isset($logCommands['*'])) {
            $logCommand = $logCommands['*'];
		}

        $logFile = "console.log";
		if ( isset($logCommand['logFilePattern']) ) {
            $pattern = $logCommand['logFilePattern'];
            $logFile = $pattern;
            if (
                isset($logCommand['logFileUseParam']) &&
                $logCommand['logFileUseParam'] &&
                isset($logCommand['params']) &&
                isset($logCommand['paramPattern']) &&
                isset($logCommand['joinCharacter'])
            ) {
                $params = explode(',', $logCommand['params']);
                $joinCharacter = $logCommand['joinCharacter'];
                $paramPattern = $logCommand['paramPattern'];
                $pp = array();

                $methodName = 'action'.$actionId;
                $method=new ReflectionMethod($event->sender,$methodName);
                $paramsName = array();
				$paramsNow = array();
                foreach($method->getParameters() as $i=>$param)
                {
	                $name=$param->getName();
	                array_push($paramsName, $name);
				}
                for($i=0; $i<count($paramsName); $i++) {
					$paramsNow[$paramsName[$i]] = $event->params[$i];
                }

                foreach($params as $key ) {
                    if (isset($paramsNow[trim($key)])) {
                        $v = $paramsNow[trim($key)];
                    }
                    else $v = NULL;
                    if (!is_null($v)) {
                        $pp[] = str_replace(array('%n', '%v'), array(trim($key), $v), $paramPattern);
                    }
				}
				$logFile = str_replace('%p', join($joinCharacter, $pp), $pattern);
                $find = array('%c', '%a');
                $replace = array($commandId, $actionId);
                $logFile = str_replace($find, $replace, $logFile);
			}
        }

        $logPath = "";
        if ( isset($logCommand['logPathPattern'])) {
            $logPath = $logCommand['logPathPattern'];

            $find = array('%m', '%c', '%a');
            $replace = array($moduleId, $controllerId, $actionId);
            $logPath = str_replace($find, $replace, $logPath);

            $find = array('<', '>', '*', '?', '/', '\\', '"', '|');
            $logPath = trim(str_replace($find, '', $logPath));
        }

        $route = Yii::app()->logext->route;
        
        if ($route['class'] == 'FileDailyLogRoute') {
            $route['logFile'] = $logFile;
            $route=Yii::createComponent($route);
            $route->init();

            if ($logPath!="") {
                $route->logPath = $route->logPath.'/'.$logPath;
            }
            Yii::app()->log->setRoutes(array('controller'=>$route));
        }

	}

}
