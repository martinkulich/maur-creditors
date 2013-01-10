§<td class="sf_admin_text sf_admin_list_td_name no-wrap-line ">
    <?php echo link_to($contract->getName(), '@settlement_addFilter?filter[contract_id]='.$contract->getId()) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_creditor no-wrap-line">
    <?php echo $contract->getCreditor() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_amount text-align-right">
    <?php echo my_format_currency($contract->getAmount(), $contract->getCurrencyCode()) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_payments_amount text-align-right">
    <?php echo link_to(my_format_currency($contract->getPaymentsAmount(), $contract->getCurrencyCode()), '@payment_contract_filter?contract_id='.$contract->getId()) ?>
</td>

<td class="sf_admin_text sf_admin_list_td_interest_rate_as_string text-align-right">
    <?php echo $contract->getInterestRateAsString() ?>
</td>
</td>
<td class="sf_admin_boolean sf_admin_list_td_capitalize text-align-center ">
    <?php echo get_partial('contract/list_field_boolean', array('value' => $contract->getCapitalize())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_period_as_string ">
    <?php echo $contract->getPeriodAsString() ?>
</td>
<td class="sf_admin_date no-wrap-line sf_admin_list_td_created_at text-align-right">
    <?php echo false !== strtotime($contract->getCreatedAt()) ? format_date($contract->getCreatedAt(), "D") : '&nbsp;' ?>
</td>
<td class="sf_admin_date no-wrap-line sf_admin_list_td_activated_at text-align-right">
    <?php echo false !== strtotime($contract->getActivatedAt()) ? format_date($contract->getActivatedAt(), "D") : '&nbsp;' ?>
</td>
<td class="sf_admin_date no-wrap-line sf_admin_list_td_first_settlement_date text-align-right">
    <?php echo false !== strtotime($contract->getFirstSettlementDate()) ? format_date($contract->getFirstSettlementDate(), "D") : '&nbsp;' ?>
</td>
<td class="sf_admin_date no-wrap-line sf_admin_list_td_closed_at text-align-right">
    <?php echo false !== strtotime($contract->getClosedAt()) ? format_date($contract->getClosedAt(), "D") : '&nbsp;' ?>
</td>
<td class="sf_admin_text sf_admin_list_td_note text-align-right">
        <?php echo $contract->getNote() ? link_to('Poznámka', '@contract_note?id='.$contract->getId(), array('class'=>'modal_link')) : ''; ?>
</td>

