<?php /* Smarty version 2.6.14, created on 2013-11-18 16:55:12
         compiled from register.tpl */ ?>
<!-- <?php echo 'register.tpl'; ?>
 -->
<?php if ($this->_tpl_vars['text']): ?>
<div style="line-height:20px; width:auto; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px;
background:url(images/cm-theme/bg-opd-r.png); padding:10px; text-align:center; border:2px solid rgba(0,0,0,0.1); color:#FFF;">
<?php echo $this->_tpl_vars['text']; ?>

</div>
<?php endif; ?>

<?php if (( $_GET['action'] == 'register' )): ?>
<!--only register page -->
<div id="container-content-profile-home">
    <div class="container-view-profile-register" style="margin-left:10px;">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "regis-step1.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </div>
    <div style="float:left; margin-left:70px; margin-top:20px; position:relative; z-index:99;">
        <?php if ($_SESSION['sess_username'] != "" || $_COOKIE['sess_username'] != ""): ?>
    <?php if ($_SESSION['sess_username']): ?>
        <!--Html -->
    <?php endif; ?>
    <?php else: ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "left-notlogged.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>
    </div>
</div>
<!--end only register page -->
<?php else: ?>
<div class="container-view-profile-register">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "regis-step1.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
<?php endif; ?>