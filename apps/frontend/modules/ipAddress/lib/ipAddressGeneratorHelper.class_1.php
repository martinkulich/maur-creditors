<?php

abstract class AbstractCustomField
{
    protected $id;
    protected $entityId;
    protected $customFieldEntity; #cizi klic
    protected $value;

}

class CarCustomField extends AbstractCustomField
{

}

class CustomField
{
    protected $id;
    protected $name;
    protected $type;
    protected $customFieldGroup;
    protected $position;

}

class CustomFieldGroup
{
    protected $id;
    protected $name;
    protected $customFieldEntity;
    protected $position;

}

class CustomFieldEntity
{
    protected $id;
    protected $className;

}