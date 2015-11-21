<?php /* Smarty version 2.6.14, created on 2014-01-06 12:47:54
         compiled from index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'index.tpl', 40, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "top.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<body>
<div class="container-bg-header">
<div class="container-bg-footer">
	<div class="wrapper">
    	<header>
        	<div class="container-logo"></div>
            <ul class="container-menu">
				<?php if (! $_SESSION['sess_externuser']): ?>
								<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
								<?php endif; ?>
            </ul>
        </header>
		<?php if ($_GET['action'] == 'search'): ?>
			<div class="container-search-box">
				<div class="container-search-form">
					<h1><?php echo $this->_config[0]['vars']['Search']; ?>
</h1>
					<label><?php echo $this->_config[0]['vars']['USERNAME']; ?>
:</label>
					<input  name="username" type="text" id="username" class="formfield_01" style=" width:143px; margin-right:10px"/>
					<a href="#" class="btn-search" style="width:60px; margin-right:10px;"  onclick="return  doSearch('action=search&type=searchUsername&username='+jQuery('#username').val())"><?php echo $this->_config[0]['vars']['Search']; ?>
</a>

					<form id="search_form">
					<input name="action" type="hidden" value="search" id="action"/>
					<input name="type" type="hidden" value="searchMembers" id="type"/>

					<label style="width:90px !important;"><?php echo $this->_config[0]['vars']['Gender']; ?>
:</label>
					<?php if ($_SESSION['right_search']['q_minage'] != ""): ?>
					<?php $this->assign('select_q_minage', $_SESSION['right_search']['q_minage']); ?>
					<?php else: ?>
					<?php $this->assign('select_q_minage', 18); ?>
					<?php endif; ?>
					<select name="q_gender" id="q_gender" class="formfield_01" style="width:85px; margin-right:10px">
						<option value=""><?php echo $this->_config[0]['vars']['Any']; ?>
</option>
						<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['gender']), $this);?>

					</select>

					<label style="width:70px !important;"><?php echo $this->_config[0]['vars']['Have_Photo']; ?>
:</label>
					<select name="q_picture" id="q_picture" class="formfield_01" style="width:86px; margin-right:10px">
						<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['picyesno'],'selected' => $_SESSION['right_search']['q_picture']), $this);?>

					</select>
					<br class="clear" />

					<label><?php echo $this->_config[0]['vars']['Age']; ?>
:</label>
					<select name="q_minage" id="q_minage" onchange="ageRange('q_minage', 'q_maxage')" class="formfield_01" style="width:93px; margin-right:10px">
						<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['age'],'selected' => $this->_tpl_vars['select_q_minage']), $this);?>

					</select>

					<label style="width:30px !important;"><?php echo $this->_config[0]['vars']['To']; ?>
</label>
					<?php if ($_SESSION['right_search']['q_maxage'] != ""): ?>
					<?php $this->assign('select_q_maxage', $_SESSION['right_search']['q_maxage']); ?>
					<?php else: ?>
					<?php $this->assign('select_q_maxage', $this->_tpl_vars['select_q_minage']+2); ?>
					<?php endif; ?>
					<select name="q_maxage" id="q_maxage" class="formfield_01" style="width:93px; margin-right:10px">
						<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['age'],'selected' => $this->_tpl_vars['select_q_maxage']), $this);?>
  
					</select>

					<label style="width:90px !important;"><?php echo $this->_config[0]['vars']['Country']; ?>
:</label>
					<select id="q_country" name="country" onchange="loadOptionState('#q_state', this.options[this.selectedIndex].value, ''); if((jQuery(this).val()!=1)) jQuery('#state_span').hide(); else jQuery('#state_span').show();" class="formfield_01" style="width:250px; margin-right:10px">
					</select>
					<br class="clear" />

					<span id="state_span" style="display: none">
					<label><?php echo $this->_config[0]['vars']['State']; ?>
:</label>
					<select id="q_state" name="state" class="formfield_01" style="width:225px; margin-right:10px"></select>
					</span>

					<span style="display: none">
					<label><?php echo $this->_config[0]['vars']['City']; ?>
:</label>
					<select id="q_city" name="city" class="formfield_01" style="width:225px; margin-right:10px">
					</select>
					</span>

					<label></label><a href="#" class="btn-login" onclick="return doSearch(jQuery('#search_form').serialize())">Suche</a>
					<br class="clear" />
					</form>
				</div>
				<div class="container-search-icon">
					<a href="#" class="search-m" id="search_women_button" onclick="return doSearch('action=search&type=searchGender&wsex=m&sex=w')"><span><?php echo $this->_config[0]['vars']['MAN_SEARCH_WOMAN']; ?>
</span></a>
					<a href="#" class="search-w" onclick="return doSearch('action=search&type=searchGender&wsex=w&sex=m')"><span><?php echo $this->_config[0]['vars']['WOMAN_SEARCH_MAN']; ?>
</span></a>
					<a href="#" class="search-mm" onclick="return doSearch('action=search&type=searchGender&wsex=m&sex=m')"><span><?php echo $this->_config[0]['vars']['MAN_SEARCH_MAN']; ?>
</span></a>
					<a href="#" class="search-ww" onclick="return doSearch('action=search&type=searchGender&wsex=w&sex=w')"><span><?php echo $this->_config[0]['vars']['WOMAN_SEARCH_WOMAN']; ?>
</span></a>
				</div>
			</div>
		<?php else: ?>
			<?php if ($_SESSION['sess_username'] != "" || $_COOKIE['sess_username'] != ""): ?>
				<div class="container-search-box">
					<div class="container-profile">

						<ul class="container-profile-list" style="margin:20px 10px 20px 20px;">
							<li><a href="?action=profile" class="profile-boder <?php if ($this->_tpl_vars['profile']['approval']): ?>approval<?php endif; ?>"></a><img src="thumbnails.php?file=<?php echo $this->_tpl_vars['MyPicture']; ?>
&w=108&h=108" width="108" height="108" class="profile-img"/></li>
						</ul>

						<div style="width:345px; height:150px; float:left; margin-top:10px;">
						<h2>Letzte Nachrichten</h2>
							<ul class="container-recent">
							<?php $_from = $this->_tpl_vars['recent_contacts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
								<li>
									<a href="?action=viewprofile&username=<?php echo $this->_tpl_vars['item']['username']; ?>
" class="profile-boder"></a>
                                    <img src="thumbnails.php?file=<?php echo $this->_tpl_vars['item']['picturepath']; ?>
&w=70&h=70" width="70" height="70" class="profile-img"/>
			                        <a href="?action=chat&username=<?php echo $this->_tpl_vars['item']['username']; ?>
" class="q-icon q-right q-chat"><span>fav</span></a>
								</li>
							<?php endforeach; endif; unset($_from); ?>
							</ul>
						</div>

						<div style="width:345px; height:150px; float:left; margin-top:10px; margin-left:20px;">
						<h2>Kontaktvorschläge</h2>
							<ul class="container-recent">
							<?php $_from = $this->_tpl_vars['random_contacts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
								<li>
									<a href="?action=viewprofile&username=<?php echo $this->_tpl_vars['item']['username']; ?>
" class="profile-boder"></a><img src="thumbnails.php?file=<?php echo $this->_tpl_vars['item']['picturepath']; ?>
&w=70&h=70" width="70" height="70" class="profile-img"/>
			                        <a href="?action=chat&username=<?php echo $this->_tpl_vars['item']['username']; ?>
" class="q-icon q-right q-chat"><span>fav</span></a>
								</li>
							<?php endforeach; endif; unset($_from); ?>
							</ul>
						</div>

						<ul class="container-profile-list" style="margin:20px 20px 20px 10px;">
							<li><a href="#" class="profile-boder"></a>
							<div style="width:108px; height:108px; margin:7px 0 0 6px; text-align:center;">
								<div style="margin-top:40px;">
								Sie haben!<br />
								<strong style="margin-top:5px; display:block; font-size:16px;"><span id="coinsArea" style="padding: 0px"></span></strong>
								</div>
							</div>
							</li>
						</ul>

					</div>
				</div>
			<?php else: ?>
			<!--box login -->
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form-login.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<!--End box login -->
			<?php endif; ?>
		<?php endif; ?>

		<div class="container-content">

<?php if ($_SESSION['sess_username'] != "" || $_COOKIE['sess_username'] != ""): ?>
	<?php if ($_GET['action'] == 'testpay'): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "payment_1.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php elseif ($_GET['action'] == 'payportal1'): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "payment_1.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php elseif ($_GET['action'] == 'terms'): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "content.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php elseif ($_GET['action'] == "terms-2"): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "content.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php elseif ($_GET['action'] == 'policy'): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "content.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php elseif ($_GET['action'] == "policy-2"): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "content.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php elseif ($_GET['action'] == 'refund'): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "content.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php elseif ($_GET['action'] == "refund-2"): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "content.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php elseif ($_GET['action'] == 'imprint'): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "content.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php elseif ($_GET['action'] == 'webcam'): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "cam_default.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php elseif ($_GET['action'] == 'bonusverify'): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "bonusverify_step2.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php elseif ($_GET['action'] == 'validCode2'): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "sms_validcode2.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php else: ?>
		<?php if (file_exists ( (@SITE)."templates/".($_GET['action']).".tpl" )): ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($_GET['action']).".tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php else: ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php endif; ?>
	<?php endif; ?>
<?php elseif ($_GET['action'] == 'register'): ?>
	<?php if (( $this->_tpl_vars['section'] == "regis-step1-result" )): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "regis-step1-result.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php else: ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "register.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif; ?>
<?php elseif ($_GET['action'] == 'viewcard_mail'): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "viewcard.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php elseif ($_GET['action'] == 'terms'): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "content.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php elseif ($_GET['action'] == "terms-2"): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "content.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php elseif ($_GET['action'] == 'policy'): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "content.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php elseif ($_GET['action'] == "policy-2"): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "content.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php elseif ($_GET['action'] == 'refund'): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "content.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php elseif ($_GET['action'] == "refund-2"): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "content.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php elseif ($_GET['action'] == 'imprint'): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "content.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php elseif ($_GET['action'] == 'membership'): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "membership_1.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php else: ?>
	<?php if (file_exists ( (@SITE)."templates/".($_GET['action']).".tpl" )): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($_GET['action']).".tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php else: ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif; ?>
<?php endif; ?>
		</div>
		<br class="clear" />
	</div>
	<!--end warper-->
</div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</body>
</html>