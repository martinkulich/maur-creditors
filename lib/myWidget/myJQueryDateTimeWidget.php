<?php

class myJQueryDateTimeWidget extends sfWidgetFormDateTime
{

    protected function getDateWidget($attributes = array())
    {
        return new myJQueryDateWidget($this->getOptionsFor('date'), $this->getAttributesFor('date', $attributes));
    }
}