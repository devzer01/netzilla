<!-- {$smarty.template} -->
<table width="517" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>
		<table width="517" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="105" style="cursor:pointer" onclick="alert('External')">
				{if $datas.picturepath !=""}			
					<table width="100" height="114" cellspacing="1" cellpadding="0" border="0" bgcolor="#9F5CC0" class="border">
					 <tr>			  
					   <td><img src="{$datas.site_url}thumbnails.php?file={$datas.picturepath}&w=100&h=114" width="100" height="114" border="0"></td>				
					 </tr>
					 </table>
				  {else}
					  <img src="thumbs/default.jpg" class="border" border="0">
				  {/if} 
			</td>
			<td width="18"></td>
			<td valign="top">
				<table width="100%" cellspacing="1" cellpadding="1" border="0">
				<tr>
					<td align="left" width="50%"><span class="text12whitebold">{#Name#}:</span>&nbsp;<span class="subj12">{$datas.username|regex_replace:"/@.*/":""}</span></td>
					<td align="left" width="50%"><span class="text12whitebold">{#City#}:</span>&nbsp;<span class="subj12">{$datas.city}</span></td>
				</tr>
				<tr>				
					<!--<td align="left" width="50%"><span class="text12whitebold">{#Area#}:</span>&nbsp;<span class="subj12">{$datas.area}</span></td>-->
					<td align="left" width="50%"><span class="text12whitebold">{#Civil_status#}:</span>&nbsp;<span class="subj12">{$datas.civilstatus}</span></td>
				</tr>
				{assign var="thisY" value=$datas.birthday|date_format:"%Y"}  
				{assign var="Age" value="`$year-$thisY`"}
				<tr>
					<td align="left" width="50%"><span class="text12whitebold">{#Age#}:</span>&nbsp;<span class="subj12">{$Age} {#Year#}</span></td>
					<td align="left" width="50%"><span class="text12whitebold">{#Appearance#}:</span>&nbsp;<span class="subj12">{$datas.appearance}</span></td>
				</tr>
				<tr>
					<td align="left" width="50%"><span class="text12whitebold">{#Height#}:</span>&nbsp;<span class="subj12">{$datas.height}</span></td>
					<td align="left" width="50%">&nbsp;</td>
					
				</tr>
				<tr>
					<td align="left" colspan="2"><span class="text12whitebold">{#Description#}:</span>&nbsp;<span class="subj12">{$datas.description|nl2br|truncate:50:"...":true}</span></td>
				</tr>
				<tr><td height="10"></td></tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>