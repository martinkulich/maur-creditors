<td class="sf_admin_text sf_admin_list_td_creditor_fullname span1">
    <?php echo $unpaid->getCreditorFirstname() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_contract_name span1">
    <?php echo $unpaid->getContractName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_contract_unpaid span1">
    <?php echo my_format_currency($unpaid->getContractUnpaid(), 'CZK') ?>
</td>
