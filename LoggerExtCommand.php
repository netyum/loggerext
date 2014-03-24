<?php
class LoggerExtCommand extends CConsoleCommand
{

    public function behaviors() {
        return array_merge(parent::behaviors(), array(
            'LoggerExtCommandBehavior' => array(
                    'class'=>'LoggerExtCommandBehavior',
                )
            )
        );
    }
}
