<?php /* Smarty version 2.6.14, created on 2013-11-19 16:32:16
         compiled from chat_list.tpl */ ?>
<?php if ($_GET['part'] == 'part1'): ?>
<?php echo '

<script language="javascript" type="text/javascript">
	var to_ok = false;
	var sms_ok = false;
	
	jQuery("#emoticons").click(function (e) {
		e.preventDefault();
		jQuery("#iconlist").css("left", jQuery(this).parent().offset().left + \'px\');
		jQuery("#iconlist").css("top", (jQuery(this).parent().offset().top + 30) + \'px\');
		jQuery("#iconlist").fadeIn();
	});
</script>
'; ?>

<form id="message_write_form" name="message_write_form" method="post" onsubmit="return false;">
<input type="hidden" name="act" value="writemsg" />
<input id="to" name="to" type="hidden" style="width:180px" value="<?php echo $this->_tpl_vars['username']; ?>
" style="float: left">
		<h1><?php echo $this->_tpl_vars['coin_charge_sms']; ?>
</h1>
		<textarea id="sms" name="sms" maxlength="<?php echo @MAX_CHARACTERS; ?>
" onclick="markAsRead('<?php echo $this->_tpl_vars['username']; ?>
')" tabindex="1"><?php echo $this->_tpl_vars['save']['message']; ?>
</textarea>
	  
		<!--profile image -->
		<ul id="container-profile-list" style=" float:right; margin-left:0; margin-top:3px !important;">
		<li>
			<?php if ($this->_tpl_vars['username'] == @ADMIN_USERNAME_DISPLAY): ?>
			<a>
			<div class="profile-list">
				<div class="boder-profile-img"><img src="images/cm-theme/profile-boder-img.png" width="120" height="121" /></div>
				<div class="img-profile" style=" top:-113px !important"><img src="thumbnails.php?username=bigbrother&w=97&h=98" width="97" height="98" /></div>
			</div>
			</a>
			<?php else: ?>
			<a href="?action=viewprofile&username=<?php echo $this->_tpl_vars['username']; ?>
">
			<div class="profile-list">
				<div class="boder-profile-img"><img src="images/cm-theme/profile-boder-img.png" width="120" height="121" /></div>
				<div class="img-profile" style=" top:-113px !important"><img src="thumbnails.php?username=<?php echo $this->_tpl_vars['username']; ?>
&w=97&h=98" width="97" height="98" /></div>
			</div>
			</a>
			<?php endif; ?>
		</li>
		</ul>
		<span style="color:#000; position:relative; top:-28px; margin-left:30px;">
		<input readonly type="text" id="countdown" name="countdown" size="3" value="<?php echo @MAX_CHARACTERS; ?>
" style="background:none; border:none; color:#000; font-weight:bold; text-align:right;"> <?php echo $this->_config[0]['vars']['SMS_LEFT']; ?>

		<font style="line-height:26px; color:#000;">(<?php echo $this->_config[0]['vars']['SMS_MAX']; ?>
)</font>
        </span>

		<!--end profile image -->
		<div class="container-btn-chat">
		<a href="#" <?php if (( @ATTACHMENTS == 1 ) && ( @ATTACHMENTS_COIN == 1 ) && ( $this->_tpl_vars['already_topup'] )): ?>onclick="showAttachmentsList('coins'); return false;"<?php endif; ?> class="btn-coin-icon"></a>
		<a href="#" id='emoticons' class="btn-emoticon"></a>
		<a href="javascript:void(0)" onclick="sendChatMessage('sms');" id="sms_send_button" class="btn-sms-chat" tabindex="2"><?php echo $this->_config[0]['vars']['SMS_SEND']; ?>
</a>
		<a href="javascript:void(0)" onclick="sendChatMessage('email');"  id="email_send_button" class="btn-email-chat" tabindex="3"><span><?php echo $this->_config[0]['vars']['Email_SEND']; ?>
</span></a>
		</div>
		<div id="attachments-list"></div>
</form>
<br class="clear"/>
<?php else: ?>
<?php $_from = $this->_tpl_vars['messages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['message']):
?>
	<div class="container-history-<?php if ($this->_tpl_vars['message']['username'] == $this->_tpl_vars['username']): ?>left<?php else: ?>right<?php endif; ?>">
    <h1><strong><?php echo $this->_tpl_vars['message']['username']; ?>
</strong> [<?php echo $this->_tpl_vars['message']['datetime']; ?>
] <?php if (( $this->_tpl_vars['message']['status'] == 0 ) && ( $this->_tpl_vars['message']['username'] == $this->_tpl_vars['username'] )): ?><img src="images/cm-theme/new_icon.gif"/><?php endif; ?></h1>
    <p>		
		<?php echo $this->_tpl_vars['message']['message']; ?>

       
        <?php if ($this->_tpl_vars['message']['attachment_coins'] > 0): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "attachments-coins-display2.tpl", 'smarty_include_vars' => array('coins' => $this->_tpl_vars['message']['attachment_coins'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php endif; ?>
    </p>
    </div>
<?php endforeach; endif; unset($_from); ?>

<script>
totalMessages = <?php echo $this->_tpl_vars['total']; ?>
;
</script>
<?php endif; ?>
<div id='iconlist'>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'emoticons.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>