<!-- {$smarty.template} -->
<h1 class="admin-title">
{if $smarty.get.page eq "terms"}
Terms &amp; Conditions (DE)
{elseif $smarty.get.page eq "terms-2"}
Terms &amp; Conditions (EN)
{elseif $smarty.get.page eq "imprint"}
Imprint
{elseif $smarty.get.page eq "policy"}
Privacy Policy (DE)
{elseif $smarty.get.page eq "policy-2"}
Privacy Policy (EN)
{/if}
</h1>
<div id="trList" style="display:block; margin-top:10px;">


			<form action="" method="post" name="content_form" id="content_form">
				<textarea name="content" style="width: 95%; height: 550px; padding:10px;">{$content}</textarea><br/>
				<a href="javascript: void(0)" onclick="$('content_form').submit();" class="butregisin">Save</a>
			</form>

</div>