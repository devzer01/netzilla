<!-- {$smarty.template} -->
<label class="text-admin"></label>
<span style="float:left;"><b>STEP 3</b></span>
<br class="clear" />
<label class="text-admin"><u>Looking for:</u></label><br clear="all" />
<br class="clear" />
<label class="text-admin">Men:</label>
<span style="float:left;">
{if $save.lookmen}
{assign var="selected_lookmen" value=$save.lookmen}
{else}
{assign var="selected_lookmen" value="1"}
{/if}
{html_radios id="lookmen" name="lookmen" options=$yesno selected=$selected_lookmen}
</span>
<br class="clear" />
<label class="text-admin">Women:</label>
<span style="float:left;">
{if $save.lookwomen}
{assign var="selected_lookwomen" value=$save.lookwomen}
{else}
{assign var="selected_lookwomen" value="1"}
{/if}
{html_radios id="lookwomen" name="lookwomen" options=$yesno selected=$selected_lookwomen}
</span>
<br class="clear" />
<label class="text-admin">Min age:</label>
<span> 
{if $save.minage}
{assign var="selected_minage" value=$save.minage}
{else}
{assign var="selected_minage" value="18"}
{/if}
{html_options id="minage" name="minage" options=$age onchange="ageRange("minage", "maxage")" selected=$selected_minage class="formfield_01"}
</span>
<br class="clear" />
<label class="text-admin">Max age:</label>
<span> 
{if $save.maxage}
{assign var="selected_maxage" value=$save.maxage}
{else}
{assign var="selected_maxage" value="49"}
{/if}
{html_options id="maxage" name="maxage" options=$age selected=$selected_maxage class="formfield_01"}
</span>
<br class="clear" />
<label class="text-admin">Relationship:</label>
<span style="float:left;">
{html_radios id="relationship" name="relationship" options=$yesno selected=$save.relationship}
</span>
<br class="clear" />
<label class="text-admin">One night stand:</label>
<span style="float:left;">
{html_radios id="onenightstand" name="onenightstand" options=$yesno selected=$save.onenightstand}
</span>
<br class="clear" />
<label class="text-admin">Affair:</label>
<span style="float:left;">
{html_radios id=""affair name="affair" options=$yesno selected=$save.affair}
</span>
<br class="clear" />
<label class="text-admin">Friendship:</label>
<span style="float:left;">
{html_radios id="friendship" name="friendship" options=$yesno selected=$save.friendship}
</span>
<br class="clear" />
<label class="text-admin"><u>Prefenrence:</u></label><br clear="all" />
<br class="clear" />
<label class="text-admin">Cybersex:</label>
<span style="float:left;">
{html_radios id="cybersex" name="cybersex" options=$yesno selected=$save.cybersex}
</span>
<br class="clear" />
<label class="text-admin">Picture swapping:</label>
<span style="float:left;">
{html_radios id="picture_swapping" name="picture_swapping" options=$yesno selected=$save.picture_swapping}
</span>
<br class="clear" />
<label class="text-admin">Live dating:</label>
<span style="float:left;">
{html_radios id="live_dating" name="live_dating" options=$yesno selected=$save.live_dating}
</span>
<br class="clear" />
<label class="text-admin">Role playing:</label>
<span style="float:left;">
{html_radios id="role_playing" name="role_playing" options=$yesno selected=$save.role_playing}
</span>
<br class="clear" />
<label class="text-admin">S&M:</label>
<span style="float:left;">
{html_radios id="s_m" name="s_m" options=$yesno selected=$save.s_m}
</span>
<br class="clear" />
<label class="text-admin">Partner exchange:</label>
<span style="float:left;">
{html_radios id="partner_exchange" name="partner_exchange" options=$yesno selected=$save.partner_exchange}
</span>
<br class="clear" />
<label class="text-admin">Voyeurism:</label>
<span style="float:left;">
{html_radios id="voyeurism" name="voyeurism" options=$yesno selected=$save.voyeurism}
</span>
<br class="clear" />
<label class="text-admin">Description:</label>
<span> 
<textarea id="description" name="description" class="description-edit" style="width:250px;">{$save.description}</textarea>
</span>
<br clear="both"/>
<input type="hidden" name="return_page" value="{if $save.return_page}{$save.return_page}{else}{$smarty.server.HTTP_REFERER}{/if}">
<label class="text-admin"></label> 
<span></span>

<a href="#" onclick="stepWizard('stepPage2', Array('stepPage1', 'stepPage3'))"class="butregisin">BACK</a>
<a href="#" onclick="$('adduser_form').submit()" value="" class="butregisin">FINISH</a>