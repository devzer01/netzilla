<!-- {$smarty.template} -->
<style>
{literal}
	.picture-item { float: left; margin-right: 5px; margin-bottom: 5px}
	.picture-username { text-align: center; font-size: 12px; color: red; font-weight: bold; overflow: hidden}
	.picture-item div.delete { display:none; text-align: right; margin: -145px 0px 0 0; height: 145px; position: relative; z-index: 100; background-color: #FFFFFF; opacity:0.6; filter:alpha(opacity=60);}
	.picture-item div.delete a {margin: 5px}
	.picture-item:hover div.delete { display:block; }
{/literal}
</style>
<h1 class="admin-title">Manage Pictures</h1>
<div id="trList" style="display:block; margin-top:10px;">

		
			<form action="" method="post">
			{foreach from=$list item="item"}
				<div class="picture-item">
				<img src="thumbnails.php?file={$item.picturepath}&w=129&h=145" width="129" height="145"/>
				<div class="delete"><a href="?action=admin_manage_picture&delete_{$smarty.get.type}_picture={$item.id}" onclick="if(confirm('Are you sure to delete this picture?')) return true; else return false;"><img src="images/icon/b_drop.png"></a></div>
				<div class="picture-username"><input type="checkbox" name="id[]" value="{$item.id}"/> <a href="?action=viewprofile&username={$item.username}&from=admin" class="admin-link">{$item.username}</a></div>
				</div>
			{/foreach}
			<br clear="both"/>
			<div class="page">
				<input type="hidden" name="type" value="{$smarty.get.type}"/>
				<input type="submit" name="submit" value="Delete selected"/>
				{paginate_prev class="pre-pager"} {paginate_middle class="num-pager"} {paginate_next class="next-pager"}
			</div>
			</form>
		
	</div>
</div>