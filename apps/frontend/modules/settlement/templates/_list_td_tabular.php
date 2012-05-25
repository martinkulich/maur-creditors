<?php use_helper('Number') ?>
<td class="sf_admin_text sf_admin_list_td_contract ">
    <?php echo $settlement->getContract() ?>
</td>
<td class="sf_admin_date no-wrap-line sf_admin_list_td_date ">
    <?php echo false !== strtotime($settlement->getDate()) ? format_date($settlement->getDate(), "D") : '&nbsp;' ?>
</td>
<td class="sf_admin_text sf_admin_list_td_balance ">
    <?php echo format_currency($settlement->getBalance(), 'CZK') ?>
</td>
<td class="sf_admin_text sf_admin_list_td_interest ">
    <?php echo format_currency($settlement->getInterest(), 'CZK') ?>
</td>
<td class="sf_admin_text sf_admin_list_td_paid ">
    <?php echo format_currency($settlement->getPaid(), 'CZK') ?>
</td>
<td class="sf_admin_text sf_admin_list_td_capitalized ">
    <?php echo format_currency($settlement->getCapitalized(), 'CZK') ?>
</td>
<td class="sf_admin_text sf_admin_list_td_balance_reduction ">
    <?php echo format_currency($settlement->getBalanceReduction(), 'CZK') ?>
</td>

<td class="sf_admin_text sf_admin_list_td_unsettled ">
        <?php echo format_currency($settlement->getUnsettled(), 'CZK') ?>
</td>
