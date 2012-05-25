<?php
class myValidatorHexColor extends sfValidatorRegex
{
    protected function configure($options = array(), $messages = array())
    {
        parent::configure($options, $messages);
        $this->setOption('pattern', '/^[#]?([a-f0-9]{6})$/');
        
    }

    protected function doClean($value)
    {
      $clean = parent::doClean($value);
//      $clean = str_replace('#', '', $clean);
      return $clean;
    } 
}