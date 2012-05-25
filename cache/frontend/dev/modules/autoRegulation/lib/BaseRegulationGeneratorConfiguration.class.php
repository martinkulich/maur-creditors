<?php

/**
 * regulation module configuration.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage regulation
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: configuration.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseRegulationGeneratorConfiguration extends sfModelGeneratorConfiguration
{
  public function getActionsDefault()
  {
    return array();
  }


  public function getFormActions()
  {
    return array();
  }

  public function getNewActions()
  {
    return array();
  }

  public function getEditActions()
  {
    return array();
  }

  public function getListObjectActions()
  {
    return array();
  }

  public function getListActions()
  {
    return array();
  }

  public function getListBatchActions()
  {
    return array();
  }

  public function getListParams()
  {
    return '%%creditor_firstname%% - %%creditor_lastname%% - %%contract_id%% - %%contract_name%% - %%settlement_year%% - %%start_balance%% - %%contract_activated_at%% - %%contract_balance%% - %%regulation%% - %%paid%% - %%paid_for_current_year%% - %%capitalized%% - %%teoretically_to_pay_in_current_year%% - %%end_balance%%';
  }

  public function getListLayout()
  {
    return 'tabular';
  }

  public function getListTitle()
  {
    return 'Regulation List';
  }

  public function getEditTitle()
  {
    return 'Edit Regulation';
  }

  public function getNewTitle()
  {
    return 'New Regulation';
  }

  public function getFilterDisplay()
  {
    return array();
  }

  public function getFormDisplay()
  {
    return array();
  }

  public function getEditDisplay()
  {
    return array();
  }

  public function getNewDisplay()
  {
    return array();
  }

  public function getListDisplay()
  {
    return array(  0 => 'creditor_firstname',  1 => 'creditor_lastname',  2 => 'contract_id',  3 => 'contract_name',  4 => 'settlement_year',  5 => 'start_balance',  6 => 'contract_activated_at',  7 => 'contract_balance',  8 => 'regulation',  9 => 'paid',  10 => 'paid_for_current_year',  11 => 'capitalized',  12 => 'teoretically_to_pay_in_current_year',  13 => 'end_balance',);
  }

  public function getFieldsDefault()
  {
    return array(
      'creditor_firstname' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'creditor_lastname' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'contract_id' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'contract_name' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'settlement_year' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'start_balance' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'contract_activated_at' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Date',),
      'contract_balance' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'regulation' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'paid' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'paid_for_current_year' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'capitalized' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'teoretically_to_pay_in_current_year' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'end_balance' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
    );
  }

  public function getFieldsList()
  {
    return array(
      'creditor_firstname' => array(),
      'creditor_lastname' => array(),
      'contract_id' => array(),
      'contract_name' => array(),
      'settlement_year' => array(),
      'start_balance' => array(),
      'contract_activated_at' => array(),
      'contract_balance' => array(),
      'regulation' => array(),
      'paid' => array(),
      'paid_for_current_year' => array(),
      'capitalized' => array(),
      'teoretically_to_pay_in_current_year' => array(),
      'end_balance' => array(),
    );
  }

  public function getFieldsFilter()
  {
    return array(
      'creditor_firstname' => array(),
      'creditor_lastname' => array(),
      'contract_id' => array(),
      'contract_name' => array(),
      'settlement_year' => array(),
      'start_balance' => array(),
      'contract_activated_at' => array(),
      'contract_balance' => array(),
      'regulation' => array(),
      'paid' => array(),
      'paid_for_current_year' => array(),
      'capitalized' => array(),
      'teoretically_to_pay_in_current_year' => array(),
      'end_balance' => array(),
    );
  }

  public function getFieldsForm()
  {
    return array(
      'creditor_firstname' => array(),
      'creditor_lastname' => array(),
      'contract_id' => array(),
      'contract_name' => array(),
      'settlement_year' => array(),
      'start_balance' => array(),
      'contract_activated_at' => array(),
      'contract_balance' => array(),
      'regulation' => array(),
      'paid' => array(),
      'paid_for_current_year' => array(),
      'capitalized' => array(),
      'teoretically_to_pay_in_current_year' => array(),
      'end_balance' => array(),
    );
  }

  public function getFieldsEdit()
  {
    return array(
      'creditor_firstname' => array(),
      'creditor_lastname' => array(),
      'contract_id' => array(),
      'contract_name' => array(),
      'settlement_year' => array(),
      'start_balance' => array(),
      'contract_activated_at' => array(),
      'contract_balance' => array(),
      'regulation' => array(),
      'paid' => array(),
      'paid_for_current_year' => array(),
      'capitalized' => array(),
      'teoretically_to_pay_in_current_year' => array(),
      'end_balance' => array(),
    );
  }

  public function getFieldsNew()
  {
    return array(
      'creditor_firstname' => array(),
      'creditor_lastname' => array(),
      'contract_id' => array(),
      'contract_name' => array(),
      'settlement_year' => array(),
      'start_balance' => array(),
      'contract_activated_at' => array(),
      'contract_balance' => array(),
      'regulation' => array(),
      'paid' => array(),
      'paid_for_current_year' => array(),
      'capitalized' => array(),
      'teoretically_to_pay_in_current_year' => array(),
      'end_balance' => array(),
    );
  }


  /**
   * Gets the form class name.
   *
   * @return string The form class name
   */
  public function getFormClass()
  {
    return 'regulationForm';
  }

  public function hasFilterForm()
  {
    return true;
  }

  /**
   * Gets the filter form class name
   *
   * @return string The filter form class name associated with this generator
   */
  public function getFilterFormClass()
  {
    return 'regulationFormFilter';
  }

  public function getPagerClass()
  {
    return 'sfPropelPager';
  }

  public function getPagerMaxPerPage()
  {
    return 20;
  }

  public function getDefaultSort()
  {
    return array('contract_id asc', 'settlement_year desc');
  }

  public function getPeerMethod()
  {
    return 'doSelect';
  }

  public function getPeerCountMethod()
  {
    return 'doCount';
  }
}
