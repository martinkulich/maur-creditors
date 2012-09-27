<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_creditor_fullname">
  <?php if ('creditor_fullname' == $sort[0]): ?>
    <?php echo link_to(__('Creditor fullname', array(), 'messages'), '@regulation', array('query_string' => 'sort=creditor_fullname&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo image_tag(sfConfig::get('root_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'sf_admin'), 'title' => __($sort[1], array(), 'sf_admin'), 'class'=>'admin_list_sort_img')) ?>
  <?php else: ?>
    <?php echo link_to(__('Creditor fullname', array(), 'messages'), '@regulation', array('query_string' => 'sort=creditor_fullname&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_contract_name">
  <?php if ('contract_name' == $sort[0]): ?>
    <?php echo link_to(__('Contract name', array(), 'messages'), '@regulation', array('query_string' => 'sort=contract_name&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo image_tag(sfConfig::get('root_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'sf_admin'), 'title' => __($sort[1], array(), 'sf_admin'), 'class'=>'admin_list_sort_img')) ?>
  <?php else: ?>
    <?php echo link_to(__('Contract name', array(), 'messages'), '@regulation', array('query_string' => 'sort=contract_name&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_foreignkey sf_admin_list_th_regulation_year">
  <?php if ('regulation_year' == $sort[0]): ?>
    <?php echo link_to(__('Year', array(), 'messages'), '@regulation', array('query_string' => 'sort=regulation_year&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo image_tag(sfConfig::get('root_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'sf_admin'), 'title' => __($sort[1], array(), 'sf_admin'), 'class'=>'admin_list_sort_img')) ?>
  <?php else: ?>
    <?php echo link_to(__('Year', array(), 'messages'), '@regulation', array('query_string' => 'sort=regulation_year&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_start_balance">
  <?php if ('start_balance' == $sort[0]): ?>
    <?php echo link_to(__('Start balance', array(), 'messages'), '@regulation', array('query_string' => 'sort=start_balance&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo image_tag(sfConfig::get('root_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'sf_admin'), 'title' => __($sort[1], array(), 'sf_admin'), 'class'=>'admin_list_sort_img')) ?>
  <?php else: ?>
    <?php echo link_to(__('Start balance', array(), 'messages'), '@regulation', array('query_string' => 'sort=start_balance&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_date sf_admin_list_th_contract_activated_at">
  <?php if ('contract_activated_at' == $sort[0]): ?>
    <?php echo link_to(__('Contract activated at', array(), 'messages'), '@regulation', array('query_string' => 'sort=contract_activated_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo image_tag(sfConfig::get('root_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'sf_admin'), 'title' => __($sort[1], array(), 'sf_admin'), 'class'=>'admin_list_sort_img')) ?>
  <?php else: ?>
    <?php echo link_to(__('Contract activated at', array(), 'messages'), '@regulation', array('query_string' => 'sort=contract_activated_at&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_contract_balance">
  <?php if ('contract_balance' == $sort[0]): ?>
    <?php echo link_to(__('Contract balance', array(), 'messages'), '@regulation', array('query_string' => 'sort=contract_balance&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo image_tag(sfConfig::get('root_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'sf_admin'), 'title' => __($sort[1], array(), 'sf_admin'), 'class'=>'admin_list_sort_img')) ?>
  <?php else: ?>
    <?php echo link_to(__('Contract balance', array(), 'messages'), '@regulation', array('query_string' => 'sort=contract_balance&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_upaid_in_past span2">
  <?php echo __('Upaid in past', array(), 'messages') ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_regulation">
  <?php if ('regulation' == $sort[0]): ?>
    <?php echo link_to(__('Regulation', array(), 'messages'), '@regulation', array('query_string' => 'sort=regulation&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo image_tag(sfConfig::get('root_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'sf_admin'), 'title' => __($sort[1], array(), 'sf_admin'), 'class'=>'admin_list_sort_img')) ?>
  <?php else: ?>
    <?php echo link_to(__('Regulation', array(), 'messages'), '@regulation', array('query_string' => 'sort=regulation&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_paid">
  <?php if ('paid' == $sort[0]): ?>
    <?php echo link_to(__('Paid', array(), 'messages'), '@regulation', array('query_string' => 'sort=paid&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo image_tag(sfConfig::get('root_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'sf_admin'), 'title' => __($sort[1], array(), 'sf_admin'), 'class'=>'admin_list_sort_img')) ?>
  <?php else: ?>
    <?php echo link_to(__('Paid', array(), 'messages'), '@regulation', array('query_string' => 'sort=paid&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_paid_for_current_year span2">
  <?php if ('paid_for_current_year' == $sort[0]): ?>
    <?php echo link_to(__('Paid for current year', array(), 'messages'), '@regulation', array('query_string' => 'sort=paid_for_current_year&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo image_tag(sfConfig::get('root_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'sf_admin'), 'title' => __($sort[1], array(), 'sf_admin'), 'class'=>'admin_list_sort_img')) ?>
  <?php else: ?>
    <?php echo link_to(__('Paid for current year', array(), 'messages'), '@regulation', array('query_string' => 'sort=paid_for_current_year&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_capitalized">
  <?php if ('capitalized' == $sort[0]): ?>
    <?php echo link_to(__('Capitalized', array(), 'messages'), '@regulation', array('query_string' => 'sort=capitalized&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo image_tag(sfConfig::get('root_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'sf_admin'), 'title' => __($sort[1], array(), 'sf_admin'), 'class'=>'admin_list_sort_img')) ?>
  <?php else: ?>
    <?php echo link_to(__('Capitalized', array(), 'messages'), '@regulation', array('query_string' => 'sort=capitalized&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_unpaid">
  <?php if ('unpaid' == $sort[0]): ?>
    <?php echo link_to(__('Unpaid', array(), 'messages'), '@regulation', array('query_string' => 'sort=unpaid&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo image_tag(sfConfig::get('root_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'sf_admin'), 'title' => __($sort[1], array(), 'sf_admin'), 'class'=>'admin_list_sort_img')) ?>
  <?php else: ?>
    <?php echo link_to(__('Unpaid', array(), 'messages'), '@regulation', array('query_string' => 'sort=unpaid&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_end_balance">
  <?php if ('end_balance' == $sort[0]): ?>
    <?php echo link_to(__('End balance', array(), 'messages'), '@regulation', array('query_string' => 'sort=end_balance&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo image_tag(sfConfig::get('root_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'sf_admin'), 'title' => __($sort[1], array(), 'sf_admin'), 'class'=>'admin_list_sort_img')) ?>
  <?php else: ?>
    <?php echo link_to(__('End balance', array(), 'messages'), '@regulation', array('query_string' => 'sort=end_balance&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>