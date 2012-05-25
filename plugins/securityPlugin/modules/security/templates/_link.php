<div id="login_form">
<?php if($sf_user->isAuthenticated()){?>
    <?php echo link_to(__('Logout'), 'security/logout')?>
<?php }else{?>
        <?php echo link_to_remote(__('Login'), array('update'=>'login_form', 'url'=>'security/showLoginForm','script'=>true))?>
        <?php //include_partial('security/login', array('form'=>new LoginForm()))?>
<?php }?>
</div>