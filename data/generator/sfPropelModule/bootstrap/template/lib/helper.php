[?php

/**
 * <?php echo $this->getModuleName() ?> module configuration.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage <?php echo $this->getModuleName()."\n" ?>
 * @author     ##AUTHOR_NAME##
 */
abstract class Base<?php echo ucfirst($this->getModuleName()) ?>GeneratorHelper extends sfModelGeneratorHelper
{
  public function getUrlForAction($action)
  {
    return 'list' == $action ? '<?php echo $this->params['route_prefix'] ?>' : '<?php echo $this->params['route_prefix'] ?>_'.$action;
  }

  public function linkToNew($params)
  {
    return '<li class="sf_admin_action_new">'.link_to(__($params['label'], array(), 'sf_admin'), '@'.$this->getUrlForAction('new'), array('class'=>'modal_link')).'</li>';
  }

  public function linkToEdit($object, $params)
  {
    return '<li class="sf_admin_action_edit">'.link_to('<i class="icon-edit icon-white"></i> '.__($params['label'], array(), 'sf_admin'), $this->getUrlForAction('edit'), $object, array('class'=>'btn btn-primary modal_link')).'</li>';
  }

  public function linkToList($params)
  {
    return '<li class="sf_admin_action_list">'.link_to('<i class="icon-list icon"></i> '.__($params['label'], array(), 'sf_admin'), '@'.$this->getUrlForAction('list'), array('class'=>'btn')).'</li>';
  }

  public function linkToFilters($params)
  {
    return '<li class="sf_admin_action_filters">'.link_to(__($params['label'], array(), 'sf_admin'), '@'.$this->getUrlForAction('filters'), array('class'=>'modal_link')).'</li>';
  }

  public function linkToReset($params)
  {
    return '<li class="sf_admin_action_filters text-red">'.link_to(__($params['label'], array(), 'sf_admin'), '@'.$this->getUrlForAction('reset'), array('class'=>'')).'</li>';
  }

  public function linkToPrint()
  {
    return '<li class="sf_admin_action_print"><a href=# onclick="window.print();return false;">'.__('Print').'</a></li>';
  }
  
  public function linkToSave($object, $params)
  {
    return '<li class="sf_admin_action_save">
                <button type="submit" class="btn btn-primary">
                    <i class="icon-ok icon-white">
                    </i> '.' '.__($params['label'], array(), 'sf_admin').
                '</button>
            </li>';

  }

  public function linkToFilter()
  {
    return '<li class="sf_admin_action_filter">
                <button type="submit" class="btn btn-primary">
                    <i class="icon-ok icon-white">
                    </i> '.' '.__('Filter', array(), 'sf_admin').
                '</button>
            </li>';

  }

   public function linkToDelete($object, $params)
  {
    if ($object->isNew())
    {
      return '';
    }

    return '<li class="sf_admin_action_delete">'.link_to('<i class="icon-trash icon-white"></i> '.__($params['label'], array(), 'sf_admin'), $this->getUrlForAction('delete'), $object, array('class'=>'btn btn-danger','method' => 'delete', 'confirm' => !empty($params['confirm']) ? __($params['confirm'], array(), 'sf_admin') : $params['confirm'])).'</li>';
  }

}
