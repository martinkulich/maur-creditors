<?php

/**
 * EventGroup filter form.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
class EventGroupFormFilter extends BaseEventGroupFormFilter
{
  public function configure()
  {
      $this->unsetField('playground_id');
  }
}
