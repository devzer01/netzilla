<div id="trList" style="display:block">
	<div class="result-box">
	<h1>
		{if $smarty.get.page eq "terms"}
			Terms &amp; Conditions (ENG)
		{elseif $smarty.get.page eq "terms-2"}
			Terms &amp; Conditions (GER)
		{elseif $smarty.get.page eq "imprint"}
			Imprint
		{elseif $smarty.get.page eq "policy"}
			Privacy Policy
		{/if}
	</h1>
		<div class="result-box-inside-nobg">
			<form action="" method="post" name="content_form" id="content_form">
				<textarea name="content" style="width: 100%; height: 400px">{$content}</textarea><br/>
				<a href="javascript: void(0)" onclick="$('content_form').submit();" class="butregisin">Save</a>
			</form>
		</div>
	</div>
</div>