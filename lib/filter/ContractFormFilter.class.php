<?php

/**
 * Contract filter form.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
class ContractFormFilter extends BaseContractFormFilter
{

    public function configure()
    {
        $this->getWidgetSchema()->moveField('name', sfWidgetFormSchema::FIRST);

        $this->setWidget('created_at', new MyJQueryFormFilterDate());
        $this->setWidget('activated_at', new MyJQueryFormFilterDate());

        $this->setValidator('created_at', new MyValidatorDateRange(array('required' => false)));
        $this->setValidator('activated_at', new MyValidatorDateRange(array('required' => false)));

        $this->getWidgetSchema()->moveField('closed_at', sfWidgetFormSchema::AFTER, 'activated_at');

        $this->setYesNoField('activated');
        $this->getWidgetSchema()->moveField('activated', sfWidgetFormSchema::BEFORE, 'activated_at');
        $this->setYesNoField('closed');
        $this->getWidgetSchema()->moveField('closed', sfWidgetFormSchema::BEFORE, 'closed_at');

        $periodChoices = array_merge(array(''=>''), Contract::getPeriods());
        $this->setWidget('period', new sfWidgetFormChoice(array('choices' => $periodChoices), array('class' => 'span2')));
        $this->setValidator('period', new sfValidatorChoice(array('choices' => array_keys($periodChoices), 'required' => false)));
    }

    public function addActivatedColumnCriteria(Criteria $criteria, $field, $value)
    {
        if ($value == 1) {
            $criteria->add(ContractPeer::ACTIVATED_AT, null, Criteria::ISNOTNULL);
        } elseif ($value == 0) {
            $criteria->add(ContractPeer::ACTIVATED_AT, null, Criteria::ISNULL);
        }

        return $criteria;
    }

    public function addClosedColumnCriteria(Criteria $criteria, $field, $value)
    {
        if ($value == 1) {
            $criteria->add(ContractPeer::CLOSED_AT, null, Criteria::ISNOTNULL);
        } elseif ($value == 0) {
            $criteria->add(ContractPeer::CLOSED_AT, null, Criteria::ISNULL);
        }

        return $criteria;
    }
}
