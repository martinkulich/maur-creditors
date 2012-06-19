<td class="sf_admin_text sf_admin_list_td_creditor ">
    <?php echo $payment->getCreditor() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_contract ">
  <?php echo $payment->getContract() ?>
</td>
        <td class="sf_admin_date no-wrap-line sf_admin_list_td_date text-align-right">
  <?php echo false !== strtotime($payment->getDate()) ? format_date($payment->getDate(), "D") : '&nbsp;' ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_amount text-align-right ">
  <?php echo my_format_currency($payment->getAmount(), $payment->getContract()->getCurrencyCode()) ?>
</td>
