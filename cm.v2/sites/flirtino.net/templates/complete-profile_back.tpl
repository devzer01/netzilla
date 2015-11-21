<!-- {$smarty.template} -->
<div class="listbox">
<h1>Completion bar</h1>
<div class="listboxin">
<div class="complete-box">
<div class="complete-bar" style="width:{$profile_score}%;">{$profile_score}%</div>
</div>
{if ((isset($profile_score)) && ($profile_score != "") && ($profile_score < 100))}
<a href="?action=incompleteprofile" class="complete-button">Complete profile</a>
{/if}
</div>
</div>


<div id="container-progress-bar">
<div class="box-progress-bar">
    <ul>
    	<li class="top-progress-bar"><img src="images/progress-bar-top.png" width="30" height="15" /></li>
        <li class="middle-progress-bar"><img src="images/progress-bar-middle.png" width="30" height="29" /><span>Lonely heart ads</span></li>
        <li class="middle-progress-bar"><img src="images/progress-bar-middle.png" width="30" height="29" /><span>Complete Profile</span></li>
        <li class="middle-progress-bar"><img src="images/progress-bar-middle-in.png" width="30" height="29" /><span>Photo Album</span></li>
        <li class="bottom-progress-bar"><img src="images/progress-bar-bottom.png" width="30" height="43" /><span>Register</span></li>
    </ul>
    <br class="clear" />
    <div class="container-percent">21%</div>  
</div>
<div class="container-progress-icon">
	<ul>
    	<li class="complete-profile"><img src="images/icon-true.png" width="54" height="49" /></li>
        <li class="Photo-Album"><img src="images/icon-true.png" width="54" height="49" /></li>
        <li class="Lonely-heart-ads"><img src="images/icon-true.png" width="54" height="49" /></li>
    </ul>
</div>
</div>