{if $smarty.session.sess_admin eq 1}
<a href="?action=administrator" class="butsearch">{#ADMINISTRATOR#}</a>
{/if}
<input type="hidden" id="status" name="status">
{if $submenu eq "editprofile"}
<a href="?action=editprofile" class="butsearch active">{#PROFILE#}</a>
{else $submenu eq ""}
<a href="?action=editprofile" class="butsearch">{#PROFILE#}</a>
{/if}
{if $submenu eq "editprofile"}
<a href="?action=editprofile" class="butsearchsub">{#Edit_Profile#}</a>
<a href="?action=changepassword" class="butsearchsub">{#Change_Password#}</a>
<a href="?action=fotoalbum" class="butsearchsub">{#FOTOALBUM#}</a>
{else}
{/if}
{if $submenu eq "mymessage"}
<a href="?action=chat" class="butsearch active">{#MESSAGES#}</a><div id="new_msg" style="position:relative; top:-30px; float:right; height:0px"></div>
{else $submenu eq ""}
	<a href="?action=chat" class="butsearch">{#MESSAGES#}</a><div id="new_msg" style="position:relative; top:-30px; float:right; height:0px"></div>
{/if}
{if $submenu eq "lonely_heart_ads"}
<a href="?action=lonely_heart_ads" class="butsearch active">{#LONELY_HEART_ADS#}</a>
{else $submenu eq ""}
<a href="?action=lonely_heart_ads" class="butsearch">{#LONELY_HEART_ADS#}</a>
{/if}
{if $submenu eq "lonely_heart_ads"}
<a href="?action=lonely_heart_ads" class="butsearchsub">Write</a>
<a href="?action=lonely_heart_ads&do=search" class="butsearchsub">Search</a>
{else}
{/if}
<!-- <a href="?action=favorite" class="butsearch">{#FAVOURITES#}</a> -->
{if $submenu eq "suggestion_box"}
<a href="?action=suggestion_box" class="butsearch active">{#SUGGESTIONBOX#}</a>
{else $submenu eq ""}
<a href="?action=suggestion_box" class="butsearch">{#SUGGESTIONBOX#}</a><div id="new_sugg" style="position:relative; top:-30px; float:right; height:0px"></div>
{/if}
{if $submenu eq "suggestion_box"}
<a href="?action=suggestion_box&do=sugg4" class="butsearchsub">{#MESSAGES#}</a>
<a href="?action=suggestion_box&do=sugg3" class="butsearchsub">{#Diary#}</a>
<a href="?action=suggestionalbum" class="butsearchsub">{#Photo_Album#}</a>
{else}
{/if}
{if $smarty.const.WEB_CAMS_ENABLE}
<a href="?action=webcam" class="butsearch">Live-Cam-Chat</a>
{/if}
{if $smarty.const.FREE_SMS_ENABLE}
<a href="?action=SMS" class="butsearch">{#FREE_SMS#}</a>
{/if}
<a href="?action=birthday" class="butsearch">{#BIRTHDAYS_MENU#}</a>
{include file="left-menu.tpl"}
<a href="?action=question" class="butsearch">{#QUESTION_TO_TEAM#}</a>
<a href="?action=pay-for-coins" class="butsearch">{#I_WANT_PAY_COINS#}</a>
<a href="?action=logout"  class="butsearch">{#LOG_OUT#}</a>