<?php

class MyValidatorDateRange extends sfValidatorDateRange
{
  protected function configure($options = array(), $messages = array())
  {
      parent::configure($options, $messages);
      $this->setOption('from_date', new myValidatorDate(array('required'=>false)));
      $this->setOption('to_date', new myValidatorDate(array('required'=>false)));

  }
}
