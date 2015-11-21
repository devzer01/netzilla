<!-- {$smarty.template} -->
<b>{#STEP#} 2</b><br />
<br />
<label class="text">{#Height#}:</label>
<span> 
{html_options id="height" name="height" options=$height selected=$save.height style="width:155px" onchange="checkNullSelectOption(this)}
</span>
<label class="text">{#Weight#}:</label>
<span>
{html_options id="weight" name="weight" options=$weight selected=$save.weight style="width:155px" onchange="checkNullSelectOption(this)}
</span>
<label class="text">{#Appearance#}:</label>
<span>
{html_options id="appearance" name="appearance" options=$appearance selected=$save.appearance class="box"}
</span>
<label class="text">{#Eyes_Color#}:</label>
<span>
{html_options id="eyescolor" name="eyescolor" options=$eyescolor selected=$save.eyescolor class="box"}
</span>
<label class="text">{#Hair_Color#}:</label>
<span>
{html_options id="haircolor" name="haircolor" options=$haircolor selected=$save.haircolor class="box"}
</span>
<label class="text">{#Hair_Length#}:</label>
<span>
{html_options id="hairlength" name="hairlength" options=$hairlength selected=$save.hairlength class="box"}
</span>
<label class="text">{#Beard#}:</label>
<span>
{html_options id="beard" name="beard" options=$beard selected=$save.beard class="box"}
</span>
<label class="text">{#Zodiac#}:</label>
<span>
{html_options id="zodiac" name="zodiac" options=$zodiac selected=$save.zodiac class="box"}
</span>
<label class="text">{#Civil_status#}:</label>
<span>
{html_options id="civilstatus" name="civilstatus" options=$status selected=$save.civilstatus  class="box"}
</span>
{if $save.sexuality}
{assign var="selected_sexuality" value=$save.sexuality}
{else}
{assign var="selected_sexuality" value="2"}
{/if}
<label class="text">{#Sexuality#}:</label>
<span>
{html_options id="sexuality" name="sexuality" options=$sexuality selected=$selected_sexuality class="box"}

</span>
<label class="text">{#Tattos#}:</label>
<span>
{html_radios id="tattos" name="tattos" options=$yesno selected=$save.tattos}
</span>
<label class="text">{#Smoking#}:</label>
<span>
{html_radios id="smoking" name="smoking" options=$yesno selected=$save.smoking}
</span>
<label class="text">{#Glasses#}:</label>
<span>
{html_radios id="glasses" name="glasses" options=$yesno selected=$save.glasses}
</span>
<label class="text">{#Piercings#}:</label>
<span>
{html_radios id="piercings" name="piercings" options=$yesno selected=$save.piercings}
</span><br clear="both"/>
<a href="#" onclick="stepWizard('stepPage1', Array('stepPage2', 'stepPage3'))" class="butregisin">{#BACK#}</a>
<a href="#" onclick="stepWizard('stepPage3', Array('stepPage1', 'stepPage2'))" class="butregisin">{#NEXT#}</a>