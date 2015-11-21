<?php /* Smarty version 2.6.14, created on 2014-01-06 12:47:54
         compiled from online.tpl */ ?>
<div class="container-content-box" style="float:left;">
	<h1>Online</h1>
	<span id='online-result-container'></span>
</div>
<script>
<?php echo '
jQuery(function(){
	jQuery.get("",{"action": "search", "type": "searchOnline", "total": ';  echo $this->_tpl_vars['total'];  echo '}, function(data){jQuery(\'#online-result-container\').parent().show();if(data){ jQuery(\'#online-result-container\').html(data)}else{jQuery(\'#online-result-container\').html("'; ?>
<div align='center' style='padding:10px;'><?php echo $this->_config[0]['vars']['NoResult']; ?>
</div><?php echo '")}});
	return false;
	});
'; ?>

</script>