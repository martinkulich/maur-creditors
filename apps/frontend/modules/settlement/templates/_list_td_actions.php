<td class="list_object_actions no-wrap-line">
    <?php if(!$settlement->isSettlementType(SettlementPeer::END_OF_YEAR) && !$settlement->getContract()->getClosedAt()){ ?>
        <ul class="sf_admin_actions">
            <?php echo $helper->linkToEdit($settlement, array('class' => 'modal_link', 'params' => array(), 'class_suffix' => 'edit', 'label' => 'Edit',)) ?>
            <?php echo $helper->linkToDelete($settlement, array('params' => array(), 'confirm' => 'Are you sure?', 'class_suffix' => 'delete', 'label' => 'Delete',)) ?>
        </ul>
    <?php }?>
</td>
