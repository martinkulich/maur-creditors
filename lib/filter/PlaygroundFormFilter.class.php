<?php

/**
 * Playground filter form.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
class PlaygroundFormFilter extends BasePlaygroundFormFilter
{

    public function configure()
    {

    }

    public function doBuildCriteria(array $values)
    {
        $criteria = parent::doBuildCriteria($values);
        $criteria->add(PlaygroundPeer::IS_PUBLIC, true);

        return $criteria;
    }
}
