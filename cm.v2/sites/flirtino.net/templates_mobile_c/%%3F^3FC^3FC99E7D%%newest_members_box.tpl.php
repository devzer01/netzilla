<?php /* Smarty version 2.6.14, created on 2014-01-06 12:47:54
         compiled from newest_members_box.tpl */ ?>
<div class="container-content-box" style="float:right;">
	<h1><?php echo $this->_config[0]['vars']['Newest_main']; ?>
</h1>
	<span id='newest-result-container'></span>
</div>
<script>
<?php echo '
jQuery(function(){
	jQuery.get("",{"action": "search", "type": "searchNewestMembers"';  if ($this->_tpl_vars['total']): ?>, "total": <?php echo $this->_tpl_vars['total'];  endif;  echo '}, function(data){jQuery(\'#newest-result-container\').parent().show();if(data){ jQuery(\'#newest-result-container\').html(data)}else{jQuery(\'#newest-result-container\').html("'; ?>
<div align='center' style='padding:10px;'><?php echo $this->_config[0]['vars']['NoResult']; ?>
</div><?php echo '")}});
	return false;
	});
'; ?>

</script>