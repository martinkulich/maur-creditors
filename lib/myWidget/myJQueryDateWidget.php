<?php

class myJQueryDateWidget extends sfWidgetFormInput
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
        $this->addOption('format', '%date%');

        $this->setOption('type', 'text');
//        $this->setAttribute('readonly', 'readonly');
        $this->setAttribute('class', 'date_input');
    }

    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $name = $name . '[date]';

        if (is_array($value) && array_key_exists('date', $value)) {
            $value = $value['date'];
        }

        if ($value) {
            $dateTime = new DateTime($value);
            $value = $dateTime->format('d.m.Y');
        }
        $render = parent::render($name, $value, $attributes, $errors);

        return $render . $this->renderJavascript($name, $value, $attributes, $errors);
    }

    public function renderJavascript($name, $value = null, $attributes = array(), $errors = array())
    {
        $dateTime = new DateTime($value);
        $controlId = $targetId = $this->generateId($name);

        $pattern = '
            <script type="text/javascript">
                jQuery(document).ready(function() {
                    jQuery("#%control_id%").datepicker({
                        format: "dd.mm.yyyy",
                        language: "%culture%"
                    })
                    .on("changeDate", function(ev){
                           control = $("#%control_id%");
                           var date = moment(new Date(ev.date.valueOf()));
                           var formatedDate = date.format("DD.MM.YYYY");
                           control.attr("value", formatedDate);
                           control.change();
                           control.datepicker("hide");

                        })
                    .on("hide", function(ev){
                        ev.stopPropagation();
                    });
                });
            </script>';

        if ($this->getOption('type') == 'hidden') {
            $pattern .= '<div id = "%control_id%"></div>';
            $controlId .= '_control';
        }

        $replacements['%target_id%'] = $targetId;
        $replacements['%control_id%'] = $controlId;
        $replacements['%default_date%'] = $dateTime->format('d.m.Y');
        $replacements['%date_format%'] = $this->getOption('date_format');
        $replacements['%culture%'] = $this->getOption('culture');

        $javascript = str_replace(array_keys($replacements), $replacements, $pattern);
        return $javascript;
    }
}