<div class="row">
    <div class="span3 offset4">
        <div class="span-login-form well">
            <form action="<?php echo url_for('@account') ?>" method="post">
                <?php include_partial('default/form', array('form' => $form)) ?>
                <input type="submit" class="btn btn-primary btn-account pull-right" value="<?php echo __('Update') ?>" />
            </form>
        </div>
    </div>
</div>