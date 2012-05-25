<?php $formName = $form->getName()?>
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
</div>