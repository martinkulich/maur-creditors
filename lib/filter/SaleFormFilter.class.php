<?php

/**
 * Sale filter form.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
class SaleFormFilter extends BaseSaleFormFilter
{

    public function configure()
    {
        $fieldsToUnset = array(
            'playground_id',
            'schedule_sale_list',
        );

        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
        }

        $plygroundId = ServiceContainer::getPlaygroundService()->getCurrentPlayground()->getId();

        $salePriceCategoryCriteria = new Criteria();
        $salePriceCategoryCriteria->add(PriceCategoryPeer::PLAYGROUND_ID, $plygroundId);
        $salePriceCategoryCriteria->addAscendingOrderByColumn(PriceCategoryPeer::NAME);
        $salePriceCategoryWidget = $this->getWidget('sale_price_category_list');
        $salePriceCategoryWidget->setOption('criteria', $salePriceCategoryCriteria);
        $salePriceCategoryWidget->setLabel('Price category');
        $salePriceCategoryValidator = $this->getValidator('sale_price_category_list');
        $salePriceCategoryValidator->setOption('criteria', $salePriceCategoryCriteria);
//        $salePriceCategoryValidator->setOption('required', true);



        $salePriceCriteria = new Criteria();
        $salePriceCriteria->add(PricePeer::PLAYGROUND_ID, $plygroundId);
        $salePriceCriteria->addAscendingOrderByColumn(PricePeer::NAME);
        $salePriceWidget = $this->getWidget('sale_price_list');
        $salePriceWidget->setOption('criteria', $salePriceCriteria);
        $salePriceWidget->setLabel('Price');
        $salePriceValidator = $this->getValidator('sale_price_list');
        $salePriceValidator->setOption('criteria', $salePriceCriteria);
//        $salePriceValidator->setOption('required', true);

        $this->getWidget('curt_count')->setAttribute('class', 'span1');

    }
}
