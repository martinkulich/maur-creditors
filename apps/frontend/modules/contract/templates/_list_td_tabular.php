<td class="sf_admin_text sf_admin_list_td_name ">
    <?php echo link_to($contract->getName(), '@settlement_contract_filter?contract_id='.$contract->getId()) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_creditor ">
    <?php echo $contract->getCreditor() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_amount ">
    <?php echo format_currency($contract->getAmount(), 'CZK') ?>
</td>
<td class="sf_admin_text sf_admin_list_td_payments_amount ">
    <?php echo link_to(format_currency($contract->getPaymentsAmount(), 'CZK'), '@payment_contract_filter?contract_id='.$contract->getId()) ?>
</td>

<td class="sf_admin_text sf_admin_list_td_interest_rate_as_string ">
    <?php echo $contract->getInterestRateAsString() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_period_as_string ">
    <?php echo $contract->getPeriodAsString() ?>
</td>
<td class="sf_admin_date no-wrap-line sf_admin_list_td_created_at ">
    <?php echo false !== strtotime($contract->getCreatedAt()) ? format_date($contract->getCreatedAt(), "D") : '&nbsp;' ?>
</td>
<td class="sf_admin_date no-wrap-line sf_admin_list_td_activated_at ">
    <?php echo false !== strtotime($contract->getActivatedAt()) ? format_date($contract->getActivatedAt(), "D") : '&nbsp;' ?>
</td>
