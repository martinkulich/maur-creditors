<?php

class MyJQueryFormFilterDate extends sfWidgetFormFilterDate
{

    protected function configure($options = array(), $attributes = array())
    {

        parent::configure($options, $attributes);

        $this->setOption('from_date', new myJQueryDateWidget());
        $this->setOption('to_date', new myJQueryDateWidget());
        $this->setOption('filter_template', '%date_range%');
        $translateService = ServiceContainer::getTranslateService();
        $this->addOption('template', sprintf('%s %%from_date%% %s %%to_date%%', $translateService->__('From'), $translateService->__('To')));
    }
}
