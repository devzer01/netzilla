<!-- {$smarty.template} -->
<label>{#Height#} ({#Cm#}):</label>
<span> 
<input id="height" name="height" type="text" value="{$save.height}" class="box" />
</span>
<label>{#Weight#} ({#Kg#}):</label>
<span>
<input id="weight" name="weight" type="text" value="{$save.weight}" class="box" />
</span>
<label>{#Appearance#}:</label>
<span>
{html_options id="appearance" name="appearance" options=$appearance selected=$save.appearance class="box"}
</span>
<label>{#Eyes_Color#}:</label>
<span>
{html_options id="eyescolor" name="eyescolor" options=$eyescolor selected=$save.eyescolor class="box"}
</span>
<label>{#Hair_Color#}:</label>
<span>
{html_options id="haircolor" name="haircolor" options=$haircolor selected=$save.haircolor class="box"}
</span>
<label>{#Hair_Length#}:</label>
<span>
{html_options id="hairlength" name="hairlength" options=$hairlength selected=$save.hairlength class="box"}
</span>
<label>{#Beard#}:</label>
<span>
{html_options id="beard" name="beard" options=$beard selected=$save.beard class="box"}
</span>
<label>{#Zodiac#}:</label>
<span>
{html_options id="zodiac" name="zodiac" options=$zodiac selected=$save.zodiac class="box"}
</span>
<label>{#Civil_status#}:</label>
<span>
{html_options id="civilstatus" name="civilstatus" options=$status selected=$save.civilstatus  class="box"}
</span>
{if $save.sexuality}
{assign var="selected_sexuality" value=$save.sexuality}
{else}
{assign var="selected_sexuality" value="2"}
{/if}
<label>{#Sexuality#}:</label>
<span>
{html_options id="sexuality" name="sexuality" options=$sexuality selected=$save.sexuality class="box"}
</span>
<label>{#Tattos#}:</label>
<span>
{html_radios id="tattos" name="tattos" options=$yesno selected=$save.tattos}
</span>
<label>{#Smoking#}:</label>
<span>
{html_radios id="smoking" name="smoking" options=$yesno selected=$save.smoking}
</span>
<label>{#Glasses#}:</label>
<span>
{html_radios id="glasses" name="glasses" options=$yesno selected=$save.glasses}
</span>
<label>{#Piercings#}:</label>
<span>
{html_radios id="piercings" name="piercings" options=$yesno selected=$save.piercings}
</span>
<a href="#" onclick="stepWizard('stepPage1', Array('stepPage2', 'stepPage3'))" class="butregisin">{#BACK#}</a>
<a href="#" onclick="stepWizard('stepPage3', Array('stepPage2', 'stepPage1'))" class="butregisin">SKIP</a>
<a href="#" onclick="if(checkNullSignup2())stepWizard('stepPage3', Array('stepPage1', 'stepPage2'))" class="butregisin">{#NEXT#}</a>