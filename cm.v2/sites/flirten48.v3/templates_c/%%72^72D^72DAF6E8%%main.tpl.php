<?php /* Smarty version 2.6.14, created on 2013-11-20 17:41:41
         compiled from main.tpl */ ?>
<!-- <?php echo 'main.tpl'; ?>
 -->
<!--<div id="container-top-content-area"> -->
<div class="container-login-profile">
<?php if ($_SESSION['sess_username'] != "" || $_COOKIE['sess_username'] != ""): ?>

<!--start -->
<div class="bg-box-viewprofile" style="width:215px !important;">
<h5 class="title">Hallo <strong><?php echo $_SESSION['sess_username']; ?>
</strong></h5>
<!--profile image -->
    <ul id="container-profile-list" style=" float:left; margin-left:45px;">
    <li>
        <a href="?action=profile">
        <div class="profile-list">
            <div class="boder-profile-img">
				<?php if ($this->_tpl_vars['profile']['approval']): ?>
				<!-- watermark -->
				<img src="images/cm-theme/wait.png" width="120" height="121" />
				<?php else: ?>
				<img src="images/cm-theme/profile-boder-img.png" width="120" height="121" />
				<?php endif; ?>
			</div>
            <div class="img-profile" style=" top:-118px !important"><img src="thumbnails.php?file=<?php echo $this->_tpl_vars['MyPicture']; ?>
&w=102&h=103" width="97" height="98" /></div>
        </div>
        </a>
    </li>
    </ul>
<!--end profile image -->
<br class="clear" />
<!--Letzte Nachrichten-->
<?php if ($this->_tpl_vars['recent_contacts']): ?>
    <!--Recent -->
    <div class="container-list-most">
    <h1>Letzte Nachrichten</h1>
    <ul id="container-profile-list-most">
        <?php $_from = $this->_tpl_vars['recent_contacts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
        <li>
            <a href="?action=viewprofile&username=<?php echo $this->_tpl_vars['item']['username']; ?>
">
            <div class="profile-list-most">
                <div class="boder-profile-img-most"><img src="images/cm-theme/profile-boder-img.png" width="88" height="89" /></div>
                <div class="img-profile-most"><img src="thumbnails.php?file=<?php echo $this->_tpl_vars['item']['picturepath']; ?>
&w=72&h=73" width="72" height="73" /></div>
            </div>
            </a>
            <div class="container-quick-icon">
                <a href="?action=chat&username=<?php echo $this->_tpl_vars['item']['username']; ?>
" class="quick-icon-left message-icon" title="<?php echo $this->_config[0]['vars']['Message']; ?>
"></a>
            </div>
        </li>
        <?php endforeach; endif; unset($_from); ?>
    </ul>
    </div>
    <!--end Recent -->
<?php endif; ?>
<!--End Letzte Nachrichten-->

<!-- Kontaktvorschläge-->			
<?php if ($this->_tpl_vars['random_contacts']): ?>
    <!--Recent -->
    <div class="container-list-most">
    <h1>Kontaktvorschläge</h1>
    <ul id="container-profile-list-most">
        <?php $_from = $this->_tpl_vars['random_contacts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
        <li>
            <a href="?action=viewprofile&username=<?php echo $this->_tpl_vars['item']['username']; ?>
">
            <div class="profile-list-most">
                <div class="boder-profile-img-most"><img src="images/cm-theme/profile-boder-img.png" width="88" height="89" /></div>
                <div class="img-profile-most"><img src="thumbnails.php?file=<?php echo $this->_tpl_vars['item']['picturepath']; ?>
&w=82&h=83" width="72" height="73" /></div>
            </div>
            </a>
            <div class="container-quick-icon">
                <a href="?action=chat&username=<?php echo $this->_tpl_vars['item']['username']; ?>
" class="quick-icon-left message-icon" title="<?php echo $this->_config[0]['vars']['Message']; ?>
"></a>
            </div>
        </li>
        <?php endforeach; endif; unset($_from); ?>
    </ul>
    </div>
    <!--end Recent -->
<?php endif; ?>
<!--end Kontaktvorschläge-->

</div>
<!--end -->


<?php else: ?>

<div id="container-top-content-sub-l">               
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "left-notlogged.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
 

 
<div id="container-register-box">
    <div class="container-boder-register">
	<form id="form_register_small" method="post" action="?action=register">
        <h3 class="title">Herzlich Willkommen</h3>
        
        <input name="username" type="text" class="formfield_01" placeholder="Nickname" AUTOCOMPLETE=OFF style="width:190px !important; margin-left:14px; margin-top:2px;"/> 
        <input name="email" type="text" class="formfield_01" placeholder="E-Mail" autocomplete='off' style="width:190px !important; margin-top:5px; margin-left:14px;"/>
        <a href="#" class="btn-red btn-register" onclick="document.getElementById('form_register_small').submit(); return false;"><input name="submitbutton" type="submit" value="submit" style="display: none"/>KOSTENLOS ANMELDEN</a>
        <a href="<?php echo @FACEBOOK_LOGIN_URL;  echo $_SESSION['state']; ?>
" class="register-facebook"><span>register-facebook</span></a>
	</form>
    </div>
</div>
 
</div>

<div id="container-profile-online">
<h1 class="title">Online</h1>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "online.tpl", 'smarty_include_vars' => array('total' => '18')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>

<div id="container-content">
<h1 class="title"><?php echo $this->_config[0]['vars']['Newest_main']; ?>
</h1>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "newest_members_box.tpl", 'smarty_include_vars' => array('total' => '8')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
<?php endif; ?>
</div>

<?php if (( $_SESSION['sess_username'] != "" )): ?>
    <!--start banner verify -->
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "banner-verify-mobile.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <!--end banner verify -->

	<?php if (( ( $this->_tpl_vars['bonusid'] != '' ) && ( $this->_tpl_vars['bonusid'] > 0 ) )): ?>
		<span id="bonusverify_box">
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "bonusverify_step1.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		</span>
	<?php endif; ?>

    <div style="float:left; width:770px; margin-left:10px;">
        <h1 class="title">Online</h1>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "online.tpl", 'smarty_include_vars' => array('total' => '12')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </div>
    
    <div style="float:right; width:770px; margin-left:10px; margin-right:5px;">
        <h1 class="title"><?php echo $this->_config[0]['vars']['Newest_main']; ?>
</h1>
        <?php $this->assign('total', '12'); ?>
        <?php if (@COIN_VERIFY_MOBILE > 0): ?>
		<?php if (! $this->_tpl_vars['mobile_verified']): ?>
        	<?php $this->assign('total', '6'); ?>
        <?php endif; ?>
        <?php endif; ?>
        
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "newest_members_box.tpl", 'smarty_include_vars' => array('total' => ($this->_tpl_vars['total']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "my_favorite.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>