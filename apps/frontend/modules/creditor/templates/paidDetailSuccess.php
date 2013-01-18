<?php use_helper('I18N', 'Date') ?>
    <div class="modal_content">
    <div class="modal-header" >
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo $creditor .' - '.__('paid') ?></h3>
    </div>
    <div class="modal-body">
        <?php include_component('default', 'flashes') ?>
        <table class="table table-bordered">
            <tr>
                <th class="text-align-center">
                    <?php echo __('Date')?>
                </th>
                <th class="text-align-center">
                    <?php echo __('Paid interests')?>
                </th>
                <th class="text-align-center">
                    <?php echo __('Balance reduction')?>
                </th>
            </tr>
        <?php foreach($outgoingPayments as $outgoingPayment){ ?>
            <tr>
                <td class="text-align-right">
                    <?php echo format_date($outgoingPayment->getDate(), 'D')?>
                </td>
                <td class="text-align-right">
                    <?php echo my_format_currency($outgoingPayment->getPaid(), $outgoingPayment->getCurrencyCode()) ?>
                </td>
                <td class="text-align-right">
                <?php echo my_format_currency($outgoingPayment->getBalanceReduction(), $outgoingPayment->getCurrencyCode()) ?>
            </td>
            </tr>
        <?php } ?>
        </table>
    </div>
</div>