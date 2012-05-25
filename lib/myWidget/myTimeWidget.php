<?php

class myTimeWidget extends sfWidgetFormChoice
{

    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);

        if ('en' == $this->getOption('culture')) {
            $this->setOption('culture', 'en');
        }


        $this->addOption('config', '{changeMonth: true,changeYear: true,showButtonPanel: true,yearRange: \'-70:+10\'}');
        $this->addOption('culture', sfContext :: getInstance()->getUser()->getCulture());
        $this->addOption('date_format', 'dd.mm.yy');

        $this->setOption('type', 'text');
        $this->setAttribute('readonly', 'readonly');
    }

    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        if($value)
        {
            $dateTime = new DateTime($value);
            $value = $dateTime->format('d.m.Y');

        }
        $render = parent::render($name, $value, $attributes, $errors);

        return $render . $this->renderJavascript($name, $value, $attributes, $errors);
    }

    public function renderJavascript($name, $value = null, $attributes = array(), $errors = array())
    {
        if (!is_null($value)) {
            $valueDateTime = new DateTime($value);
            $value = $valueDateTime->format('y-m-d');
        }
        $controlId = $targetId = $this->generateId($name);

        $pattern = '
            <script>
                 function %control_id%calendarOnSelect(date)
                 {
                    jQuery("#%target_id%").val(date);
                    jQuery("#%target_id%").change();
                 }


                jQuery(document).ready(function() {
                    jQuery("#%control_id%").datepicker(jQuery.extend({}, {
                        onSelect:   %control_id%calendarOnSelect,
                        defaultDate: \'%default_date%\',
                        dateFormat: \'%date_format%\'
                    }))
                });
            </script>';

        if ($this->getOption('type') == 'hidden') {
            $pattern .= '<div id = "%control_id%"></div>';
            $controlId .= '_control';
        }

        $replacements['%target_id%'] = $targetId;
        $replacements['%control_id%'] = $controlId;
        $replacements['%default_date%'] = $value;
        $replacements['%date_format%'] = $this->getOption('date_format');

        $javascript = str_replace(array_keys($replacements), $replacements, $pattern);
        return $javascript;
    }

}