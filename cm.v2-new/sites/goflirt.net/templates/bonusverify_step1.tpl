<!-- {$smarty.template} -->
<div id="container-content" class="wrap-bonusverify">
    <h1 class="title">{#Bonus_step1_title#}</h1>
    <div class="container-bonuscode">
        <div class="result-box-inside">
        	<p>{#Bonus_step1_content#}</p>
            <div style="margin-top:10px">
            <div style="width:250px; margin:0 auto;">
            	<a href="?action=bonusverify" class="btn-search" style="width:250px !important;" onclick="showBonusBox(); return false;">{#Bonus_step1_button#}</a>
            </div>
            <br class="clear" />
            </div>
    	</div>
    </div>
</div>

<div id="boxes">
<div id="dialogBonus" class="window">
	<div style="background-color: white; width: 100%"></div>
</div>
</div>

<script>
{literal}
var submittingBonus = false;
jQuery(document).ready(function() {
	if(window.location.hash.replace("#", "")=="bonusverify")
	{
		showBonusBox();
	}
});

function showBonusBox()
{
	var url = "?action=bonusverify";
	jQuery("#dialogBonus").load(url);

	//Get the screen height and width
	var maskHeight = jQuery(document).height();
	var maskWidth = jQuery(window).width();

	//Set heigth and width to mask to fill up the whole screen
	jQuery('#mask').css({'width':maskWidth,'height':maskHeight});
	
	//transition effect		
	//$('#mask').fadeIn(1000);	
	jQuery('#mask').fadeTo("fast",0.8);	

	//Get the window height and width
	var winH = jQuery(window).height();
	var winW = jQuery(window).width();
		  

	//Set the popup window to center
	jQuery('#dialogBonus').css('top',  winH/2-jQuery('#dialogBonus').height()/2);
	jQuery('#dialogBonus').css('left', winW/2-jQuery('#dialogBonus').width()/2);

	//transition effect
	jQuery('#dialogBonus').fadeIn(1500);
}

function coinsBalance()
{
	jQuery.ajax(
	{
		type: "GET",
		url: "?action=chat&type=coinsBalance",
		success:(function(result)
			{
				jQuery('#coinsArea').text(result);
			})
	});
}
{/literal}
</script>