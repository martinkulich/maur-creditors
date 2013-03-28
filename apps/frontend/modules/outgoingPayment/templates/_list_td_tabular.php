<td class="sf_admin_text sf_admin_list_td_bank_account ">
    <?php echo $outgoingPayment->getBankAccount() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_receiver_bank_account ">
    <?php echo $outgoingPayment->getReceiverBankAccount() ?>
</td>
<td class="sf_admin_date no-wrap-line sf_admin_list_td_text ">
    <?php echo $outgoingPayment->getCreditor()?>
</td>
<td class="sf_admin_date no-wrap-line sf_admin_list_td_date ">
    <?php echo false !== strtotime($outgoingPayment->getDate()) ? format_date($outgoingPayment->getDate(), "D") : '&nbsp;' ?>
</td>
<td class="sf_admin_text sf_admin_list_td_amount ">
    <?php echo my_format_currency($outgoingPayment->getAmount(), $outgoingPayment->getCurrencyCode()) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_amount ">
    <?php echo link_to(my_format_currency($usedAmount = $outgoingPayment->getAllocatedAmount(), $outgoingPayment->getCurrencyCode()), '@allocation_filter?modeless=1&allocation_filters[outgoing_payment_id]='.$outgoingPayment->getId()) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_amount ">
    <?php echo my_format_currency($outgoingPayment->getUnallocatedAmount(), $outgoingPayment->getCurrencyCode()) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_amount ">
    <?php echo my_format_currency($outgoingPayment->getRefundation(), $outgoingPayment->getCurrencyCode()) ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_cash ">
    <?php echo get_partial('outgoingPayment/list_field_boolean', array('value' => $outgoingPayment->getCash())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_note text-align-right">
        <?php echo $outgoingPayment->getNote() ? link_to('Poznámka', '@outgoing_payment_note?id='.$outgoingPayment->getId(), array('class'=>'modal_link')) : ''; ?>
</td>
