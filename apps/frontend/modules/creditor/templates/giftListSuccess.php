<?php use_helper('I18N', 'Date') ?>
<div class="modal_content">
    <div class="modal-header" >
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo $creditor .' - '.__('Gifts') ?></h3>
    </div>
    <div class="modal-body">
        <?php include_component('default', 'flashes') ?>
        <table class="table table-bordered">
            <tr>
                <th class="text-align-right span2">
                    <?php echo __('Date')?>
                </th>
                <th class="text-align-left">
                    <?php echo __('Name') ?>
                </th>
                <th class="text-align-left">
                    <?php echo __('Note') ?>
                </th>
            </tr>
        <?php foreach($creditor->getOrderedGifts() as $gift){ ?>
            <tr>
                <td class="text-align-right span2">
                    <?php echo format_date($gift->getDate(), 'D')?>
                </td>
                <td class="text-align-left">
                    <?php echo $gift->getName() ?>
                </td>
                <td class="text-align-left">
                    <?php echo $gift->getNote() ?>
                </td>
            </tr>
        <?php } ?>
        </table>
    </div>
</div>