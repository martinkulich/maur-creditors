<?php

class myValidatorNumber extends sfValidatorNumber
{

    public function doClean($value)
    {
        $value = str_replace(' ', '', $value);
        $value = parent::doClean($value);

        return $value;
    }
}