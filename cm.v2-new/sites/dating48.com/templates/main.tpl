<!-- {$smarty.template} -->
{if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
<!--check login -->
<div id="container-top-content-sub-l">               
</div>

<h2 style="margin:0; float:left; width:950px; margin-bottom:10px; text-shadow:1px 1px 0 rgba(100,159,199,.8);">Hallo <strong style="color:#ff9000;">{$smarty.session.sess_username}</strong></h2>

<div style=" background:url(images/cm-theme/bg-box_03.png) repeat-x #FFF; float:left; width:950px; -webkit-border-radius: 20px; -moz-border-radius: 20px; border-radius: 20px; border:2px solid #9ad3ff; margin-bottom:10px;">
    <ul id="container-profile-list" style=" float:left;">
        <li>
            <a href="?action=profile">
            <div class="profile-list">
                <div class="boder-profile-img"><img src="images/cm-theme/profile-boder-img.png" width="120" height="121" /></div>
                <div class="img-profile"><img src="thumbnails.php?file={$MyPicture}&w=112&h=113" width="112" height="113" /></div>
            </div>
            </a>
        </li>
    </ul>
    
    <div style="float:left; width:800px; height:120px; margin-top:10px;">
		<div id="container-recent">
			{if $recent_contacts}
			<fieldset>
				<legend>Letzte Nachrichten</legend>
				<!--Recent -->
				<ul id="container-profile-list-most">
					{foreach from=$recent_contacts item="item"}
					<li>
						<a href="?action=chat&username={$item.username}">
						<div class="profile-list-most">
							<div class="boder-profile-img-most"><img src="images/cm-theme/profile-boder-img.png" width="88" height="89" /></div>
							<div class="img-profile-most"><img src="thumbnails.php?file={$item.picturepath}&w=82&h=83" width="82" height="83" /></div>
						</div>
						</a>
					</li>
					{/foreach}
				</ul>
				<!--end Recent -->
			</fieldset>
			{/if}
			{if $random_contacts}
			<fieldset>
				<legend>Kontaktvorschläge</legend>
				<!--Recent -->
				<ul id="container-profile-list-most">
					{foreach from=$random_contacts item="item"}
					<li>
						<a href="?action=chat&username={$item.username}">
						<div class="profile-list-most">
							<div class="boder-profile-img-most"><img src="images/cm-theme/profile-boder-img.png" width="88" height="89" /></div>
							<div class="img-profile-most"><img src="thumbnails.php?file={$item.picturepath}&w=82&h=83" width="82" height="83" /></div>
						</div>
						</a>
					</li>
					{/foreach}
				</ul>
				<!--end Recent -->
			</fieldset>
			{/if}
		</div>   
	</div>
</div>
<!--End check login -->
{else}
<!--Start check not login -->
<div id="container-banner-text">
    <span>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been</span>
</div>
<div id="container-content">
    <div class="content-box-left">
    {include file="online.tpl" total="8"}
        
    </div>
    <div class="content-box-right">
        <div class="bg-box-content-right">
        <!--start tab login-->
        <div id="wrapper-tab">
        <div id="navigation" style="display:none;">
            <ul>
                <li class="selected">
                    <a href="#">LOGIN</a>
                </li>
                <li>
                    <a href="#">REGISTER</a>
                </li>
                <li>
                    <a href="#">FORGET PASSWORD</a>
                </li>

            </ul>
        </div>
        <div id="steps">
     
                <fieldset class="step">
                {******************************** login *****************************************}
                {include file="left-notlogged.tpl"}
                {******************************** End login *************************************} 
                </fieldset>
                <fieldset class="step">
                {******************************** register *****************************************}                
                <form id="form_register_small" method="post" action="?action=register">
                    <div class="box-content-register">
                    <p style="padding-top:15px;">
                        <label for="email">Email</label>
                        <input name="email" type="text" autocomplete='off' placeholder="info@dating48.com"/>
                    </p>
                    <p>
                        <label for="name">Full Name</label>
                        <input name="username" type="text" autocomplete='off'/>
                    </p>
                    <a href="#" class="btn-login" onclick="document.getElementById('form_register_small').submit();"><input name="submitbutton" type="submit" value="submit" style="display: none"/>REGISTER</a>
                    </div>
                </form>
                {******************************** end register **************************************}                

                </fieldset>
                 <fieldset class="step">
                <div class="box-content-register">
                    <p style="padding-top:40px;">
                        <label for="email">Email</label>
                        <input id="email" name="email" placeholder="info@dating48.com" type="email" AUTOCOMPLETE=OFF />
                    </p>
                    <a href="#" class="btn-login">SUBMIT</a>
                    </div>
                </fieldset>

        </div>
        
        </div>
        <!--End tab login -->
        </div>
    </div>
</div>
<!--End check not login -->
{/if}



<!--old site -->
<div id="container-top-content-area">

{if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}

{else}


<!--<div id="container-profile-online">
{include file="online.tpl" total="10"}
</div> -->
{/if}
</div>

{if ($smarty.session.sess_username!="")} 
	{if (($bonusid != '') && ($bonusid > 0))}
		<span id="bonusverify_box">
		{include file="bonusverify_step1.tpl"}
		</span>
	{/if}
	<div id="container-content">
    <h1>ONLINE</h1>
	{include file="online.tpl" total="7"}
	</div>
{/if}


<!--<div id="container-content">
{include file="newest_members_box.tpl"}
</div> -->

{include file="my_favorite.tpl"}