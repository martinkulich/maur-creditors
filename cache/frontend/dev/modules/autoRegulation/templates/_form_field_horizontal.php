<?php $formName = $form->getName()?>
<?php $field = $form[$key]?>
<?php $error = $form[$key]->hasError() ?  $form[$key]->getError() : null?>
<?php $renderOptions = array()?>
<?php if($error){?>
    <?php $renderOptions['rel']="tooltip"?>
    <?php $renderOptions['title']= __($error->getMessageFormat(), $error->getArguments()) ?>
<?php }?>

<div class="control-group <?php echo $error ? 'error' : ''?> <?php echo $key ?>">
        <?php echo $form[$key]->renderLabel(null, array('class'=>'control-label'))?>
    <div class="controls">
        <?php echo $form[$key]->render($renderOptions)?>
    </div>
</div>