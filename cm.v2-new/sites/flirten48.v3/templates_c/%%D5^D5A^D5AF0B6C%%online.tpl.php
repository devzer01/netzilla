<?php /* Smarty version 2.6.14, created on 2013-11-18 16:51:36
         compiled from online.tpl */ ?>
<span id='online-result-container'></span>
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