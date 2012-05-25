<?php foreach($messages as $messageType=>$messageGroup){ ?>
    <?php foreach($messageGroup as $message){ ?>
        <div class="alert alert-<?php echo $messageType?>" >
            <a class="close" data-dismiss="alert">×</a>
            <?php echo __($message); ?>
        </div>
    <?php }?>
<?php }?>
