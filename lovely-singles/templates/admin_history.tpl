<div class="result-box">
<h1>{#HISTORY#}</h1>
{if $display eq "search_form"}
<div class="result-box-inside-nobg">

			<table align="center" border="0" cellpadding="0" cellspacing="10" width="80%">
			<form method=post action="?action=admin_history&history_action=display_search_result">
			
			<tr>
				<td align="right" width="50%">Member name (even partial name) enter:</td>
				<td  align="center" width="30%">
					<input name="user_name" type="text" size="30" class="input">
				</td>
				<td align="left" >
					<input type="button" id="submit_button" name="submit_button" onclick="document.form.submit();" value="{#SEND#}">

				</td>
			</tr>
			</form>
			</table>
</div>

		{elseif $display eq "history_list"}
<div class="result-box-inside-nobg">
				<table border="0" cellpadding="2" cellspacing="1" width="100%">
				<tr bgcolor="#b6b6b6" height="28px">
					<th width="160px"  class="text-title">{#User#}User</th>
					<th width="140px"  class="text-title">Start Date</th>
					<th width="140px"  class="text-title">End Date</th>
					<th  class="text-title">Membership Type</th>
				</tr>
					{foreach from=$users item="user"}
						<tr  bgcolor="{cycle values="#663333,#996666"}" height="24">
							<td width="160px" style="padding-left:10px;">
								{$user.username}
							</td>
							<td align="center" width="160px">
								{$user.start_date}
							</td>
							<td align="center" width="160px">
								{$user.end_date}
							</td>
							<td align="center">
								{if $user.membership_type eq "2"}
									Gold
								{elseif $user.membership_type eq "1"}
									Silver
								{else}
									Bronze
								{/if}
							</td>
						</tr>
					{/foreach}
				</table>
                </div>
			<div class="page">{paginate_prev} {paginate_middle} {paginate_next}</div>
		{/if}

</div>