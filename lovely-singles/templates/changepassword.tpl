<div class="register-page-box">
	<h1>{#Change_Password#}</h1>
	<div class="register-page-box-inside" style="overflow:hidden;">
		<div class="container-edit-profile">
		<form id="editProfile" enctype="multipart/form-data" name="editProfile" method="post" action="">
			<div class="container-edit-profile-group">
			{if $error}
			<span style="color: red; text-weight: bold; width: 100%">{$error}</span><br class="clear" /><br/>
			{/if}

			<label>Old {#PASSWORD#}:</label>
			<span><input id="old_password" name="old_password" type="password" value="" style="width:150px" class="input"/></span>
			<br class="clear" />
			<label>New {#PASSWORD#}:</label>
			<span><input id="password" name="password" type="password" value="" style="width:150px" class="input"/></span>

			<label>{#Confirm#}-New {#PASSWORD#}:</label>
			<span><input id="confirm_password" name="confirm_password" type="password" value="" style="width:150px" class="input"/></span>
			<br class="clear" />
			</div>

			<input type="hidden" name="submit_button" value="1" />
			<a href="#" onclick="$('editProfile').submit()" class="butregisin">Submit</a>
			<a href="index.php" class="butregisin">Back</a>
			<br class="clear" />
		</form>
		</div>
	</div>
</div>