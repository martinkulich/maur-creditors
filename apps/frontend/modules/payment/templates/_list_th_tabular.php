<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_creditor">
  <?php echo __('Creditor', array(), 'messages') ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_contract">
  <?php echo __('Contract', array(), 'messages') ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_payment_type">
  <?php echo __('Payment type', array(), 'messages') ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_date sf_admin_list_th_date span1">
  <?php if ('date' == $sort[0]): ?>
    <?php echo link_to(__('Date', array(), 'messages'), '@payment', array('query_string' => 'sort=date&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo image_tag(sfConfig::get('root_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'sf_admin'), 'title' => __($sort[1], array(), 'sf_admin'), 'class'=>'admin_list_sort_img')) ?>
  <?php else: ?>
    <?php echo link_to(__('Date', array(), 'messages'), '@payment', array('query_string' => 'sort=date&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_amount span1">
  <?php if ('amount' == $sort[0]): ?>
    <?php echo link_to(__('Amount', array(), 'messages'), '@payment', array('query_string' => 'sort=amount&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo image_tag(sfConfig::get('root_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'sf_admin'), 'title' => __($sort[1], array(), 'sf_admin'), 'class'=>'admin_list_sort_img')) ?>
  <?php else: ?>
    <?php echo link_to(__('Amount', array(), 'messages'), '@payment', array('query_string' => 'sort=amount&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_note span1">
  <?php if ('note' == $sort[0]): ?>
    <?php echo link_to(__('Note', array(), 'messages'), '@payment', array('query_string' => 'sort=note&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo image_tag(sfConfig::get('root_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'sf_admin'), 'title' => __($sort[1], array(), 'sf_admin'), 'class'=>'admin_list_sort_img')) ?>
  <?php else: ?>
    <?php echo link_to(__('Note', array(), 'messages'), '@payment', array('query_string' => 'sort=note&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>