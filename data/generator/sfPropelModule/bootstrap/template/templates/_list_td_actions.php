<td class="list_object_actions no-wrap-line no-print">
    <ul class="sf_admin_actions">
        <?php foreach ($this->configuration->getValue('list.object_actions') as $name => $params): ?>
            <?php if ('_delete' == $name): ?>
                <?php echo $this->addCredentialCondition('[?php echo $helper->linkToDelete($' . $this->getSingularName() . ', ' . $this->asPhp($params) . ') ?]', $params) ?>

            <?php elseif ('_edit' == $name): ?>
                <?php echo $this->addCredentialCondition('[?php echo $helper->linkToEdit($' . $this->getSingularName() . ', ' . $this->asPhp(array_merge(array('class'=>'modal_link'),$params)) . ') ?]', $params) ?>

            <?php else: ?>
                [?php if (method_exists($helper, 'linkTo<?php echo $method = ucfirst(sfInflector::camelize($name)) ?>')): ?]
                    <?php echo $this->addCredentialCondition('[?php echo $helper->linkTo'.$method.'($'.$this->getSingularName() . ', ' .$this->asPhp($params).') ?]', $params) ?>
                [?php else: ?]
                    <li class="sf_admin_action_<?php echo $params['class_suffix'] ?>">
                        <?php echo $this->addCredentialCondition($this->getLinkToAction($name, $params, true), $params) ?>
                    </li>
                [?php endif; ?]

            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</td>
