<div class="row row-margin-top">
    <div class="span3 offset4">
        <div class="span-registration-form well container">
            <form action="<?php echo url_for('@registration') ?>" method="post" class="registration-form">
                <?php include_partial('default/form', array('form'=>$form))?>
                <input type="submit" class="btn btn-success btn-registration pull-right" value="<?php echo __('Register')?>" />
            </form>
        </div>
    </div>
</div>