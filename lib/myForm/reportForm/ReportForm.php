<?php

class ReportForm extends BaseForm
{

    public function configure()
    {
        $this->disableCSRFProtection();
        sfProjectConfiguration::getActive()->loadHelpers('Url');



        $this->setWidgets(array(
            'date_from' => new myJQueryDateWidget(),
            'date_to' => new myJQueryDateWidget(),
            'user_string' => new UserJQueryAutocompleter(
                array(
                    'url' => url_for('@autocomplete_user', true),
                    'value_callback' => array('SecurityUserPeer', 'retrieveDetailsById'),
                    'target' => 'report[user_id]',
                )
            ),
            'user_id' => new sfWidgetFormInputHidden(),
            'sport_id' => new sfWidgetFormPropelChoice(array('model' => 'Sport', 'order_by' => array('Name', 'asc'), 'add_empty' => true)),
            'price_category_id' => new sfWidgetFormPropelChoice(array('model' => 'PriceCategory', 'order_by' => array('Name', 'asc'), 'add_empty' => true)),
            'paid' =>new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
        ));
        $this->getWidget('user_string')->setLabel('User');
        $this->setValidators(array(
            'date_from' => new myValidatorDate(array('required' => false)),
            'date_to' => new myValidatorDate(array('required' => false)),
            'user_string' => new sfValidatorString(array('required' => false)),
            'user_id' => new sfValidatorPropelChoice(array('model' => 'SecurityUser', 'column' => 'id', 'required' => false)),
            'sport_id' => new sfValidatorPropelChoice(array('model' => 'Sport', 'required' => false)),
            'price_category_id' => new sfValidatorPropelChoice(array('model' => 'PriceCategory', 'required' => false)),
            'paid' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
        ));

        $sportCriteria = $this->getSportCriteria();
        $sportsCount = SportPeer::doCount($sportCriteria);
        if ($sportsCount > 1) {
            $this->getWidget('sport_id')->setOption('criteria', $sportCriteria);
            $this->getValidator('sport_id')->setOption('criteria', $sportCriteria);
        } else {
            $this->unsetField('sport_id');
        }

        $priceCategoryCriteria = new Criteria();
        $priceCategoryCriteria->addAscendingOrderByColumn(PriceCategoryPeer::NAME);
        $priceCategoriesCount = PriceCategoryPeer::doCount($priceCategoryCriteria);
        if ($priceCategoriesCount > 1) {
            $this->getWidget('price_category_id')->setOption('criteria', $priceCategoryCriteria);
            $this->getValidator('price_category_id')->setOption('criteria', $priceCategoryCriteria);
        } else {
            $this->unsetField('price_category_id');
        }


        $usedFields = array();
        foreach ($this->getWidgetSchema() as $field => $widget) {
            if (!in_array($field, $usedFields)) {
                $this->unsetField($field);
            }
        }
        $this->widgetSchema->setNameFormat('report[%s]');
    }

    public function getName()
    {
        return 'report';
    }

    public function getDefaults()
    {
        return array(
            'date_from' => new DateTime('now'),
        );
    }

    public function getUsedFields()
    {
        $usedFields = array();
        foreach ($this->getWidgetSchema()->getFields() as $field => $widget) {
            $usedFields[] = $field;
        }

        return $usedFields;
    }
}
