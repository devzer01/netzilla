<?php /* Smarty version 2.6.14, created on 2013-11-20 10:56:53
         compiled from search.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'search.tpl', 63, false),)), $this); ?>
<!-- <?php echo 'search.tpl'; ?>
 -->
<script>
<?php echo '
jQuery(document).ready(function($) {
	window.onhashchange = function () {
		loadByHash();
	}

	if(window.location.hash.replace("#", "")!="")
		loadByHash();
	else
		jQuery(\'#search_online_button\').trigger(\'click\');
});

function loadByHash()
{
	if(window.location.hash.replace("#", "")!="")
	{
		var data = window.location.hash.replace("#", "");
		jQuery.get("",data, searchResultHandle);
	}
}

function doSearch()
{
	window.location.hash=\'#\'+arguments[0];
	return false;
}

function page(src)
{
	return doSearch(src.href.substring(src.href.indexOf("?") + 1));
}

function searchResultHandle(data)
{
	jQuery(\'#search-result-container\').parent().show();
	if(data)
	{
		jQuery(\'#search-result-container\').html(data);
	}
	else
	{
		jQuery(\'#search-result-container\').html("'; ?>
<div align='center' style='padding:10px;'><?php echo $this->_config[0]['vars']['NoResult']; ?>
</div><?php echo '");
	}
}
'; ?>

</script>

<!--icon search -->
<div class="container-search-box">
            <div class="container-search-form">
            	<h1><?php echo $this->_config[0]['vars']['Search']; ?>
</h1>
            	<label>Benutzername:</label>
            	<input name="" type="text" class="formfield_01" style=" width:143px; margin-right:10px"/>
                <a href="#" onclick="return doSearch('action=search&type=searchUsername&username='+jQuery('#username').val())" class="btn-search" style="width:60px; margin-right:10px;">Suche</a>
                <form id="search_form">
					<input name="action" type="hidden" value="search" id="action"/>
					<input name="type" type="hidden" value="searchMembers" id="type"/>
                	<label style="width:90px !important;"><?php echo $this->_config[0]['vars']['Gender']; ?>
:</label>
                	<select name="q_gender" id='q_gender' class="formfield_01" style=" width:85px; margin-right:10px">
                		<option value=""><?php echo $this->_config[0]['vars']['Any']; ?>
</option>
						<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['gender']), $this);?>

                	</select>
                	
	                <label style="width:70px !important;"><?php echo $this->_config[0]['vars']['Have_Photo']; ?>
:</label>
	                <select name="q_picture" id='q_picture' class="formfield_01" style=" width:86px; margin-right:10px">
	                	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['picyesno'],'selected' => $_SESSION['right_search']['q_picture']), $this);?>

	                </select>
	                <br class="clear" />
	                
                <label><?php echo $this->_config[0]['vars']['Age']; ?>
:</label>
                
                <?php if ($_SESSION['right_search']['q_minage'] != ""): ?>
					<?php $this->assign('select_q_minage', $_SESSION['right_search']['q_minage']); ?>
				<?php else: ?>
					<?php $this->assign('select_q_minage', 18); ?>
				<?php endif; ?>
                
                <select name="q_minage" id='q_minage' class="formfield_01" onchange="ageRange('#q_minage', '#q_maxage')" style=" width:93px; margin-right:10px">
                	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['age'],'selected' => $this->_tpl_vars['select_q_minage']), $this);?>

                </select>
                
                 <?php if ($_SESSION['right_search']['q_maxage'] != ""): ?>
					<?php $this->assign('select_q_maxage', $_SESSION['right_search']['q_maxage']); ?>
				<?php else: ?>
					<?php $this->assign('select_q_maxage', $this->_tpl_vars['select_q_minage']+2); ?>
				<?php endif; ?>
                
                	<label style="width:30px !important;">bis</label>
                <select name="q_maxage" id='q_maxage' class="formfield_01" style=" width:93px; margin-right:10px">
                	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['age'],'selected' => $this->_tpl_vars['select_q_maxage']), $this);?>
  
                </select>
                
                
                <label style="width:90px !important;"><?php echo $this->_config[0]['vars']['Country']; ?>
:</label>
                <select id="q_country" name="country" onchange="loadOptionState('#q_state', this.options[this.selectedIndex].value, ''); if((jQuery(this).val()!=1)) jQuery('#state_span').hide(); else jQuery('#state_span').show();" name="" class="formfield_01" style=" width:250px; margin-right:10px"></select>
                <br class="clear" />
				<span id="state_span" style="display: none">
					<select id="q_state" name="state" class="formfield_01" style="width:140px; margin-bottom:10px;">
					</select>
				</span>
				<span style="display: none">
		            <label><?php echo $this->_config[0]['vars']['City']; ?>
:</label>
					<select id="q_city" name="city" class="formfield_01" style="width:140px; margin-bottom:10px;">
					</select>
				</span>
                <label></label><a href="#" onclick="return doSearch($('#search_form').serialize())" class="btn-login">Suche</a>
                <br class="clear" />
                </form>
            </div>
            <div class="container-search-icon">
            	<a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=m&sex=w')" class="search-m"><span>Mann sucht Frau</span></a>
                <a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=w&sex=m')" class="search-w"><span>Frau sucht Mann</span></a>
                <a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=m&sex=m')" class="search-mm"><span>Mann sucht Mann</span></a>
                <a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=w&sex=w')" class="search-ww"><span>Frau sucht Frau</span></a>
            </div>
        </div>
<!--end icon search -->
     <br class="clear" />
<div id="container-content" style="display: none;">
<span id='search-result-container'></span>
</div>



<script type="text/javascript">
<?php echo '	
	var Opt = Array();
	function clickInOpt(obj){
		Opt[obj.name] = obj.value; 
	}

	function clickOutOpt(obj){
		Opt[obj.name] = ""; 
		obj.checked=false;
	}

	function chkOpt(obj){
		if(obj.value==Opt[obj.name]){ 
			clickOutOpt(obj); 
		}else{
			clickInOpt(obj); 
		}
	}

	jQuery(function()
	{
		q_country_select = 0;
		q_state_select = 0;
		ajaxRequest("loadOptionCountry", "", "", q_loadOptionCountry, reportError);
	});
'; ?>

</script>