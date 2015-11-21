<!-- {$smarty.template} -->
<label class="text-admin"></label>
<span style="float:left;"><b>STEP 2</b></span>
<br class="clear" />
<label class="text-admin">Height:</label>
<span> 
{html_options id="height" name="height" options=$height selected=$save.height style="width:155px" class="formfield_01"}
</span>
<br class="clear" />
<label class="text-admin">Weight:</label>
<span>
{html_options id="weight" name="weight" options=$weight selected=$save.weight style="width:155px" class="formfield_01"}
</span>
<br class="clear" />
<label class="text-admin">Appearance:</label>
<span>
{html_options id="appearance" name="appearance" options=$appearance selected=$save.appearance class="formfield_01"}
</span>
<br class="clear" />
<label class="text-admin">Eyes color:</label>
<span>
{html_options id="eyescolor" name="eyescolor" options=$eyescolor selected=$save.eyescolor class="formfield_01"}
</span>
<br class="clear" />
<label class="text-admin">Hair color:</label>
<span>
{html_options id="haircolor" name="haircolor" options=$haircolor selected=$save.haircolor class="formfield_01"}
</span>
<br class="clear" />
<label class="text-admin">Hair length:</label>
<span>
{html_options id="hairlength" name="hairlength" options=$hairlength selected=$save.hairlength class="formfield_01"}
</span>
<br class="clear" />
<label class="text-admin">Beard:</label>
<span>
{html_options id="beard" name="beard" options=$beard selected=$save.beard class="formfield_01"}
</span>
<br class="clear" />
<label class="text-admin">Zodiac:</label>
<span>
{html_options id="zodiac" name="zodiac" options=$zodiac selected=$save.zodiac class="formfield_01"}
</span>
<br class="clear" />
<label class="text-admin">Civil status:</label>
<span>
{html_options id="civilstatus" name="civilstatus" options=$status selected=$save.civilstatus  class="formfield_01"}
</span>
{if $save.sexuality}
{assign var="selected_sexuality" value=$save.sexuality}
{else}
{assign var="selected_sexuality" value="2"}
{/if}
<br class="clear" />
<label class="text-admin">Sexuality:</label>
<span>
{html_options id="sexuality" name="sexuality" options=$sexuality selected=$selected_sexuality class="formfield_01"}
</span>
<br class="clear" />
<label class="text-admin">Tattos:</label>
<span style="float:left;">
{html_radios id="tattos" name="tattos" options=$yesno selected=$save.tattos}
</span>
<br class="clear" />
<label class="text-admin">Smoking:</label>
<span style="float:left;">
{html_radios id="smoking" name="smoking" options=$yesno selected=$save.smoking}
</span>
<br class="clear" />
<label class="text-admin">Glasses:</label>
<span style="float:left;">
{html_radios id="glasses" name="glasses" options=$yesno selected=$save.glasses}
</span>
<br class="clear" />
<label class="text-admin">Piercings:</label>
<span style="float:left;">
{html_radios id="piercings" name="piercings" options=$yesno selected=$save.piercings}
</span><br clear="both"/>
<label class="text-admin"></label> 
<span></span>
<a href="#" onclick="stepWizard('stepPage1', Array('stepPage2', 'stepPage3'))" class="butregisin">BACK</a>
<a href="#" onclick="stepWizard('stepPage3', Array('stepPage1', 'stepPage2'))" class="butregisin">NEXT</a>