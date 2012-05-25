<?php

class myJQueryDateWidgetOld extends sfWidgetFormDate
{
    protected function configure($options = array (), $attributes = array ())
    {
        parent::configure($options, $attributes);

        if ('en' == $this->getOption('culture'))
        {
            $this->setOption('culture', 'en');
        }

        $this->setOption('format', '%day%/%month%/%year%');
        $currentYear = (integer) date('Y');
        $yearsArray = range($currentYear -70 , $currentYear + 10);
        $years = array_combine($yearsArray,$yearsArray);
        $this->setOption('years', $years);

        $this->addOption('config', '{changeMonth: true,changeYear: true,showButtonPanel: true,yearRange: \'-70:+10\'}');
        $this->addOption('culture', \sfContext :: getInstance()->getUser()->getCulture());
        $this->addOption('image', \sfContext :: getInstance()->getRequest()->getRelativeUrlRoot().'/images/ico/date.png');
        $this->addOption('disabled_dates');
        $this->addOption('min_date');
    }

    public function render($name, $value = null, $attributes = array (), $errors = array ())
    {
        return $this->renderDate($name, $value, $attributes, $errors).$this->renderJavascript($name, $value, $attributes, $errors);
    }

    public function renderDate($name, $value = null, $attributes = array(), $errors = array())
    {
        // convert value to an array
        $default = array('year' => null, 'month' => null, 'day' => null);
        if (is_array($value))
        {
            $value = array_merge($default, $value);
        }
        else
        {
            $value = (string) $value == (string) (integer) $value ? (integer) $value : strtotime($value);
            if (false === $value) {
                $value = $default;
            }
            else {
                $value = array('year' => date('Y', $value), 'month' => date('n', $value), 'day' => date('j', $value));
            }
        }

        $date = array();
        $emptyValues = $this->getOption('empty_values');

        // days
        $widget = new \sfWidgetFormSelect(array('id_format'=>$this->getOption('id_format'), 'choices' => $this->getOption('can_be_empty') ? array('' => $emptyValues['day']) + $this->getOption('days') : $this->getOption('days')), array_merge($this->attributes, $attributes));
        $date['%day%'] = $widget->render($name.'[day]', $value['day']);

        // months
        $widget = new \sfWidgetFormSelect(array('id_format'=>$this->getOption('id_format'), 'choices' => $this->getOption('can_be_empty') ? array('' => $emptyValues['month']) + $this->getOption('months') : $this->getOption('months')), array_merge($this->attributes, $attributes));
        $date['%month%'] = $widget->render($name.'[month]', $value['month']);

        // years
        $widget = new \sfWidgetFormSelect(array('id_format'=>$this->getOption('id_format'), 'choices' => $this->getOption('can_be_empty') ? array('' => $emptyValues['year']) + $this->getOption('years') : $this->getOption('years')), array_merge($this->attributes, $attributes));
        $date['%year%'] = $widget->render($name.'[year]', $value['year']);

        return strtr($this->getOption('format'), $date);
    }


    public function renderJavascript($name, $value = null, $attributes = array(), $errors = array())
    {
        $prefix = $this->generateId($name);

        $disabledDatesOption =(array) $this->getOption('disabled_dates');
        $disabledDates = json_encode($disabledDatesOption);
        
        $minDateOption = $this->getOption('min_date');
        $useMinDate = isset($minDateOption) ? 'true' : 'false';
        $minDate = null;
        if($useMinDate)
        {
            $minDateDay = date('d',strtotime($minDateOption));
            $minDateMonth = date('m',strtotime($minDateOption))-1; #musi se odecist 1 protoze v JS ma leden poradove cislo 0
            $minDateYear = date('Y',strtotime($minDateOption));

            $minDate = sprintf('%s,%s,%s', $minDateYear, $minDateMonth, $minDateDay);
        }

        $image = '';
        if (false !== $this->getOption('image'))
        {
            $image = sprintf(', buttonImage: "%s", buttonImageOnly: true', $this->getOption('image'));
        }

        return $this->renderTag('input', array('type' => 'hidden', 'size' => 10, 'id' => $id = $this->generateId($name).'_jquery_control', 'disabled' => 'disabled')).
        sprintf(<<<EOF
  <script type="text/javascript">
  function wfd_%s_read_linked()
  {
    jQuery("#%s").val(jQuery("#%s").val() + "/" + jQuery("#%s").val() + "/" + jQuery("#%s").val());

    return {};
  }

  function wfd_%s_update_linked(date)
  {
    var year = Number(date.substring(0, 4));
    var day = Number(date.substring(8));
    var month = Number(date.substring(5,7));

    jQuery("#%s").val(year);
    jQuery("#%s").val(month);
    jQuery("#%s").val(day);
  }

  function wfd_%s_check_linked_days()
  {
    var daysInMonth = 32 - new Date(jQuery("#%s").val(), jQuery("#%s").val() - 1, 32).getDate();
    jQuery("#%s option").attr("disabled", "");
    jQuery("#%s option:gt(" + (%s) +")").attr("disabled", "disabled");

    if (jQuery("#%s").val() > daysInMonth)
    {
      jQuery("#%s").val(daysInMonth);
    }
  }

  jQuery(document).ready(function() {
    jQuery("#%s").datepicker(jQuery.extend({}, {
      minDate:    new Date(%s, 1 - 1, 1),
      maxDate:    new Date(%s, 12 - 1, 31),
      beforeShow: wfd_%s_read_linked,
      onSelect:   wfd_%s_update_linked,
      showOn:     "button",
      
      beforeShowDay: function(date)
      {
          var key;
          var date_as_string;
          var month;
          var disabled_dates = %s;
          var min_date = new Date();
          var use_min_date = %s;
            min_date.setFullYear(%s);

          for (key in disabled_dates)
          {
            month = date.getMonth()+1;
            date_as_string = date.getFullYear()+'-'+(month)+'-'+date.getDate();
            if(use_min_date && (min_date > date) || (date_as_string == disabled_dates[key]))
            {
                return [false,'class_false'];
            }
          }
            return [true,'class_true'];
          }


          %s
      }, jQuery.datepicker.regional["%s"], %s, {dateFormat: "yy-mm-dd"}));
  });

  jQuery("#%s, #%s, #%s").change(wfd_%s_check_linked_days);
</script>
EOF
            ,
            $prefix, $id,
            $this->generateId($name.'[year]'), $this->generateId($name.'[month]'), $this->generateId($name.'[day]'),
            $prefix,
            $this->generateId($name.'[year]'), $this->generateId($name.'[month]'), $this->generateId($name.'[day]'),
            $prefix,
            $this->generateId($name.'[year]'), $this->generateId($name.'[month]'),
            $this->generateId($name.'[day]'), $this->generateId($name.'[day]'),
            ($this->getOption('can_be_empty') ? 'daysInMonth' : 'daysInMonth-1'),
            $this->generateId($name.'[day]'), $this->generateId($name.'[day]'),
            $id,
            min($this->getOption('years')), max($this->getOption('years')),
            $prefix, $prefix,
            $disabledDates, $useMinDate, $minDate,
            $image, $this->getOption('culture'), $this->getOption('config'),
            $this->generateId($name.'[day]'), $this->generateId($name.'[month]'), $this->generateId($name.'[year]'),
            $prefix
        );
        
    }

}