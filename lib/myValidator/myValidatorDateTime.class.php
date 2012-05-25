<?php

class myValidatorDateTime extends myValidatorDate
{

    protected function configure($options = array(), $messages = array())
    {
        parent::configure($options, $messages);

        $this->setOption('with_time', true);
    }
}
