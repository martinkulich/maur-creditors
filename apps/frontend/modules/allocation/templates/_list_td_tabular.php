        <td class="sf_admin_text sf_admin_list_td_creditor ">
  <?php echo $allocation->getCreditor() ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_contract ">
  <?php echo $allocation->getContract() ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_settlement ">
  <?php echo $allocation->getSettlement() ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_outgoing_payment ">
  <?php echo $allocation->getOutgoingPayment() ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_paid ">
  <?php echo my_format_currency($allocation->getPaid(), $allocation->getContract()->getCurrencyCode()) ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_balance_reduction ">
  <?php echo my_format_currency($allocation->getBalanceReduction(), $allocation->getContract()->getCurrencyCode()) ?>
</td>
