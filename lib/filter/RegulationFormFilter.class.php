<?php

/**
 * Regulation filter form.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
class RegulationFormFilter extends BaseRegulationFormFilter
{

    public function configure()
    {
        sfProjectConfiguration::getActive()->loadHelpers('Url');

        $unsetablefields = array(
            'regulation_year',
        );

        foreach ($this->getWidgetSchema()->getFields() as $field => $widget) {
            if (!in_array($field, $unsetablefields)) {
                $this->unsetField($field);
            }
        }

        $this->setWidget('creditor_id', new sfWidgetFormPropelChoice(array('model' => 'Subject', 'order_by' => array('Lastname', 'asc'), 'add_empty' => true)));
        $this->setValidator('creditor_id', new sfValidatorPropelChoice(array('model' => 'Subject', 'required' => false)));
        $this->getWidgetSchema()->moveField('creditor_id', sfWidgetFormSchema::FIRST);

        $this->setWidget('contract_id', new sfWidgetFormPropelChoice(array('add_empty' => true, 'model' => 'Contract', 'order_by' => array('Name', 'asc'))));
        $this->setValidator('contract_id', new sfValidatorPropelChoice(array('model' => 'Contract', 'required' => false)));
        $this->getWidgetSchema()->moveField('contract_id', sfWidgetFormSchema::AFTER, 'creditor_id');
        $this->getWidget('creditor_id')->setAttribute('onchange', sprintf("updateSelectBox('%s','%s','%s', '%s', 'all'); ;", url_for('@update_contract_select?form_name=regulation_filters'), 'regulation_filters_creditor_id', 'regulation_filters_contract_id', 'creditor_id'));

        $contract = ContractPeer::retrieveByPK($this->getValue('contract_id'));
        if ($contract) {
            $this->getWidgetSchema()->setDefault('creditor_id', $contract->getCreditorId());
        }
    }

    public function addContractIdColumnCriteria(Criteria $criteria, $field, $value)
    {
        $criteria->add(RegulationPeer::CONTRACT_ID, $value);
        return $criteria;
    }

    public function addCreditorIdColumnCriteria(Criteria $criteria, $field, $value)
    {
        $criteria->addJoin(RegulationPeer::CONTRACT_ID, ContractPeer::ID);
        $criteria->add(ContractPeer::CREDITOR_ID, $value);
        return $criteria;
    }
}
