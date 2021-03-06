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
        $this->object->setActivatedAt(null);
        $this->object->setClosedAt(null);
        $this->object->save($con);
        $this->object->reload();
        ServiceContainer::getContractService()->checkContractChanges($this->object);
    }
}