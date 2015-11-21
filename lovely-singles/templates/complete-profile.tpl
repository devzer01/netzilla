<div class="result-box">
<h1>{#Complete_Prof_Titled#}</h1>
<div class="result-box-inside" style="overflow:hidden;"> 
	<div id="container-progress-bar">
		<div class="box-progress-bar">
			<ul>
				<li class="top-progress-bar"><img src="images/progress-bar-top.png" width="30" height="15" /></li>
				{foreach from=$progress_score key=arrkey item=arrVal}
				{if ($progress_score.$arrkey.score == 20)}
					<li class="middle-progress-bar"><img src="images/progress-bar-middle-in.png" width="30" height="29" /><span>{$progress_score.$arrkey.text|replace:'_':' '}</span></li>
				{else}
					<li class="middle-progress-bar"><img src="images/progress-bar-middle.png" width="30" height="29" /><span>{$progress_score.$arrkey.text|replace:'_':' '}</span></li>
				{/if}
				{/foreach}
				<li class="bottom-progress-bar"><img src="images/progress-bar-bottom.png" width="30" height="43" /><span>{#Register#}</span></li>
			</ul>
			<br class="clear" />
			<div class="container-percent">{$total_score}%</div>  
		</div>

		<div class="container-progress-icon">
			<ul>
				<a href="{if ($progress_score.Phone_Number.score != 20)}?action=incompleteinfo_skip{/if}" style="text-decoration:none; color:#7E190D; font-weight:bold;">
					<li class="complete-mobile">
						<div class="imgcheck">{if ($progress_score.Phone_Number.score == 20)}<img src="images/icon-true.png" width="54" height="49" />{/if}</div>
						<span style="text-decoration:none;">{$progress_bar_mobile_text|replace:'_':' '}<span>{*$progress_score.Phone_Number.text|replace:'_':' '*}
					</li>
				</a>
				<a href="?action=editprofile" style="text-decoration:none; color:#7E190D; font-weight:bold;">
					<li class="complete-profile">
						<div class="imgcheck">{if ($progress_score.Complete_Profile.score == 20)}<img src="images/icon-true.png" width="54" height="49" />{/if}</div>
						<span style="text-decoration:none;">{$progress_score.Complete_Profile.text|replace:'_':' '}<span>
					</li>
				</a>
				<a href="?action=fotoalbum" style="text-decoration:none; color:#7E190D; font-weight:bold;">
					<li class="Photo-Album">
						<div class="imgcheck">{if ($progress_score.Photo_Album.score == 20)}<img src="images/icon-true.png" width="54" height="49" />{/if}</div>
						<span>{$progress_score.Photo_Album.text|replace:'_':' '}<span>
					</li>
				</a>
				<a href="?action=lonely_heart_ads" style="text-decoration:none; color:#7E190D; font-weight:bold;">
					<li class="Lonely-heart-ads">
						<div class="imgcheck">{if ($progress_score.Lonely_heart_ads.score == 20)}<img src="images/icon-true.png" width="54" height="49" />{/if}</div>
						<span>{$progress_score.Lonely_heart_ads.text|replace:'_':' '}<span>
					</li>
				</a>
			</ul>
		</div>
		
	</div>
   
</div>
<br class="clear" />
</div>