        <td class="sf_admin_text sf_admin_list_td_creditor_firstname ">
  <?php echo $regulation->getCreditorFirstname() ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_creditor_lastname ">
  <?php echo $regulation->getCreditorLastname() ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_contract_id ">
  <?php echo $regulation->getContractId() ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_contract_name ">
  <?php echo $regulation->getContractName() ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_settlement_year ">
  <?php echo $regulation->getSettlementYear() ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_start_balance ">
  <?php echo $regulation->getStartBalance() ?>
</td>
        <td class="sf_admin_date no-wrap-line sf_admin_list_td_contract_activated_at ">
  <?php echo false !== strtotime($regulation->getContractActivatedAt()) ? format_date($regulation->getContractActivatedAt(), "f") : '&nbsp;' ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_contract_balance ">
  <?php echo $regulation->getContractBalance() ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_regulation ">
  <?php echo $regulation->getRequlation() ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_paid ">
  <?php echo $regulation->getPid() ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_paid_for_current_year ">
  <?php echo $regulation->getPaidForCurrentYear() ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_capitalized ">
  <?php echo $regulation->getCapitalized() ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_teoretically_to_pay_in_current_year ">
  <?php echo $regulation->getTeoreticallyToPayInCurrentYear() ?>
</td>
        <td class="sf_admin_text sf_admin_list_td_end_balance ">
  <?php echo $regulation->getEndBalance() ?>
</td>
