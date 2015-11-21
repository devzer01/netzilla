<?php /* Smarty version 2.6.14, created on 2013-11-29 14:45:22
         compiled from bonusverify_step1.tpl */ ?>
<div style="float:left; width:770px; margin-left:10px;">
	<h1 class="title"><?php echo $this->_config[0]['vars']['Bonus_step1_title']; ?>
</h1>
    <div class="container-bonuscode" style="width:740px">
        <div class="result-box-inside">
        	<p><?php echo $this->_config[0]['vars']['Bonus_step1_content']; ?>
</p>
            <div style="margin-top:10px">
            <div style=" margin:0 auto 10px auto; width:214px;"><a href="?action=bonusverify" class="btn-red" onclick="showBonusBox(); return false;"><?php echo $this->_config[0]['vars']['Bonus_step1_button']; ?>
</a></div>
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
<?php echo '
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
	jQuery(\'#mask\').css({\'width\':maskWidth,\'height\':maskHeight});
	
	//transition effect		
	//$(\'#mask\').fadeIn(1000);	
	jQuery(\'#mask\').fadeTo("fast",0.8);	

	//Get the window height and width
	var winH = jQuery(window).height();
	var winW = jQuery(window).width();
		  

	//Set the popup window to center
	jQuery(\'#dialogBonus\').css(\'top\',  winH/2-jQuery(\'#dialogBonus\').height()/2);
	jQuery(\'#dialogBonus\').css(\'left\', winW/2-jQuery(\'#dialogBonus\').width()/2);

	//transition effect
	jQuery(\'#dialogBonus\').fadeIn(1500);
}

function coinsBalance()
{
	jQuery.ajax(
	{
		type: "GET",
		url: "?action=chat&type=coinsBalance",
		success:(function(result)
			{
				jQuery(\'#coinsArea\').text(result);
			})
	});
}
'; ?>

</script>