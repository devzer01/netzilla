<?php /* Smarty version 2.6.14, created on 2014-01-31 18:38:51
         compiled from right_admin.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'right_admin.tpl', 32, false),)), $this); ?>
<!-- <?php echo 'right_admin.tpl'; ?>
 -->
<?php echo '
<script language="javascript" type="text/javascript">
jQuery(function()
{
	country_select = "';  echo $_GET['co'];  echo '";
	state_select = "';  echo $_GET['s'];  echo '";
	city_select = "';  echo $_GET['ci'];  echo '";
	ajaxRequest("loadOptionCountry", "", "", loadOptionCountry, reportError);
});
</script>
'; ?>

<h2>Admin Search User</h2>
<form id="admin_search_form" name="admin_search_form" method="get">
<div class="qsbox" style="margin-left: 10px">
<label>Username:</label>
<span><input type="hidden" id="action" name="action"  value="<?php echo $_GET['action']; ?>
"/><input type="text" id="search_username" name="u" class="box" value="<?php echo $_GET['u']; ?>
" ></span>
<br class="clear" />
<label>Fake or Real:</label>
<span>
<select name="f" id="f" class="box" >
<option value=""> <?php echo $this->_config[0]['vars']['Any']; ?>
  </option>
<option value="0" <?php if ($_GET['f'] == '0'): ?> selected="selected"<?php endif; ?>>Real</option>
<option value="1" <?php if ($_GET['f'] == '1'): ?> selected="selected"<?php endif; ?>>Fake</option>
</select>
</span>
<br class="clear" />
<label>Gender:</label>
<span>
<select name="g" id="g_gender" class="box" >
<option value=""> <?php echo $this->_config[0]['vars']['Any']; ?>
  </option>
<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['gender'],'selected' => $_GET['g']), $this);?>
 
</select>
</span>
<br class="clear" />
<label>Looking for:</label>
<span>
<select name="lg" id="l_gender" class="box" >
<option value=""> <?php echo $this->_config[0]['vars']['Any']; ?>
  </option>
<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['gender'],'selected' => $_GET['lg']), $this);?>
 
</select>
</span>
<br class="clear" />
<label>Country:</label>
<span>
<select id="country" name="co" onchange="loadOptionState('#state', this.options[this.selectedIndex].value, '');loadOptionCity('#city', 0, '')" class="box"></select>
</span>
<br class="clear" />
<label>State:</label>
<span>
<select id="state" name="s" onchange="loadOptionCity('#city', this.options[this.selectedIndex].value, '')" class="box"></select>
</span>
<br class="clear" />
<label>City:</label>
<span>
<select id="city" name="ci"  class="box"></select>
</span>
<br class="clear" />
<label class="quicktext">Age</label>
<span>
<?php if ($_GET['min_age'] != ""): ?>
<?php $this->assign('select_min_age', $_GET['min_age']); ?>
<?php else: ?>
<?php $this->assign('select_min_age', 18); ?>
<?php endif; ?>
<select name="min_age" id="min_age" onchange="ageRange('min_age', 'max_age')" class="input-quick-select02">
<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['age'],'selected' => $this->_tpl_vars['select_min_age']), $this);?>
  
</select>
To 
<?php if ($_GET['max_age'] != ""): ?>
<?php $this->assign('select_max_age', $_GET['max_age']); ?>
<?php else: ?>
<?php $this->assign('select_max_age', $this->_tpl_vars['select_min_age']+2); ?>
<?php endif; ?>
<select name="max_age" id="max_age" class="input-quick-select02">
<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['age'],'selected' => 99), $this);?>
  
</select>
</span>
<a href="#" onclick="$('admin_search_form').submit(); return false;" class="butregisin">SEARCH</a>

</div>

</form>