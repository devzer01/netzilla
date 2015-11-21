<!-- {$smarty.template} -->
<fieldset>
<table border="0" cellpadding="0" cellspacing="5" width="95%">
	<tr>
		<td class="text14grey" align="left"><b>{#View_diary#}</b></td>
	</tr>
	<tr>
		<td height="2px"></td>
	</tr>
	<tr>
		<td  align="center">
		<table border="0" cellpadding="0" cellspacing="5" width="100%" >
			{section name="suggestion" loop=$suggestion_data}
			{if $smarty.section.suggestion.index eq 0}
			<tr align="left">
				<td><b>{$suggestion_data[suggestion].subject}</b></td>
			</tr>
			<tr align="left">
				<td >{$suggestion_data[suggestion].message|replace:"\n":"<br>"}</td>
			</tr>
			{else}
			{assign var=num_diary value=$smarty.section.suggestion.index}
			{/if}
			{/section}
		</table>		
		</td>
	</tr>
</table>
</fieldset>
{if $num_diary gt 0}
<br>

	<fieldset>
	<table border="0" cellpadding="0" cellspacing="5" width="100%">
	<tr align="left">
		<td class="text14grey"><b>{#My_diary#}</b></td>
	</tr>
	<tr>
		<td align="center">
		<table border="0" bordercolor="#666666" cellpadding="5" cellspacing="0" width="100%" align="center">
			{section name="suggestion" loop=$suggestion_data}
			{if $smarty.section.suggestion.index ne 0}
			<tr  bgcolor="{cycle values="#006de0,#003873"}" height="25">
				<td style="padding-left:10px;"><a href="?action=suggestion_box&do=view_suggestion&id={$suggestion_data[suggestion].id}" class="link-inrow">{$suggestion_data[suggestion].subject|truncate:70:"..."}</a></td>
			</tr>{/if}
			{/section}
		</table>
		</td>
	</tr>
	</table>	
	</fieldset>
{/if}