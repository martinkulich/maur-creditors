<?php use_helper('I18N', 'Date') ?>
<div class="modal_content">
    <div class="modal-header" >
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo $outgoinPayment ?></h3>
    </div>

    <div class="modal-body">
        <?php include_component('default', 'flashes') ?>
        <table class="table table-bordered">
            <tr>
                <th>
                    <?php echo __('Creditor') ?>
                </th>
                <td>
                    <?php echo $outgoinPayment->getCreditor() ?>
                </td>
            </tr>
            <tr>
                <th>
                    <?php echo __('Bank account'); ?>
                </th>
                <td>
                    <?php echo $outgoinPayment->getBankAccount() ?>
                </td>
            </tr>
        </table>
    </div>
</div>