<td class="list_object_actions no-wrap-line no-print">
    <ul class="sf_admin_actions">
        <?php echo $helper->linkToEdit($contract, array('class' => 'modal_link', 'params' => array(), 'class_suffix' => 'edit', 'label' => 'Edit',)) ?>
        <?php if (method_exists($helper, 'linkToCopy')): ?>
            <?php echo $helper->linkToCopy($contract, array('action' => 'copy', 'label' => 'Copy', 'params' => array(), 'class_suffix' => 'copy',)) ?>                <?php else: ?>
            <li class="sf_admin_action_copy">
                <?php echo link_to(__('Copy', array(), 'messages'), 'contract/copy?id=' . $contract->getId(), array()) ?>                    </li>
        <?php endif; ?>
        <?php if($contract->getDocument()){ ?>
            <?php echo $helper->linkToDownload($contract, array('action' => 'download', 'label' => 'Download', 'params' => array(), 'class_suffix' => 'download',)) ?>
        <?php } ?>
        <?php if(!$contract->getClosedAt()){ ?>
            <?php if (method_exists($helper, 'linkToClose')){ ?>
                <?php echo $helper->linkToClose($contract, array('action' => 'close', 'label' => 'Terminate', 'params' => array(), 'class_suffix' => 'close',)) ?>
            <?php }else{ ?>
                <li class="sf_admin_action_close">
                    <?php echo link_to(__('Terminate', array(), 'messages'), 'contract/close?id=' . $contract->getId(), array()) ?>                    </li>
            <?php } ?>
        <?php } ?>
        <?php echo $helper->linkToDelete($contract, array('params' => array(), 'confirm' => 'Are you sure?', 'class_suffix' => 'delete', 'label' => 'Delete',)) ?>
    </ul>
</td>
