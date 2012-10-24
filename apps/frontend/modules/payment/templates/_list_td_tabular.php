<td class="sf_admin_text sf_admin_list_td_creditor ">
    <?php echo $payment->getCreditor() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_contract ">
    <?php echo $payment->getContract() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_payment_type ">
    <?php echo __($payment->getPaymentType()) ?>
</td>
<td class="sf_admin_date no-wrap-line sf_admin_list_td_date text-align-right">
    <?php echo false !== strtotime($payment->getDate()) ? format_date($payment->getDate(), "D") : '&nbsp;' ?>
</td>
<td class="sf_admin_text sf_admin_list_td_amount text-align-right">
    <?php echo my_format_currency($payment->getAmount(), $payment->getContract()->getCurrencyCode()) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_note text-align-right">
    <?php echo $payment->getNote() ? link_to('Poznámka', '@payment_note?id=' . $payment->getId(), array('class' => 'modal_link')) : ''; ?>
</td>

