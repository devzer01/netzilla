{include file='header.tpl'}

<h3>Add Email</h3>

{if $success neq ""}
	<div class="alert alert-success">{$success}</div>
{/if}

<form method='post' action='addemail'>
		<div class="boxcontainner">
			<span class="label2">Email:</span>
			<span class="field">
				<input name="username" id="username" class="box" type="text">
			</span>
		</div>
		<div class="boxcontainner">
			<span class="label2">Password:</span>
			<span class="field">
				<input name="password" id="password" class="box" type="text">
			</span>
		</div>
		<div class="boxcontainner">
			<span class="label2">&nbsp;</span>
				<input name="submit" value="Insert" class="button" type="submit">
			<span class="field">
			</span>
		</div>
</form>

{include file='footer.tpl'}