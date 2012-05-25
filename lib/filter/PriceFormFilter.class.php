<?php

/**
 * Price filter form.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
class PriceFormFilter extends BasePriceFormFilter
{

    public function configure()
    {
        $fieldsToUnset = array(
            'playground_id',
            'currency_code',
            'price_amount_list',
            'color',
            'sale_price_list',
        );

        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
        }
        $this->disableCSRFProtection();
        $this->getValidator('name')->setOption('required', true);
    }

    public function doBuildCriteria(array $values)
    {
        $criteria = parent::doBuildCriteria($values);
        $criteria->add(PricePeer::PLAYGROUND_ID, ServiceContainer::getPlaygroundService()->getCurrentPlayground()->getId());

        return $criteria;
    }

}
