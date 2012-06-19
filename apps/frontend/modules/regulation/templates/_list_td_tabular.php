<?php $currencyCode = 'CZK';?>
<td class="sf_admin_text sf_admin_list_td_creditor_fullname ">
  <?php echo $regulation->getCreditorFirstname() ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_contract_name ">
  <?php echo $regulation->getContractName() ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_regulation_year ">
  <?php echo $regulation->getRegulationYear() ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_start_balance text-align-right">
  <?php echo my_format_currency($regulation->getStartBalance(), $currencyCode) ?>
</td>
        <td class="sf_admin_date no-wrap-line sf_admin_list_td_contract_activated_at text-align-right">
  <?php echo false !== strtotime($regulation->getContractActivatedAt()) ? format_date($regulation->getContractActivatedAt(), "D") : '&nbsp;' ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_contract_balance text-align-right">
  <?php echo my_format_currency($regulation->getContractBalance(), $currencyCode) ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_regulation text-align-center">
  <?php echo my_format_currency($regulation->getRequlation(), $currencyCode) ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_paid text-align-right">
  <?php echo my_format_currency($regulation->getPaid(), $currencyCode) ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_paid_for_current_year text-align-right">
  <?php echo my_format_currency($regulation->getPaidForCurrentYear(), $currencyCode) ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_capitalized text-align-right">
  <?php echo my_format_currency($regulation->getCapitalized(), $currencyCode) ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_teoretically_to_pay_in_current_year text-align-right">
  <?php echo my_format_currency($regulation->getTeoreticallyToPayInCurrentYear(), $currencyCode) ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_unpaid text-align-right">
  <?php echo my_format_currency($regulation->getUnpaid(), $currencyCode) ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_end_balance text-align-right">
  <?php echo my_format_currency($regulation->getEndBalance(), $currencyCode) ?>
</td>
