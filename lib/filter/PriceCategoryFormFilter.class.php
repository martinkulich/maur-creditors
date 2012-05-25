<?php

/**
 * PriceCategory filter form.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
class PriceCategoryFormFilter extends BasePriceCategoryFormFilter
{

    public function configure()
    {
        $fieldsToUnset = array(
            'playground_id',
            'color',
            'price_amount_list',
            'price_category_security_group_list',
            'price_category_user_list',
            'sale_price_category_list',
        );

        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
        }
    }

    public function doBuildCriteria(array $values)
    {
        $criteria = parent::doBuildCriteria($values);
        $criteria->add(PriceCategoryPeer::PLAYGROUND_ID, ServiceContainer::getPlaygroundService()->getCurrentPlayground()->getId());

        return $criteria;
    }

}
