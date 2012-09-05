<?php $closingSettlementForm = $form['closing_settlement']?>
<?php echo $closingSettlementForm->renderHiddenFields()?>
<?php foreach($closingSettlementForm as $key=>$field){ ?>
    <?php if(!$field->isHidden()){ ?>
        <?php $formName = $closingSettlementForm->getName()?>
        <?php $renderOptions = array()?>
        <?php $error = $field->hasError() ?  $closingSettlementForm[$key]->getError() : null?>
        <?php if($error){?>
            <?php $renderOptions['rel']="tooltip"?>
            <?php $renderOptions['title']= __($error->getMessageFormat(), $error->getArguments()) ?>
        <?php }?>
        <div class="control-group <?php echo $error ? 'error' : ''?> <?php echo $key; ?>">
                <?php echo $closingSettlementForm[$key]->renderLabel(null, array('class'=>'control-label '.($key == 'calculate_first_date' ? ' text-red ' : '')))?>
            <div class="controls">
                <?php echo $closingSettlementForm[$key]->render($renderOptions)?>
                <i><?php echo $closingSettlementForm[$key]->renderHelp();?></i>
            </div>
        </div>
    <?php }?>
<?php }?>