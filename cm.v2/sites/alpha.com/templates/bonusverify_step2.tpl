<!-- {$smarty.template} -->
<div class="container-metropopup">
<div class="metropopup-content">

	<font style="font-size:2em; padding-bottom:2%; display:block;">{#Bonus_step2_title#}</font>

        <form id="bonusverify" name="bonusverify" method="post" action="" onsubmit="submitBonus(); return false">
            <div align="center"><b style="color: red">{$text}</b></div>
            <div style=" margin-bottom:10px;">{#Bonus_verify_Txt#}</div>
            <input id="bonus_ver_code" name="bonus_ver_code" type="text" style="width:300px;" class="formfield_01" placeholder="{#Fill_Verify#}" />
            <input type="hidden" name="act" value="bonusverify" />
            <input type="hidden" name="submit_hidden" value="1" />		
            <a href="#" onclick="submitBonus(); return false" class="btn-popup">{#SUBMIT#}</a>
        </form>
<br class="clear" />

</div>
</div>

<script>
{literal}
function submitBonus()
{
	if(!submittingBonus)
	{
		submittingBonus = true;
		jQuery.ajax({ type: "POST", url: "?action=bonusverify", data: jQuery("#bonusverify").serialize(), success:(function(result){submittingBonus = false; if(result=="FINISHED") {jQuery('#mask').hide(); jQuery('.window').hide(); jQuery('#bonusverify_box').remove(); coinsBalance();}else{alert(result);}}) });
	}
}
{/literal}
</script>