<div class="row">
    <div class="span3 offset4">
        <div class="span-login-form well">
            <form action="<?php echo url_for('@login') ?>" method="post" class="no-bottom-margin form-login">
                <?php include_partial('default/form_field', array('form'=>$form, 'key'=>'email'))?>
                <?php $formName = $form->getName(); $key = 'password'?>
                <?php $field = $form[$key]?>
                <?php $error = $form[$key]->hasError() ?  $form[$key]->getError() : null?>
                <div class="control-group <?php echo $error ? 'error' : ''?> no-bottom-margin"
                    <?php $renderOptions = array(
                        'placeholder' => __($field->renderLabelName()),
                        'id' => $formName.'_'.$form[$key]->getName(),
                    )?>
                    <?php if($error){?>
                        <?php $renderOptions['rel']="tooltip"?>
                        <?php $renderOptions['title']=__($error)?>
                    <?php }?>
                 >
                    <?php echo $form[$key]->render($renderOptions)?>
                    <input type="submit" class="btn btn-primary btn-login" value="<?php echo __('Login') ?>"/>
                </div>
            </form>
            <?php echo link_to(__('forgotten_password', array('class' => 'btn  forgotten_password')), '@forgotten_password') ?>
        </div>
    </div>
</div>