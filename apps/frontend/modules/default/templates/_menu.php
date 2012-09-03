<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <?php if($userIsAuthenticated){?>
                <?php if(count($mainLinks)> 0){?>
                    <ul class="nav nav-pills visible-desktop">
                    <?php foreach($mainLinks as $link){?>
                        <li class=" <?php if($activeLink == $link ) echo 'active' ?>">
                            <?php echo link_to(__($link . ' admin'), '@' . $link) ?>
                        </li>
                    <?php }?>
                    </ul>
                <?php }?>
                <?php if(count($adminLinks)> 0){?>
                    <ul class="nav nav-pills visible-desktop">
                        <li class="dropdown <?php if($activeLinkIsAdministrationLink ) echo 'active' ?>" id="administration_menu">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#administration_menu"  >
                                <?php echo __('Administration')?>
                                    <?php if($activeLinkIsAdministrationLink){?>
                                        (<?php echo __('of '.$activeLink)?>)
                                     <?php }?>
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <?php foreach ($adminLinks as $link) { ?>
                                    <li class="<?php if ($link == $activeLink) echo 'active';?>">
                                        <?php echo link_to(__($link . ' admin'), '@' . $link) ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    </ul>
                <?php }?>
                <ul class="nav pull-right">
                    <li class="me dropdown <?php if($activeLink  == 'account') echo 'active' ?>" id="user_menu">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#user_menu">
                            <?php echo $username?>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="<?php if($activeLink  == 'account') echo 'active' ?>"><?php echo link_to(__('Account'), '@account')?></li>
                            <li class="logout"><?php echo link_to(__('Logout'), '@logout')?></li>
                        </ul>
                    </li>
                </ul>
            <?php }?>
        </div>
    </div>
</div>
        <?php if (has_slot('submenu')){ ?>
            <?php include_slot('submenu') ?>
        <?php } ?>
<script type="text/javascript">
    $('.dropdown-toggle').dropdown();
</script>