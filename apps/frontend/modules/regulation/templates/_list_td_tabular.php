<?php $currencyCode = $regulation->getContract()->getCurrencyCode(); ?>
<?php $defaultCurrencyCode = $currency->getCode();?>
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
    <?php echo my_format_converted_currency($regulation->getStartBalance(), $currencyCode, $defaultCurrencyCode) ?>
</td>
<td class="sf_admin_date no-wrap-line sf_admin_list_td_contract_activated_at text-align-right">
    <?php echo false !== strtotime($regulation->getContractActivatedAt()) ? format_date($regulation->getContractActivatedAt(), "D") : '&nbsp;' ?>
</td>
<td class="sf_admin_text sf_admin_list_td_contract_balance text-align-right <?php if ($regulation->hasManualBalance()) echo ' text-red ' ?>">
<?php echo my_format_converted_currency($regulation->getContractBalance(), $currencyCode, $defaultCurrencyCode) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_unpaid_in_past text-align-right">
    <?php echo my_format_converted_currency($regulation->getUnpaidInPast(), $currencyCode, $defaultCurrencyCode) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_regulation text-align-center <?php if ($regulation->hasManualInterest()) echo ' text-red ' ?>">
    <?php echo my_format_converted_currency($regulation->getRequlation(), $currencyCode, $defaultCurrencyCode) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_paid text-align-right">
    <?php echo my_format_converted_currency($regulation->getPaid(), $currencyCode, $defaultCurrencyCode) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_paid_for_current_year text-align-right">
    <?php echo my_format_converted_currency($regulation->getPaidForCurrentYear(), $currencyCode, $defaultCurrencyCode) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_capitalized text-align-right">
    <?php echo my_format_converted_currency($regulation->getCapitalized(), $currencyCode, $defaultCurrencyCode) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_unpaid text-align-right">
    <?php echo my_format_converted_currency($regulation->getUnpaid(), $currencyCode, $defaultCurrencyCode) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_end_balance text-align-right">
<?php echo my_format_converted_currency($regulation->getEndBalance(), $currencyCode, $defaultCurrencyCode) ?>
</td>
