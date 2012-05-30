<?php

class ContractCopyForm extends ContractForm
{

    public function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);
    }

    public function doSave($con = null)
    {
        $this->object = $this->getObject()->copy();
        $this->updateObject();
        $this->object->setId(null);
        $this->object->save($con);

        ServiceContainer::getContractService()->updateContractSettlements($this->object);
    }
}