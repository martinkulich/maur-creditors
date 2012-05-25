    <?php $userHasCredentialToAdminReservations = $sf_user->hasCredential('reservation.admin');?>
    <div class="modal-body">
        <?php include_component('default', 'flashes') ?>
        <?php echo $form->renderHiddenFields(); ?>
            <div>
            <?php $renderOptions = array('class' => 'span1') ?>
            <?php $error = $form['from']->hasError() ? $form['from']->getError() : null ?>
            <?php if ($error) { ?>
                <?php die(var_dump($error));?>
                <?php $renderOptions['rel'] = "tooltip" ?>
                <?php $renderOptions['title'] = __($error) ?>
            <?php } ?>

            <div class="control-group <?php echo $error ? 'error' : '' ?> ">
                <div class="control-label">
                    <?php echo __('Time') ?>
                </div>
                <div class="controls reservation">
                    <?php echo $form['from']['time_zone_id']->render($renderOptions) ?>
                    <?php echo $form['to']['time_zone_id']->render($renderOptions) ?>
                </div>
            </div>
            <?php foreach ($form as $fieldKey => $field) { ?>
                <?php if (!$field->isHidden() && !($field instanceof sfFormFieldSchema) && $fieldKey != 'repeat') { ?>
                    <?php include_partial('default/form_field_horizontal', array('form' => $form, 'key' => $fieldKey)) ?>
                <?php } ?>

                <?php if ($field instanceof sfFormFieldSchema && $fieldKey == 'reservation_repeating') { ?>
                    <?php include_partial('default/form_field_horizontal', array('form' => $form, 'key' => 'repeat')) ?>
                    <div>
                        <div id="reservation_repeating_collapse" class="<?php if(!$form['repeat']->getValue()) echo 'hide'?>">
                            <?php foreach ($field as $subFieldKey => $subField) { ?>
                                <?php if (!$subField->isHidden() && !($subField instanceof sfFormFieldSchema)) { ?>
                                    <?php include_partial('default/form_field_horizontal', array('form' => $form[$fieldKey], 'key' => $subFieldKey)) ?>

                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
            <i class="icon-ok icon-white"></i>
            <?php echo ' ' . __('Save') ?>
        </button>
        <?php include_partial('reservation/reservationActions', array('reservation' => $reservation)) ?>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function(){
        reservationRepeat();

//        $('#reservation_repeating_collapse').on('hide', function (event) {
//            event.stopPropagation();
//        });
//        $('#reservation_repeating_collapse').collapse('hide');

    });

    function reservationRepeat()
    {
        if($('#reservation_repeat').length > 0)
        {
            var checked = jQuery('#reservation_repeat').is(':checked');

            if(checked == true)
            {
                $('#reservation_repeating_collapse').removeClass('hide');
                $('#reservation_repeating_collapse').show();
//                $('#reservation_repeating_collapse').collapse('show');

            }else{
//                $('#reservation_repeating_collapse').collapse('hide');
                 $('#reservation_repeating_collapse').hide();
            }
        }
    }
</script>

