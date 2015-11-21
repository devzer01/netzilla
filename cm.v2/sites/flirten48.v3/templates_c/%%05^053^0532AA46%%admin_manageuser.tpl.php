<?php /* Smarty version 2.6.14, created on 2013-11-19 16:45:46
         compiled from admin_manageuser.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin_manageuser.tpl', 84, false),array('function', 'paginate_prev', 'admin_manageuser.tpl', 147, false),array('function', 'paginate_middle', 'admin_manageuser.tpl', 147, false),array('function', 'paginate_next', 'admin_manageuser.tpl', 147, false),)), $this); ?>
<!-- <?php echo 'admin_manageuser.tpl'; ?>
 -->
<h1 class="admin-title"><?php echo $this->_config[0]['vars']['MANAGE_USER']; ?>
</h1>
<div class="result-box">
<div class="result-box-inside">
<a href="?action=admin_adduser" class="btn-admin"><?php echo $this->_config[0]['vars']['Add_New_User']; ?>
</a>
<br class="clear" /><br class="clear" />

<?php if ($this->_tpl_vars['userrec']): ?>
<table width="100%"  border="0">
<tr bgcolor="#2d2d2d" height="28px">
	<td width="50" align="center">
	<?php if ($_GET['order'] == ""): ?>
	<?php if ($_GET['type'] == 'asc'): ?>
	<a href="?action=admin_manageuser&order=&type=desc&g=<?php echo $_GET['g']; ?>
&lg=<?php echo $_GET['lg']; ?>
&f=<?php echo $_GET['f']; ?>
&co=<?php echo $_GET['co']; ?>
&s=<?php echo $_GET['s']; ?>
&ci=<?php echo $_GET['ci']; ?>
&u=<?php echo $_GET['u']; ?>
" class="sitelink"><img src="images/s_desc.png" border="0"></a>
	<?php else: ?>
	<a href="?action=admin_manageuser&order=&type=asc&g=<?php echo $_GET['g']; ?>
&lg=<?php echo $_GET['lg']; ?>
&f=<?php echo $_GET['f']; ?>
&co=<?php echo $_GET['co']; ?>
&s=<?php echo $_GET['s']; ?>
&ci=<?php echo $_GET['ci']; ?>
&u=<?php echo $_GET['u']; ?>
" class="sitelink"><img src="images/s_asc.png" border="0"></a>
	<?php endif; ?>
	<?php else: ?>
	<a href="?action=admin_manageuser&order=&type=desc&g=<?php echo $_GET['g']; ?>
&lg=<?php echo $_GET['lg']; ?>
&f=<?php echo $_GET['f']; ?>
&co=<?php echo $_GET['co']; ?>
&s=<?php echo $_GET['s']; ?>
&ci=<?php echo $_GET['ci']; ?>
&u=<?php echo $_GET['u']; ?>
" class="sitelink">#</a>
	<?php endif; ?>
	</td>
	<!-- /////<td  align="center">Type</td>-->
	<td align="center" width="100">
	<?php if ($_GET['order'] == 'name'): ?>
	    <?php if ($_GET['type'] == 'desc'): ?>
		<a href="?action=admin_manageuser&order=name&type=asc&g=<?php echo $_GET['g']; ?>
&lg=<?php echo $_GET['lg']; ?>
&f=<?php echo $_GET['f']; ?>
&co=<?php echo $_GET['co']; ?>
&s=<?php echo $_GET['s']; ?>
&ci=<?php echo $_GET['ci']; ?>
&u=<?php echo $_GET['u']; ?>
" class="sitelink"><?php echo $this->_config[0]['vars']['USERNAME']; ?>
</a> <img src="images/s_desc.png">
	    <?php else: ?>
		<a href="?action=admin_manageuser&order=name&type=desc&g=<?php echo $_GET['g']; ?>
&lg=<?php echo $_GET['lg']; ?>
&f=<?php echo $_GET['f']; ?>
&co=<?php echo $_GET['co']; ?>
&s=<?php echo $_GET['s']; ?>
&ci=<?php echo $_GET['ci']; ?>
&u=<?php echo $_GET['u']; ?>
" class="sitelink"><?php echo $this->_config[0]['vars']['USERNAME']; ?>
</a> <img src="images/s_asc.png">
	    <?php endif; ?>
	<?php else: ?>
		<a href="?action=admin_manageuser&order=name&type=asc&g=<?php echo $_GET['g']; ?>
&lg=<?php echo $_GET['lg']; ?>
&f=<?php echo $_GET['f']; ?>
&co=<?php echo $_GET['co']; ?>
&s=<?php echo $_GET['s']; ?>
&ci=<?php echo $_GET['ci']; ?>
&u=<?php echo $_GET['u']; ?>
" class="sitelink"><?php echo $this->_config[0]['vars']['USERNAME']; ?>
</a>
	<?php endif; ?>
	</td>

	<td align="center" width="100">
	<?php if ($_GET['order'] == 'registred'): ?>
	<?php if ($_GET['type'] == 'desc'): ?>
	<a href="?action=admin_manageuser&order=registred&type=asc&g=<?php echo $_GET['g']; ?>
&lg=<?php echo $_GET['lg']; ?>
&f=<?php echo $_GET['f']; ?>
&co=<?php echo $_GET['co']; ?>
&s=<?php echo $_GET['s']; ?>
&ci=<?php echo $_GET['ci']; ?>
&u=<?php echo $_GET['u']; ?>
" class="sitelink"><?php echo $this->_config[0]['vars']['Registered']; ?>
</a> <img src="images/s_desc.png">
	<?php else: ?>
	<a href="?action=admin_manageuser&order=registred&type=desc&g=<?php echo $_GET['g']; ?>
&lg=<?php echo $_GET['lg']; ?>
&f=<?php echo $_GET['f']; ?>
&co=<?php echo $_GET['co']; ?>
&s=<?php echo $_GET['s']; ?>
&ci=<?php echo $_GET['ci']; ?>
&u=<?php echo $_GET['u']; ?>
" class="sitelink"><?php echo $this->_config[0]['vars']['Registered']; ?>
</a> <img src="images/s_asc.png">
	<?php endif; ?>
	<?php else: ?>
	<a href="?action=admin_manageuser&order=registred&type=asc&g=<?php echo $_GET['g']; ?>
&lg=<?php echo $_GET['lg']; ?>
&f=<?php echo $_GET['f']; ?>
&co=<?php echo $_GET['co']; ?>
&s=<?php echo $_GET['s']; ?>
&ci=<?php echo $_GET['ci']; ?>
&u=<?php echo $_GET['u']; ?>
" class="sitelink"><?php echo $this->_config[0]['vars']['Registered']; ?>
</a>
	<?php endif; ?>
	</td>							

	<td align="center" width="90">
	<?php if ($_GET['order'] == 'city'): ?>
	<?php if ($_GET['type'] == 'desc'): ?>
	<a href="?action=admin_manageuser&order=city&type=asc&g=<?php echo $_GET['g']; ?>
&lg=<?php echo $_GET['lg']; ?>
&f=<?php echo $_GET['f']; ?>
&co=<?php echo $_GET['co']; ?>
&s=<?php echo $_GET['s']; ?>
&ci=<?php echo $_GET['ci']; ?>
&u=<?php echo $_GET['u']; ?>
" class="sitelink"><?php echo $this->_config[0]['vars']['City']; ?>
</a> <img src="images/s_desc.png">
	<?php else: ?>
	<a href="?action=admin_manageuser&order=city&type=desc&g=<?php echo $_GET['g']; ?>
&lg=<?php echo $_GET['lg']; ?>
&f=<?php echo $_GET['f']; ?>
&co=<?php echo $_GET['co']; ?>
&s=<?php echo $_GET['s']; ?>
&ci=<?php echo $_GET['ci']; ?>
&u=<?php echo $_GET['u']; ?>
" class="sitelink"><?php echo $this->_config[0]['vars']['City']; ?>
</a> <img src="images/s_asc.png">
	<?php endif; ?>
	<?php else: ?>
	<a href="?action=admin_manageuser&order=city&type=asc&g=<?php echo $_GET['g']; ?>
&lg=<?php echo $_GET['lg']; ?>
&f=<?php echo $_GET['f']; ?>
&co=<?php echo $_GET['co']; ?>
&s=<?php echo $_GET['s']; ?>
&ci=<?php echo $_GET['ci']; ?>
&u=<?php echo $_GET['u']; ?>
" class="sitelink"><?php echo $this->_config[0]['vars']['City']; ?>
</a>
	<?php endif; ?>
	</td>
	<td align="center" width="100">
	<?php if ($_GET['order'] == 'country'): ?>
	<?php if ($_GET['type'] == 'desc'): ?>
	<a href="?action=admin_manageuser&order=country&type=asc&g=<?php echo $_GET['g']; ?>
&lg=<?php echo $_GET['lg']; ?>
&f=<?php echo $_GET['f']; ?>
&co=<?php echo $_GET['co']; ?>
&s=<?php echo $_GET['s']; ?>
&ci=<?php echo $_GET['ci']; ?>
&u=<?php echo $_GET['u']; ?>
" class="sitelink"><?php echo $this->_config[0]['vars']['Country']; ?>
</a> <img src="images/s_desc.png">
	<?php else: ?>
	<a href="?action=admin_manageuser&order=country&type=desc&g=<?php echo $_GET['g']; ?>
&lg=<?php echo $_GET['lg']; ?>
&f=<?php echo $_GET['f']; ?>
&co=<?php echo $_GET['co']; ?>
&s=<?php echo $_GET['s']; ?>
&ci=<?php echo $_GET['ci']; ?>
&u=<?php echo $_GET['u']; ?>
" class="sitelink"><?php echo $this->_config[0]['vars']['Country']; ?>
</a> <img src="images/s_asc.png">
	<?php endif; ?>
	<?php else: ?>
	<a href="?action=admin_manageuser&order=country&type=asc&g=<?php echo $_GET['g']; ?>
&lg=<?php echo $_GET['lg']; ?>
&f=<?php echo $_GET['f']; ?>
&co=<?php echo $_GET['co']; ?>
&s=<?php echo $_GET['s']; ?>
&ci=<?php echo $_GET['ci']; ?>
&u=<?php echo $_GET['u']; ?>
" class="sitelink"><?php echo $this->_config[0]['vars']['Country']; ?>
</a>
	<?php endif; ?>
	</td>
	<td align="center" width="80">
	<?php if ($_GET['order'] == 'flag'): ?>
	<?php if ($_GET['type'] == 'desc'): ?>
	<a href="?action=admin_manageuser&order=flag&type=asc&g=<?php echo $_GET['g']; ?>
&lg=<?php echo $_GET['lg']; ?>
&f=<?php echo $_GET['f']; ?>
&co=<?php echo $_GET['co']; ?>
&s=<?php echo $_GET['s']; ?>
&ci=<?php echo $_GET['ci']; ?>
&u=<?php echo $_GET['u']; ?>
" class="sitelink"><?php echo $this->_config[0]['vars']['Edit']; ?>
</a> <img src="images/s_desc.png">
	<?php else: ?>
	<a href="?action=admin_manageuser&order=flag&type=desc&g=<?php echo $_GET['g']; ?>
&lg=<?php echo $_GET['lg']; ?>
&f=<?php echo $_GET['f']; ?>
&co=<?php echo $_GET['co']; ?>
&s=<?php echo $_GET['s']; ?>
&ci=<?php echo $_GET['ci']; ?>
&u=<?php echo $_GET['u']; ?>
" class="sitelink"><?php echo $this->_config[0]['vars']['Edit']; ?>
</a> <img src="images/s_asc.png">
	<?php endif; ?>
	<?php else: ?>
	<a href="?action=admin_manageuser&order=flag&type=asc&g=<?php echo $_GET['g']; ?>
&lg=<?php echo $_GET['lg']; ?>
&f=<?php echo $_GET['f']; ?>
&co=<?php echo $_GET['co']; ?>
&s=<?php echo $_GET['s']; ?>
&ci=<?php echo $_GET['ci']; ?>
&u=<?php echo $_GET['u']; ?>
" class="sitelink"><?php echo $this->_config[0]['vars']['Edit']; ?>
</a>
	<?php endif; ?>
	</td>

	<td align="center" width="45"><a href="#">Action</a></td>
</tr>
<?php $_from = $this->_tpl_vars['userrec']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['userdata']):
?>
<tr  bgcolor="<?php echo smarty_function_cycle(array('values' => "#ccb691,#fde6be"), $this);?>
">
	<td  align="center"><?php if ($this->_tpl_vars['userdata']['picturepath'] != ""): ?><img src="images/has_pic.png"><?php endif; ?></td>
	
	<td width="100" align="center"><a href="?action=viewprofile&username=<?php echo $this->_tpl_vars['userdata']['username']; ?>
&from=admin" class="admin-link"><?php echo $this->_tpl_vars['userdata']['username']; ?>
</a><?php if (( $this->_tpl_vars['userdata']['agent'] ) && ( $this->_tpl_vars['userdata']['fake'] )): ?><br/><font color="yellow">[<?php echo $this->_tpl_vars['userdata']['agent']; ?>
]<?php endif; ?></font></td>
	<td width="100px"  align="center"><?php echo $this->_tpl_vars['userdata']['registred']; ?>
</td>
	<td width="90px"  align="center"><?php echo $this->_tpl_vars['userdata']['city']; ?>
</td>
<!--	<td width="100px"><?php echo $this->_tpl_vars['userdata']['state']; ?>
</td>  -->
	<?php if ($this->_tpl_vars['userdata']['country'] == Germany): ?>
	<td width="20px"  align="center">DE</td>				
	<?php elseif ($this->_tpl_vars['userdata']['country'] == Switzerland): ?>
	<td width="20px"  align="center">CH</td>
	<?php elseif ($this->_tpl_vars['userdata']['country'] == Austria): ?>
	<td width="20px"  align="center">AT</td>							
	<?php elseif ($this->_tpl_vars['userdata']['country'] == 'United Kingdom'): ?>
	<td width="20px"  align="center">UK</td>							
	<?php elseif ($this->_tpl_vars['userdata']['country'] == 'Belgium'): ?>
	<td width="20px"  align="center">BE</td>
	<?php else: ?>
	<td width="20px"  align="center"></td>
	<?php endif; ?>							
	<?php if ($this->_tpl_vars['userdata']['flag'] == 1): ?>
	<td align="center"  align="center">Yes</td>
	<?php else: ?>
	<td align="center">No</td>
	<?php endif; ?>							
	<td width="45">
		<div align="center">
		<a href="?action=viewprofile&username=<?php echo $this->_tpl_vars['userdata']['username']; ?>
&proc=edit&from=admin#editprofile" onclick="showEditProfile('<?php echo $this->_tpl_vars['userdata']['username']; ?>
'); return false;" title="Edit">
		<img src="images/icon/b_edit.png" width="16" height="16" border="0"></a>
		<?php if ($_SESSION['sess_permission'] == 1): ?>
			<?php if ($this->_tpl_vars['userdata']['status'] != 1): ?>
			<a href="?action=admin_manageuser&user=<?php echo $this->_tpl_vars['userdata']['username']; ?>
&proc=del" onclick="return confirm(confirm_delete_box)" title="Delete">
			<img src="images/icon/b_drop.png" width="16" height="16" border="0">
			</a>
			<?php else: ?>
			<img src="images/icon/b_drop_disable.png" width="16" height="16">
			<?php endif; ?>

			<?php if ($this->_tpl_vars['userdata']['status'] != 1): ?>
			<a href="?action=admin_manageuser&user=<?php echo $this->_tpl_vars['userdata']['username']; ?>
&proc=block" onclick="return confirm('Are you sure to block this member?')" title="Block">
			<img src="images/icon/b_drop_block.png" width="16" height="16" border="0">
			</a>
			<?php else: ?>
			<img src="images/icon/b_drop_disable.png" width="16" height="16">
			<?php endif; ?>

			<?php if ($this->_tpl_vars['userdata']['vcode_mobile_insert_time'] != 0): ?>
			<a href="?action=admin_manageuser&user=<?php echo $this->_tpl_vars['userdata']['username']; ?>
&proc=resetphone" onclick="return confirm('Are you sure to reset this member mobile phone verification?')" title="Reset phone">
			<img src="images/icon/reset_icon.png" width="16" height="16" border="0">
			</a>
			<?php endif; ?>

			<a href="?action=admin_manageuser&user=<?php echo $this->_tpl_vars['userdata']['username']; ?>
&proc=sendcoins&coins=" onclick="return sendcoins(this, '<?php echo $this->_tpl_vars['userdata']['username']; ?>
');" title="Send coins">
            <img src="images/icon/coins.png" width="16" height="16" border="0">
            </a>
		<?php endif; ?>
		</div>
	</td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
</div>
</div>
<div class="page"><?php echo smarty_function_paginate_prev(array('class' => "pre-pager"), $this);?>
 <?php echo smarty_function_paginate_middle(array('class' => "num-pager"), $this);?>
 <?php echo smarty_function_paginate_next(array('class' => "next-pager"), $this);?>
</div>
<?php endif; ?>

<script>
<?php if ($this->_tpl_vars['admin_manageuser_error']): ?>
alert('<?php echo $this->_tpl_vars['admin_manageuser_error']; ?>
');
<?php endif; ?>
<?php echo '
function sendcoins(obj, username)
{
	var coins = prompt(\'How many coins you want to send to \'+username+\'?\');

	if (coins!=null && coins!="")
	{
		var url = jQuery(obj).attr(\'href\')
		jQuery(obj).attr(\'href\', url+coins);
		return true;
	}
	else
	{
		return false;
	}
}

function showEditProfile(username)
{
	var url = "?action=';  echo $_GET['action'];  echo '&proc=getProfile&username="+username;
	loadPagePopup(url, \'100%\');
}
'; ?>

</script>