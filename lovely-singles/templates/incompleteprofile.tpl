<div class="register-page-box">
<h1>{#Page_Title#}</h1>
<div class="register-page-box-inside">

<div align="center">
<b style="color: red">{$text}</b>
</div>
<br>

{if ($sub_score1 <= 64)}
<p>
	{#Completion_Profile_Txt#}<br><br>
	<a href="?action=editprofile" class="complete-button">{#Completion_Profile_Btn#}</a>
</p>
<br clear="all"><br>
{/if}

{if ($sub_score2 < 10)}
<p>
	{#Completion_Album_Txt#}<br><br>
	<a href="?action=fotoalbum" class="complete-button">{#Completion_Album_Btn#}</a>
</p>
<br clear="all"><br>
{/if}

{if ($sub_score3 < 25)}
<p>
	{#Completion_Ads_Txt#}<br><br>
	<a href="?action=lonely_heart_ads" class="complete-button">{#Completion_Ads_Btn#}</a>
</p>
{/if}
</div>
</div>