<?php /* Smarty version 2.6.14, created on 2013-11-20 09:52:05
         compiled from main.tpl */ ?>
<?php if ($_SESSION['sess_username'] != "" || $_COOKIE['sess_username'] != ""): ?>
    <div class="container-search-box">
            <div class="container-profile">
            	
                <ul class="container-profile-list" style="margin:20px 10px 20px 20px;">
                	<li><a href="?action=profile" class="profile-boder"></a>
                		<img src="thumbnails.php?file=<?php echo $this->_tpl_vars['memberProfile']['picturepath']; ?>
" width="108" height="108" class="profile-img"/></li>
            	</ul>
                
                <div style="width:345px; height:150px; float:left; margin-top:10px;">
                <h2>Letzte Nachrichten</h2>
                    <ul class="container-recent">
                    	<?php $_from = $this->_tpl_vars['recent_contacts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['member']):
?>
	                    	<li>
	                        <a href="?action=viewprofile&username=<?php echo $this->_tpl_vars['member']['username']; ?>
" class="profile-boder"></a><img src="thumbnails.php?file=<?php echo $this->_tpl_vars['member']['picturepath']; ?>
" width="70" height="70" class="profile-img"/>
	                        <a href="?action=chat&username=<?php echo $this->_tpl_vars['member']['username']; ?>
" class="q-icon q-right q-chat"><span>fav</span></a>
	                        </li>
	                   	<?php endforeach; endif; unset($_from); ?>
                    </ul>
                </div>
                
                <div style="width:345px; height:150px; float:left; margin-top:10px; margin-left:20px;">
                <h2>Kontaktvorschläge</h2>
                    <ul class="container-recent">
                    	<?php $_from = $this->_tpl_vars['random_contacts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['member']):
?>
	                    	<li>
	                        <a href="?action=viewprofile&username=<?php echo $this->_tpl_vars['member']['username']; ?>
" class="profile-boder"></a><img src="thumbnails.php?file=<?php echo $this->_tpl_vars['member']['picturepath']; ?>
" width="70" height="70" class="profile-img"/>
	                        <a href="?action=chat&username=<?php echo $this->_tpl_vars['member']['username']; ?>
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
						<strong style="margin-top:5px; display:block; font-size:16px;"><?php if ($this->_tpl_vars['coin']):  echo $this->_tpl_vars['coin'];  else: ?>0<?php endif; ?> coins</strong>
                        </div>
                    </div>
                    </li>
            	</ul>
                
            </div>
        </div>
        <!-- banner-->
	<?php if (@COIN_VERIFY_MOBILE > 0 && ! $this->_tpl_vars['mobile_verified']): ?>
        <div style="width:1024px; height:120px; float:left; margin-top:5px"><a href="#" onclick="showVerifyMobileDialog(); return false;"><img src="images/banner-mobile.png" width="1025" height="121" /></a></div>    
	<?php endif; ?>
<?php else: ?>

	<div class="container-login-box">
	<!--box login -->
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form-login.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<!--End box login --> 
	<!--box register -->    
		
		
			<div class="container-register">
            	<h1><?php echo $this->_config[0]['vars']['Register']; ?>
</h1>
            	<form id="form_register_small" method="post" action="?action=register">
	                <input name="username" type="text" placeholder="<?php echo $this->_config[0]['vars']['USERNAME']; ?>
" AUTOCOMPLETE=OFF class="formfield_01" style=" width:215px; margin-right:10px"/>
	                <input name="email" type="text"  class="formfield_01" placeholder="<?php echo $this->_config[0]['vars']['Email']; ?>
" style=" width:215px;"/><br class="clear" />
	                <div style="float:left; margin-bottom:10px; margin-top:8px;"><input name="" type="radio" value="" />Male</div>
	                <div style="float:left; margin-bottom:10px; margin-top:8px; margin-left:10px;"><input name="" type="radio" value="" />Female</div><br class="clear" />
	                <a href="<?php echo @FACEBOOK_LOGIN_URL;  echo $_SESSION['state']; ?>
" class="btn-login-fb" style="padding-left:32px; width:198px;">SIGN UP THROUGH FACEBOOK</a>
	                <a href="#" onclick="$('#form_register_small').submit(); return false;" class="btn-login"><?php echo $this->_config[0]['vars']['Register']; ?>
</a>
	          	</form>
            </div>
	<!--End box register --> 
	</div>
<?php endif; ?>

<div class="container-content">
        	<div class="container-content-box" style="float:left;">
            	<h1>ONLINE</h1>
                <ul class="container-profile-list">
                	<?php $_from = $this->_tpl_vars['online_members']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['member']):
?>
                		<li>
                			<a href="?action=viewprofile&username=<?php echo $this->_tpl_vars['member']['username']; ?>
" class="profile-boder"></a><img src="thumbnails.php?file=<?php echo $this->_tpl_vars['member']['picturepath']; ?>
" width="108" height="108" class="profile-img"/><p><?php echo $this->_tpl_vars['member']['username']; ?>
</p>
                			<?php if ($_SESSION['sess_username'] != ""): ?>
                				<?php if (! in_array ( $this->_tpl_vars['member']['username'] , $this->_tpl_vars['favorites_list'] )): ?>
									<a href="#" class="q-icon q-fav" title="Favorite" onclick="$(this).remove(); return addFavorite('<?php echo $this->_tpl_vars['member']['username']; ?>
','favorite-list-container');"><span>fav</span></a>
								<?php else: ?>
									<a href="#" class="q-icon del-icon-g" title="<?php echo $this->_config[0]['vars']['Delete']; ?>
" onclick="return removeFavorite('<?php echo $this->_tpl_vars['member']['username']; ?>
','favorite-list-container')"><span>fav</span></a>			
								<?php endif; ?>
                				<?php if ($this->_tpl_vars['item']['username'] != $_SESSION['sess_username']): ?>
									<a href="?action=chat&username=<?php echo $this->_tpl_vars['member']['username']; ?>
" class="q-icon q-right q-chat" title="Chat"><span>fav</span></a>
								<?php endif; ?>
                			<?php endif; ?>
                		</li>
                	<?php endforeach; endif; unset($_from); ?>
                </ul>
            </div>
            <div class="container-content-box" style="float:right;">
            	<h1>NEWEST</h1>
                <ul class="container-profile-list">
                	<?php $_from = $this->_tpl_vars['newest_members']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['member']):
?>
                		<li>
                			<a href="?action=viewprofile&username=<?php echo $this->_tpl_vars['member']['username']; ?>
" class="profile-boder"></a><img src="thumbnails.php?file=<?php echo $this->_tpl_vars['member']['picturepath']; ?>
" width="108" height="108" class="profile-img"/><p><?php echo $this->_tpl_vars['member']['username']; ?>
</p>
                			<?php if ($_SESSION['sess_username'] != ""): ?>
                				<?php if (! in_array ( $this->_tpl_vars['member']['username'] , $this->_tpl_vars['favorites_list'] )): ?>
									<a href="#" class="q-icon q-fav" title="Favorite" onclick="$(this).remove(); return addFavorite('<?php echo $this->_tpl_vars['member']['username']; ?>
','favorite-list-container');"><span>fav</span></a>
								<?php else: ?>
									<a href="#" class="q-icon del-icon-g" title="<?php echo $this->_config[0]['vars']['Delete']; ?>
" onclick="return removeFavorite('<?php echo $this->_tpl_vars['member']['username']; ?>
','favorite-list-container')"><span>fav</span></a>			
								<?php endif; ?>
                				<?php if ($this->_tpl_vars['item']['username'] != $_SESSION['sess_username']): ?>
									<a href="?action=chat&username=<?php echo $this->_tpl_vars['member']['username']; ?>
" class="q-icon q-right q-chat" title="Chat"><span>fav</span></a>
								<?php endif; ?>
                			<?php endif; ?>
                		</li>
                	<?php endforeach; endif; unset($_from); ?>
                </ul>
            </div>
        </div>
        
        <?php if ($_SESSION['sess_username'] != "" && count ( $this->_tpl_vars['favorites'] ) > 0): ?> 
        
        	<div class="container-content-02">
            	<h1>Favoriten</h1>
                <ul class="container-profile-list">
                	<?php $_from = $this->_tpl_vars['favorites']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['member']):
?>
                	<li>
                    <a href="?action=viewprofile&username=<?php echo $this->_tpl_vars['member']['username']; ?>
" class="profile-boder"></a><img src="thumbnails.php?file=<?php echo $this->_tpl_vars['member']['picturepath']; ?>
" width="108" height="108" class="profile-img"/><p><?php echo $this->_tpl_vars['member']['username']; ?>
</p>
					<?php if (! in_array ( $this->_tpl_vars['member']['username'] , $this->_tpl_vars['favorites_list'] )): ?>
						<a href="#" class="q-icon q-fav" title="Favorite" onclick="$(this).remove(); return addFavorite('<?php echo $this->_tpl_vars['member']['username']; ?>
','favorite-list-container');"><span>fav</span></a>
					<?php else: ?>
						<a href="#" class="q-icon del-icon-g" title="<?php echo $this->_config[0]['vars']['Delete']; ?>
" onclick="return removeFavorite('<?php echo $this->_tpl_vars['member']['username']; ?>
','favorite-list-container')"><span>fav</span></a>			
					<?php endif; ?>
                    <a href="#" class="q-icon q-right q-chat"><span>fav</span></a>
                    </li>
                    <?php endforeach; endif; unset($_from); ?>
                </ul>
            </div>
   		<?php endif; ?>
        
        