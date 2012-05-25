<?php

class TranslateService
{
    protected $translator = null;
    protected $culture = null;
    
    public function  __construct($translator = null)
    {
        if(is_null($translator))
        {
            $translator = sfContext::getInstance()->getI18N();
        }
        $this->translator = $translator;
    }

    public function __($string, array $args = array(), $catalogue = null)
    {
        return $this->translator->__($string, $args, $catalogue);
    }

    public function getCulture()
    {
        if(is_null($this->culture))
        {
            $cultureLong = sfContext::getInstance()->getUser()->getCulture();
            $cultureParts = explode('_', $cultureLong);
            $this->culture = reset($cultureParts);
            $cultures = $this->getCultures();
            if(!in_array($this->culture, $cultures))
            {
                $this->culture = reset($cultures);
            }

        }
        return $this->culture;
    }

    public function getCultures()
    {
        return array('cs', 'en');
    }


}
