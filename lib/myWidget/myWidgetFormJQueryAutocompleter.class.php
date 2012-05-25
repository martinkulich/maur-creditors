<?php


class myWidgetFormJQueryAutocompleter extends sfWidgetFormInput
{

  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('url');
    $this->addOption('value_callback');
    $this->addOption('config', '{ }');

    // this is required as it can be used as a renderer class for sfWidgetFormChoice
    $this->addOption('choices');

    parent::configure($options, $attributes);
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The date displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $visibleValue = $this->getOption('value_callback') ? call_user_func($this->getOption('value_callback'), $value) : $value;

    return $this->renderTag('input', array('type' => 'hidden', 'name' => $name, 'value' => $value)).
           parent::render('autocomplete_'.$name, $visibleValue, $attributes, $errors).
           sprintf(<<<EOF
<script type="text/javascript">
  jQuery(document).ready(function() {
    jQuery("#%s")
    .autocomplete('%s', jQuery.extend({}, {
      dataType: 'json',
      parse:    function(data) {
        var parsed = [];
        for (key in data) {
          parsed[parsed.length] = { data: [ data[key], key ], value: data[key], result: data[key] };
        }
        return parsed;
      }
    }, %s))
    .result(function(event, data) { jQuery("#%s").val(data[1]); });
  });
</script>
EOF
      ,
      $this->generateId('autocomplete_'.$name),
      $this->getOption('url'),
      $this->getOption('config'),
      $this->generateId($name)
    );
  }

  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * @return array An array of stylesheet paths
   */
  public function getStylesheets()
  {
    return array('/sfFormExtraPlugin/css/jquery.autocompleter.css' => 'all');
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavascripts()
  {
    return array('/sfFormExtraPlugin/js/jquery.autocompleter.js');
  }
}
