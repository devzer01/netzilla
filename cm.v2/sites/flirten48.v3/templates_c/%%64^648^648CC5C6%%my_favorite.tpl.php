<?php /* Smarty version 2.6.14, created on 2013-11-18 16:51:36
         compiled from my_favorite.tpl */ ?>
<?php echo '
<script>
jQuery(function(){loadFavorite("favorite-list-container", \'';  echo $this->_tpl_vars['style'];  echo '\');});
</script>
'; ?>


<?php if ($this->_tpl_vars['style'] == '2'): ?>
<!--show profile page -->
<div>
<h5 class="title"><?php echo $this->_config[0]['vars']['FAVOURITES']; ?>
</h5>
<span id="favorite-list-container" class="fav-profile-page"></span>
</div>
<!--end show profile page -->
<?php else: ?>
<br class="clear" />
<div id="container-content">
<h1 class="title"><?php echo $this->_config[0]['vars']['FAVOURITES']; ?>
</h1>
<span id="favorite-list-container"></span>
</div>
<?php endif; ?>