<!-- {$smarty.template} -->
<div class="result-box">
	<h1>{#SENDCARD#}</h1>
	<div class="result-box-inside">
        <div class="ecard-complete">
        <span class="ecard-complete-text">{#Thankyou#}</span>
        </div>
		<img src="{$card.cardtmp}" style="width:300px; border:4px solid #FFFFFF; float:left; margin-right:20px;"/>
        <!--<div style="font-size:14px; font-weight:bold;">{#Thankyou#}</div>-->
		<div style="display:block; width:350px; float:left">
        <span style="display:block; float:left; font-weight:bold; width:100px;">{#Email#}:</span> <span style="display:block; float:left; width:250px;">{$card.card.to}</span>
		<span style="display:block; float:left; font-weight:bold; width:100px;">{#Subject#}:</span> <span style="display:block; float:left; width:250px;">{$card.card.subject}</span>
		<span style="display:block; float:left; font-weight:bold; width:100px;">{#Message#}:</span> <span style="display:block; float:left; width:250px;">{$card.card.message}</span>
        <br clear="all"/><br />
        <a href="?action=mymessage" class="button">{#BACK#}</a>
        </div>
	</div>
</div>