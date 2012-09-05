<?php $currencyCode = $settlement->getContract()->getCurrencyCode()?>
<?php use_helper('Number') ?>
<td class="sf_admin_text sf_admin_list_td_creditor no-wrap-line">
    <?php echo $settlement->getCreditor() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_contract no-wrap-line">
    <?php echo $settlement->getContract() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_contract_interest_rate ">
  <?php echo $settlement->getContractInterestRate() ?>
</td>
<td class="sf_admin_date no-wrap-line sf_admin_list_td_date text-align-right">
    <?php echo false !== strtotime($settlement->getDate()) ? format_date($settlement->getDate(), "D") : '&nbsp;' ?>
</td>
<td class="sf_admin_date no-wrap-line sf_admin_list_td_date_of_payment text-align-right">
    <?php echo false !== strtotime($settlement->getDateOfPayment()) ? format_date($settlement->getDateOfPayment(), "D") : '&nbsp;' ?>
</td>
<td class="sf_admin_text sf_admin_list_td_days_count text-align-right <?php echo $settlement->getCalculateFirstDate() ? ' text-red ' : '' ?>">
    <?php echo ServiceContainer::getContractService()->getDaysCount($settlement) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_balance text-align-right <?php echo $settlement->getManualBalance() ? ' text-red ' : '' ?>">
    <?php echo my_format_currency($settlement->getBalance(), $currencyCode) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_interest text-align-right <?php echo $settlement->getManualInterest() ? ' text-red ' : '' ?>">
    <?php echo my_format_currency($settlement->getInterest(), $currencyCode) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_paid text-align-right">
    <?php echo my_format_currency($settlement->getPaid(), $currencyCode) ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_cash ">
  <?php echo get_partial('settlement/list_field_boolean', array('value' => $settlement->getCash())) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_capitalized text-align-right">
    <?php echo my_format_currency($settlement->getCapitalized(), $currencyCode) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_balance_reduction text-align-right">
    <?php echo my_format_currency($settlement->getBalanceReduction(), $currencyCode) ?>
</td>

<td class="sf_admin_text sf_admin_list_td_unsettled text-align-right">
        <?php echo my_format_currency($settlement->getUnsettled(), $currencyCode) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_unsettled_cumulative text-align-right">
  <?php echo my_format_currency($settlement->getUnsettledCumulative(), $currencyCode) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_settlement_type text-align-right">
        <?php echo $sf_context->getI18n()->__($settlement->getSettlementType()); ?>
</td>

