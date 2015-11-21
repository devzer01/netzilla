<!-- {$smarty.template} -->
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="78">
	<table width="78" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#79BEE1">
	  <tr>
		<td width="78" height="98" bgcolor="#FFFFFF"> 
		  {if $from_info.picturepath != ''}
		  <img src="{$base_url}thumbs/{$from_info.picturepath}"> 
		  {else}
		  <img src="{$base_url}thumbs/default.jpg" >
		  {/if}							        </td>
	  </tr>
	</table>	</td>
    <td> {#You_got_post_from#} <strong>{$from_info.username}</strong></td>
  </tr>
</table>
