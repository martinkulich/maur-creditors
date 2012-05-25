<?php $formName = $form->getName()?>
<?php echo $form->renderHiddenFields()?>
<?php  foreach($form as $key=>$field){ ?>
    <?php if(!$field->isHidden()){?>
        <?php include_partial('default/form_field', array('form'=>$form, 'key'=>$key))?>
    <?php }?>
<?php }?>