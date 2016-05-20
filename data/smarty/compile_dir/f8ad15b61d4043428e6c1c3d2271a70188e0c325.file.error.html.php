<?php /* Smarty version Smarty-3.0.8, created on 2014-03-28 11:18:01
         compiled from "./templates/error.html" */ ?>
<?php /*%%SmartyHeaderCode:13094605075334e4e4b473f4-57069344%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f8ad15b61d4043428e6c1c3d2271a70188e0c325' => 
    array (
      0 => './templates/error.html',
      1 => 1395976659,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13094605075334e4e4b473f4-57069344',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title></title>
</head>

<body>

<div class="content">
	<div class="box">
        
        <div class="working">
	        <div class="error_box">
	            <div class="ErrorMessage"><dt><?php echo $_smarty_tpl->getVariable('Lang')->value['ErrorMessage'];?>
:</dt><dl><?php echo $_smarty_tpl->getVariable('message')->value;?>
</dl></div>
            	<div class="errorCode"><dt><?php echo $_smarty_tpl->getVariable('Lang')->value['ErrorCode'];?>
:</dt><dl><?php echo $_smarty_tpl->getVariable('errorCode')->value;?>
</dl></div>
                <?php if ($_smarty_tpl->getVariable('DEBUG')->value){?>
                <div class="errorFile"><dt><?php echo $_smarty_tpl->getVariable('Lang')->value['ErrorFile'];?>
:</dt><dl><?php echo $_smarty_tpl->getVariable('errorFile')->value;?>
</dl></div>
                <div class="errorLine"><dt><?php echo $_smarty_tpl->getVariable('Lang')->value['ErrorLine'];?>
:</dt><dl><?php echo $_smarty_tpl->getVariable('errorLine')->value;?>
</dl></div>
                <div class="errorLevel"><dt><?php echo $_smarty_tpl->getVariable('Lang')->value['ErrorLevel'];?>
:</dt><dl><?php echo $_smarty_tpl->getVariable('errorLevel')->value;?>
</dl></div>
                <div class="trace"><dt><?php echo $_smarty_tpl->getVariable('Lang')->value['ErrorTrace'];?>
:</dt><dl><?php echo $_smarty_tpl->getVariable('trace')->value;?>
</dl></div>
                <?php }?>
            </div>
            
         </div>

    </div>
</div>

</body>
</html>