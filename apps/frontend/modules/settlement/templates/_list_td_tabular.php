<?php use_helper('Number') ?>
<td class="sf_admin_text sf_admin_list_td_contract ">
    <?php echo $settlement->getContract() ?>
</td>
<td class="sf_admin_date no-wrap-line sf_admin_list_td_date ">
    <?php echo false !== strtotime($settlement->getDate()) ? format_date($settlement->getDate(), "D") : '&nbsp;' ?>
</td>
<td class="sf_admin_text sf_admin_list_td_balance ">
    <?php echo format_currency($settlement->getBalance(), $settlement->getContract()->getCurrencyCode()) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_interest ">
    <?php echo format_currency($settlement->getInterest(), $settlement->getContract()->getCurrencyCode()) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_paid ">
    <?php echo format_currency($settlement->getPaid(), $settlement->getContract()->getCurrencyCode()) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_capitalized ">
    <?php echo format_currency($settlement->getCapitalized(), $settlement->getContract()->getCurrencyCode()) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_balance_reduction ">
    <?php echo format_currency($settlement->getBalanceReduction(), $settlement->getContract()->getCurrencyCode()) ?>
</td>

<td class="sf_admin_text sf_admin_list_td_unsettled ">
        <?php echo format_currency($settlement->getUnsettled(), $settlement->getContract()->getCurrencyCode()) ?>
</td>
