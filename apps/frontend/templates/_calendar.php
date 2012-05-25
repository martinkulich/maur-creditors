<div class="calendar">
<form method="get">
    <?php foreach($form as $field){?>
        <?php echo $field->render();?>
    <?php }?>
</form>
</div>