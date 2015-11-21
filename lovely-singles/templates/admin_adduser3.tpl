<b>STEP 3</b><br /><br />

<label><u>You're looking for:</u></label><br clear="all" />
<label>Men:</label>
<span> 
{if $save.lookmen}
{assign var="selected_lookmen" value=$save.lookmen}
{else}
{assign var="selected_lookmen" value="1"}
{/if}
{html_radios id="lookmen" name="lookmen" options=$yesno selected=$selected_lookmen}
</span>
<label>Women:</label>
<span> 
{if $save.lookwomen}
{assign var="selected_lookwomen" value=$save.lookwomen}
{else}
{assign var="selected_lookwomen" value="1"}
{/if}
{html_radios id="lookwomen" name="lookwomen" options=$yesno selected=$selected_lookwomen}
</span>
<label>Pairs:</label>
<span> 
{if $save.lookpairs}
{assign var="selected_lookpairs" value=$save.lookpairs}
{else}
{assign var="selected_lookpairs" value="1"}
{/if}
{html_radios id="lookpairs" name="lookpairs" options=$yesno selected=$selected_lookpairs}
</span>
<label>Min Age:</label>
<span> 
{if $save.minage}
{assign var="selected_minage" value=$save.minage}
{else}
{assign var="selected_minage" value="18"}
{/if}
{html_options id="minage" name="minage" options=$age onchange="ageRange("minage", "maxage")" selected=$selected_minage}
</span>

<label>Max Age:</label>
<span> 
{if $save.maxage}
{assign var="selected_maxage" value=$save.maxage}
{else}
{assign var="selected_maxage" value="49"}
{/if}
{html_options id="maxage" name="maxage" options=$age selected=$selected_maxage}
</span>

<label>Relationship:</label>
<span> 
{html_radios id="relationship" name="relationship" options=$yesno selected=$save.relationship}
</span>

<label>One-night stand:</label>
<span> 
{html_radios id="onenightstand" name="onenightstand" options=$yesno selected=$save.onenightstand}
</span>

<label>Affair:</label>
<span> 
{html_radios id=""affair name="affair" options=$yesno selected=$save.affair}
</span>

<label>Friendship and more:</label>
<span> 
{html_radios id="friendship" name="friendship" options=$yesno selected=$save.friendship}
</span>

<label><u>Preference:</u></label><br clear="all" />

<label>Cybersex:</label>
<span> 
{html_radios id="cybersex" name="cybersex" options=$yesno selected=$save.cybersex}
</span>

<label>Picture Swapping:</label>
<span> 
{html_radios id="picture_swapping" name="picture_swapping" options=$yesno selected=$save.picture_swapping}
</span>

<label>Real dating:</label>
<span> 
{html_radios id="live_dating" name="live_dating" options=$yesno selected=$save.live_dating}
</span>

<label>Role playing:</label>
<span> 
{html_radios id="role_playing" name="role_playing" options=$yesno selected=$save.role_playing}
</span>

<label>S and M:</label>
<span> 
{html_radios id="s_m" name="s_m" options=$yesno selected=$save.s_m}
</span>

<label>Partner exchange(clubs):</label>
<span> 
{html_radios id="partner_exchange" name="partner_exchange" options=$yesno selected=$save.partner_exchange}
</span>

<label>Voyeurism:</label>
<span> 
{html_radios id="voyeurism" name="voyeurism" options=$yesno selected=$save.voyeurism}
</span>

<label>Your Description:</label>
<span> 
<textarea id="description" name="description" class="box" style="width:250px;">{$save.description}</textarea>
</span>
<input type="hidden" name="return_page" value="{if $save.return_page}{$save.return_page}{else}{$smarty.server.HTTP_REFERER}{/if}">
<a href="#" onclick="stepWizard('stepPage2', Array('stepPage1', 'stepPage3'))" class="butregisin">BACK</a>
<a href="#" onclick="$('adduser_form').submit()" class="butregisin">FINISH</a>