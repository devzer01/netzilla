<?php /* Smarty version 2.6.14, created on 2013-11-18 06:33:42
         compiled from chat_contact.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'chat_contact.tpl', 4, false),)), $this); ?>
	<div id="contactListArea">
		<ul id="contactList" class="container-profile-chat">
		<?php $_from = $this->_tpl_vars['contactList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['contact']):
?>
			<li onclick="loadMessagesHistory('<?php echo $this->_tpl_vars['contact']['username']; ?>
','undefined', 'part2');" class="message_contact <?php if ($this->_tpl_vars['contact']['count'] > 0): ?>active<?php endif; ?>" id="contactList-<?php echo ((is_array($_tmp=$this->_tpl_vars['contact']['username'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '') : smarty_modifier_replace($_tmp, ' ', '')); ?>
">
				<a href="javascript:void(0)">
					<div class="profile-list-most">
						<div class="boder-profile-img-most"><img src="images/cm-theme/profile-boder-img.png" width="88" height="89" /></div>
						<div class="img-profile-most">
							<img src="thumbnails.php?file=<?php echo $this->_tpl_vars['contact']['picturepath']; ?>
&w=72&h=73" width="72" height="73"/>
						</div>
					</div>
				</a>
                <div class="container-quick-icon">
				<a href="#" onclick="if(confirm('Bist du sicher, dass du deinen Chatpartner aus deiner Chat-Übersicht entfernen willst?')) deleteContact('<?php echo $this->_tpl_vars['contact']['username']; ?>
'); return false;" class="quick-icon-right del-icon" style=" margin-right:2px;" title="Delete"></a>
			</div>
			</li>
			
		<?php endforeach; endif; unset($_from); ?>
		</ul>
	</div>
<!-- <div class="container-chat-right">
<div class="container-chat-history" id="messagesArea">

    <div class="container-history-left">
    <h1><strong>zerocoolz</strong> [2013-10-24 17:14:04]</h1>
    <p>
    Lorem ipsum dolor sit amet,dscdscvdsvdvsvd df<br /> sdv sdgvsdv dsv <br />dsv sdv sf sdf s<br /> dsf dsf s sd sd sdfsdfsdf f<a href="#">consectetur adipisicing</a></p>
    </div>
    
    <div class="container-history-right">
    <h1><strong>boyanoss</strong> [2013-10-24 17:14:04]</h1>
    <p>
    Lorem ipsum dolor sit amet, <a href="#">consectetur adipisicing elit, sed do eiusmod tempor incididunt</a> ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
    </p>
    </div>
    
    <div class="container-history-left">
    <h1><strong>zerocoolz</strong> [2013-10-24 17:14:04]</h1>
    <p>Lorem ipsum dolor sit amet,dscdscvdsvdvsvd</p>
    </div>
    
    <div class="container-history-left">
    <h1><strong>zerocoolz</strong> [2013-10-24 17:14:04]</h1>
    <p>
    Lorem ipsum dolor sit amet,dscdscvdsvdvsvd df<br /> sdv sdgvsdv dsv <br />dsv sdv sf sdf s<br /> dsf dsf s sd sd sdfsdfsdf f</p>
    </div>
    
    <div class="container-history-right">
    <h1><strong>boyanoss</strong> [2013-10-24 17:14:04]</h1>
    <p>
    Lorem ipsum dolor sit amet, <a href="#">consectetur adipisicing elit, sed do eiusmod tempor incididunt</a> ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
    </p>
    </div>
    
    <div class="container-history-left">
    <h1><strong>zerocoolz</strong> [2013-10-24 17:14:04]</h1>
    <p>Lorem ipsum dolor sit amet,dscdscvdsvdvsvd</p>
    </div>
    
    <div class="container-history-left">
    <h1><strong>zerocoolz</strong> [2013-10-24 17:14:04]</h1>
    <p>
    Lorem ipsum dolor sit amet,dscdscvdsvdvsvd df<br /> sdv sdgvsdv dsv <br />dsv sdv sf sdf s<br /> dsf dsf s sd sd sdfsdfsdf f</p>
    </div>
    
    <div class="container-history-right">
    <h1><strong>boyanoss</strong> [2013-10-24 17:14:04]</h1>
    <p>
    Lorem ipsum dolor sit amet, <a href="#">consectetur adipisicing elit, sed do eiusmod tempor incididunt</a> ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
    </p>
    </div>
    
    <div class="container-history-left">
    <h1><strong>zerocoolz</strong> [2013-10-24 17:14:04]</h1>
    <p>Lorem ipsum dolor sit amet,dscdscvdsvdvsvd</p>
    </div>
    
</div>
</div> -->









<script type="text/javascript">
var crc=<?php echo $this->_tpl_vars['crc']; ?>
;
</script>