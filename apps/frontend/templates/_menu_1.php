<?php $currentPlaygroundModules = ServiceContainer::getPlaygroundService()->getCurrentPlaygroundModules();?>
<?php $route = $sf_context->getInstance()->getRouting()->getCurrentRouteName()?>
<?php $links = array_merge(array_keys($currentPlaygroundModules), array('curt', 'price', 'price_category','schedule', 'rights', 'user', 'group', 'mail'));?>
<?php $unsecureLinks = array('reservation', 'tournament', 'training','event_group')?>
<?php $userIsAuthenticated = $sf_user->isAuthenticated() ?>
<ul class="main_menu">
    <?php foreach($links as $link)    {?>
        <?php if(($userIsAuthenticated && ($sf_user->hasCredential($link.'.admin')) ) || in_array($link, $unsecureLinks)) {?>
            <li class="<?php if(in_array($route, array($link, $link.'_new', $link.'_create', $link.'_edit', $link.'_delete', $link.'_schedule', $link.'_update', $link.'_sport', $link.'_save', $link.'_prices', $link.'_detail', $link.'_results', $link.'_ratings'))){echo 'selected';}?> "><?php echo link_to(__($link.' admin'), '@'.$link)?></li>
        <?php } ?>
    <?php }?>
<!--    <li><?php //echo link_to(__('Account'), '@member_edit?id='.$sf_user->getId());?></li>-->
<?php if($userIsAuthenticated){ ?>
    <li><?php echo link_to(__('Account'), '@account')?></li>
    <li class="logout"><?php echo link_to(__('Logout'), '@logout')?></li>
<?php }else{?>
    <li><?php echo link_to(__('Login'), '@login')?></li>
    <li><?php echo link_to(__('registration'), '@registration') ?></li>
<?php }?>
</ul>
