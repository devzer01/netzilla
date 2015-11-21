<div class="result-box">
	<h1>{#Lonely_Heart_Ads#}</h1>
	<div class="result-box-inside-nobg">
		<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
		{if $smarty.get.action eq "lonely_heart_ads"}
		<tr align="left">
			<td>
            	{#lonely_heart_ad_txt_1#}
                <br /><br />
				{#lonely_heart_ad_txt_2#}
			</td>
		</tr>
		{else if $smarty.get.action eq "lonely_heart_ads_view"}
        <tr align="left">
        	<td>{#lonely_heart_ad_txt_view#}</td>
        </tr>
		{/if}

		{if $smarty.get.action eq "lonely_heart_ads"}
		<tr>
			<td style="padding-top:20px;" align="center">
				<form id="lonely_heart_write_form" name="lonely_heart_write_form" method="post" action="{$url}">
				<table width="500" align="center" cellpadding="2" cellspacing="1" border="0">
				<tr>
					<td width="120" align="right" style="padding:0 10px 10px 10px"><b>{#Target_group#}:</b></td>
					<td align="left">{html_options id="taget" name="target" options=$targetGroup style="width:205px "}</td>
				</tr>
				<tr>
					<td width="120" align="right" style="padding:0 10px 10px 10px"><b>{#Category#}:</b></td>
					<td align="left">{html_options id="category" name="category" options=$category style="width:205px "}</td>
				</tr>
				<tr>
					<td width="120" align="right" style="padding:0 10px 10px 10px"><b>{#Headline#}:</b></td>
					<td align="left"><input name="headline" type="text" id="headline" maxlength="100" style="width:300px" class="input" /></td>
				</tr>
				<tr>
					<td width="120" valign="top" align="right" style="padding:0 10px 10px 10px"><b>{#Text#}:</b></td>
					<td align="left">
						<textarea id="text" name="text" style="width:300px; height: 150px" onKeyDown="limitText(this.form.text,this.form.countdown,800);"
onKeyUp="limitText(this.form.text,this.form.countdown,800);"></textarea>
						<font style="line-height:26px;">({#LHD_MAX#})</font>
						<br>
						<input readonly type="text" name="countdown" size="3" value="800"> {#SMS_LEFT#}
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td align="left" style="padding:10px"><input type="hidden" id="submit_hidden" name="submit_hidden" value="submit" />
						<input type="button" id="send_button" name="send_button" onclick="if(checkWriteLonely()) $('lonely_heart_write_form').submit();" value="{#lonely_heart_ad_Write_Button#}" class="button" />
					</td>
				</tr>
				</table>
				</form>		
			</td>
		</tr>
		<tr><td height="10"></td></tr>
		{/if}

		{if $lonely_heart}
		<form id="lonely_heart_form" name="lonely_heart_form" method="post" action="">
		<tr><td height="10"></td></tr>
		<tr>
			<td>
				{if $smarty.get.action eq "lonely_heart_ads_view"}
				<div class="result-box-inside">
					<div style=" display:block; float:left; margin-right:10px; width:80px;">
					{if $lonelyProfile.picturepath !=""}
					<a href="?action=viewprofile&username={$lonelyProfile.username}" class="linklist"><img src="thumbnails.php?file={$lonelyProfile.picturepath}&w=78&h=104" height="104" width="78" border="0"  class="listimg"></a>
					{else}
					<a href="?action=viewprofile&username={$lonelyProfile.username}" class="linklist"><img src="thumbs/default.jpg" height="104" width="78" border="0"  class="listimg"></a>
					{/if}
					</div>
					<div style="display:block; float:left; text-align:left; margin-right:10px; width:280px;">
					{#Name#}: {$lonelyProfile.username|regex_replace:"/@.*/":""}<br />
					{#City#}: {$lonelyProfile.city}<br />
					{#Civil_status#}: {$lonelyProfile.civilstatus}
					</div>
					<div style=" display:block; float:left; margin-right:10px; width:280px; text-align:left;">
					{assign var="year" value=$lonelyProfile.birthday|date_format:"%Y"}  
					{assign var="Age" value="`$thisyear-$year`"}
					{#Age#}:  {$Age} {#Year#}<br />
					{#Appearance#}: {$lonelyProfile.appearance}<br />
					{#Height#}: {$lonelyProfile.height}<br />
					</div>
					<div style=" display:block; float:left; margin-right:10px; width:560px; text-align:left;">
					{#Description#}: {$lonelyProfile.description|nl2br|truncate:40:"...":true}
					</div>
				</div>
                {section name="lonely_heart" loop=$lonely_heart}
					{******************}
                        {include file="ads_box.tpl" datas=$lonely_heart[lonely_heart]}
                    {******************}
				{/section}
				{if $smarty.get.backurl eq "index"}
					<a href="?action=viewprofile&username={$lonelyProfile.username}" class="button">{#Member_profile#}</a>
					<a href="index.php" class="button">{#BACK#}</a>
				{/if}
                {if $smarty.get.backurl eq "viewprofile" or $smarty.get.backurl eq "viewmessage"}
					<a href="?action=viewprofile&username={$lonelyProfile.username}" class="button">{#Member_profile#}</a>
					<a href="javascript:void(0)" onclick="history.go(-1);" class="button">{#BACK#}</a>
				{/if}
				{else}
                <table border="0" cellpadding="2" cellspacing="1" width="100%" align="center">
                <tr bgcolor="#b6b6b6" height="28px">
                    <th width="35px" class="text-title">{#Index#}</th>
                    <th width="60px" class="text-title">{#Target#}</th>
                    <th width="90px" class="text-title">{#Category#}</th>
                    <th width="200px" class="text-title">{#Headline#}</th>
                    <th width="100px" class="text-title">{#Datetime#}</th>
                    {if $smarty.get.action eq "lonely_heart_ads"}
                    <th width="20px" class="text-title">{#Edit#}</th>
                    <th width="20px"><a href="#" class="sitelink" onclick="if(confirm('Are you sure to delete selected lonely heart?')) deleteLonelyHeart('lonely_heart_form'); else return false;">{#Delete#}</a></th>
					{/if}
                </tr>
                {section name="lonely_heart" loop=$lonely_heart}
                <tr bgcolor="{cycle values='#663333,#996666'}">
                    <td align="center">{$smarty.section.lonely_heart.index+1}</td>
                    <td style="padding-left:10px;">{$lonely_heart[lonely_heart].target}</td>
                    <td style="padding-left:10px;">{$lonely_heart[lonely_heart].category}</td>
                    {if $smarty.get.action eq "lonely_heart_ads"}
                    <td style="padding-left:10px;">
						<a href="?action=lonely_heart_ads&do=view&lonelyid={$lonely_heart[lonely_heart].id}" class="link-inrow lightview" rel="iframe">
							{$lonely_heart[lonely_heart].headline|stripslashes|truncate:40:"..."}
						</a>
					</td>
                    {elseif $smarty.get.action eq "lonely_heart_ads_view"}
                    <td style="padding-left:10px;">
						<a href="?action=lonely_heart_ads_view&do=view&lonelyid={$lonely_heart[lonely_heart].id}&username={$smarty.get.username}" class="link-inrow" onclick="return GB_showCenter('', this.href, 190, 730);">
							{$lonely_heart[lonely_heart].headline|truncate:40:"..."}
						</a>
					</td>
                    {/if}
                    <td style="padding-left:10px;">{$lonely_heart[lonely_heart].datetime}</td>
                    {if $smarty.get.action eq "lonely_heart_ads"}
                    <td align="center"><a href="?action=lonely_heart_ads&do=edit&lonelyid={$lonely_heart[lonely_heart].id}"><img border="0" src="images/icon/b_edit.png" /></a></td>
                    <td align="center"><input type="checkbox" id="lonely_heart_id" name="lonely_heart_id[]" value="{$lonely_heart[lonely_heart].id}"></td>
                    {/if}
                </tr>
                {/section}
                </table>
				{/if}
			</td>
		</tr>
		</form>
		{/if}
		</table>
</div>
{if $lonely_heart}<div class="pagein">{#page#} {paginate_prev} {paginate_middle} {paginate_next}</div>{/if}
</div>