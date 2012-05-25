<?php use_javascript('rights')?>
<?php if($roleForm->hasErrors()){?>
    <?php echo $roleForm->renderGlobalErrors();?>
<?php }?>
<table class="table table-admin table-rights">
    <thead>
    <tr>
        <th colspan="2"></th>
        <?php foreach($roles as $role){?>
            <th class="role well no-wrap-line">
                <?php echo $role->getName();?>
            </th>
        <?php }?>
        <?php foreach($perms as $perm){?>
            <th class="perm  no-wrap-line">
                <?php echo (__($perm->getName()));?>
            </th>
        <?php }?>
    </tr>
    </thead>
    <tbody>
    <tr>
        <th class="roles well" colspan="<?php echo count($perms)+count($roles)+2?>">
            <?php echo __('Roles')?>
        </th>
    </tr>
    <?php foreach($roles as $role){?>
        <tr>
            <td class="delete_role">
                <?php echo link_to('<i class="icon-trash icon-white"></i>', '@delete_role?id='.$role->getId(), array('title'=>__('Delete role'), 'class'=>'btn btn-mini btn-danger'));?>
            </td>
            <td>
                <?php echo $role->getName();?>
            </td>
            <?php for($i = 1; $i<=count($roles); $i++){?>
                <td  class="well"></td>
            <?php }?>
        <?php foreach($perms as $perm){?>
            <td class="role_perm text-align-center">
                <?php if(isset($rolePerms[$role->getId()][$perm->getId()])){?>
                    <?php echo link_to('<i class="icon-plus icon-white"></i>', '@delete_role_perm?role_id='.$role->getId().'&perm_id='.$perm->getId(), array('title'=>__('Delete role perm'), 'class'=>'btn btn-mini btn-success'));?>
                <?php }else{?>
                    <?php echo link_to('<i class="icon-minus icon-white"></i>', '@create_role_perm?role_id='.$role->getId().'&perm_id='.$perm->getId(), array('title'=>__('Add role perm'), 'class'=>'btn btn-mini btn-danger'));?>
                <?php }?>
            </td>
        <?php }?>
        </tr>
    <?php }?>
    <tr>
        <form action="<?php echo url_for('@create_role')?>" method="post">
        <td>
            <button title="<?php echo __('Create role')?>" class="btn btn-mini btn-success">
                 <i class="icon-plus icon-white"></i>
            </button>
        </td>
        <td>
            <?php include_partial('default/form_field', array('form'=>$roleForm, 'key'=>'name'))?>
        </td>
        </form>
        <?php for($i = 1; $i<=count($roles); $i++){?>
            <td  class="well"></td>
        <?php }?>
        <td colspan="<?php echo count($perms)?>"></td>
    </tr>
    <tr>
        <th class="users well" colspan="<?php echo count($perms)+count($roles)+2?>">
            <?php echo __('Users')?>
        </th>
    </tr>
    <?php foreach($users as $user){?>
        <tr>
            <td class="delete_playground_user">
                    <?php echo link_to('<i class="icon-trash icon-white"></i>', '@delete_playground_user?user_id='.$user->getId(), array('title'=>__('Delete playground user'), 'class'=>'btn btn-mini btn-danger'));?>
            </td>
            <td>
                <?php echo $user->getFullName();?>
            </td>
        <?php foreach($roles as $role){?>
            <td class="user_role well  text-align-center">
                <?php if(isset($userRoles[$user->getId()][$role->getId()])){?>
                    <?php echo link_to('<i class="icon-plus icon-white"></i>', '@delete_user_role?user_id='.$user->getId().'&role_id='.$role->getId(), array('title'=>__('Delete user role'), 'class'=>'btn btn-mini btn-success'));?>
                <?php }else{?>
                    <?php echo link_to('<i class="icon-minus icon-white"></i>', '@create_user_role?user_id='.$user->getId().'&role_id='.$role->getId(), array('title'=>__('Add user role'), 'class'=>'btn btn-mini btn-danger'));?>
                <?php }?>
            </td>
        <?php }?>
        <?php foreach($perms as $perm){?>
            <td class="user_perm  text-align-center">
                <?php if(isset($userPerms[$user->getId()][$perm->getId()])){?>
                    <?php echo link_to('<i class="icon-plus icon-white"></i>', '@delete_user_perm?user_id='.$user->getId().'&perm_id='.$perm->getId(), array('title'=>__('Delete user perm'), 'class'=>'btn btn-mini btn-success'));?>
                <?php }else{?>
                    <?php echo link_to('<i class="icon-minus icon-white"></i>', '@create_user_perm?user_id='.$user->getId().'&perm_id='.$perm->getId(), array('title'=>__('Add user perm'), 'class'=>'btn btn-mini btn-danger'));?>
                <?php }?>
            </td>
        <?php }?>
        </tr>
    <?php }?>
    <tr>
        <form action="<?php echo url_for('@create_playground_user')?>" method="post">
        <td >
            <button title="<?php echo __('Add playground user')?>" class="btn btn-mini btn-success">
                 <i class="icon-plus icon-white"></i>
            </button>
        </td>
        <td>
            <?php include_partial('default/form', array('form'=>$playgroundUserForm))?>
        </td>
        </form>
        <td colspan="<?php echo count($perms)+count($roles)?>"></td>
    </tr>
    </tbody>
</table>
