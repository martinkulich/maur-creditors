<div class="row">
    <div class="span3 offset4">
        <div class="span-registration-form well container">
            <form action="<?php echo url_for('@forgotten_password')?>" method="post">
                <?php include_partial('default/form', array('form'=>$form))?>
                <input type="submit" class="btn btn-primary btn-registration pull-right" value="<?php echo __('Send password')?>" />
            </form>
        </div>
    </div>
</div>