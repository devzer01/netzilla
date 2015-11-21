<!-- {$smarty.template} -->
{literal}
<script type='text/javascript'>

jQuery(document).ready(function($) {	
	
	jQuery('#n1').click(function (e) {
		//$("#profilepic").css("opacitiy", "1");
		//$("#profilepic").css("filter", "filter:alpha(opacity=100)");
		$("#profilepic").show();
	});
	
	jQuery("#profilepic").change(function (e) {		
		console.log("Fired File Input Change");
		jQuery("#profilepic_form").submit();
		//jQuery("#profilepic_form")[0].submit();
		//jQuery("#profilepic_form").trigger("submit");
	}); 
	
	window.onhashchange = function () {
		loadByHash();
	}

	loadByHash();
});

function loadByHash()
{
	if(window.location.hash.replace("#", "")!="")
	{
		jQuery('#link_'+window.location.hash.replace("#", "")).trigger('click');
	}
	else
	{
		getPage('?action=fotoalbum', 'contentDiv');
	}
}

function getPage(url, target)
{
	jQuery.get(url, function(data) {
		if(data != '')
		{
			jQuery('#'+target).html(data);
		}
	});
	return false;
}

function getFileDialog()
{
	jQuery('#profilepic').trigger('click');
}
</script>
{/literal}


                        

<section>
    <div class="container-news-bg">
        <div class="container-profile">
        <!-- -->
            <section style="margin-bottom:20px;">
            	<form id="profilepic_form" method="post" enctype="multipart/form-data" action="?action=editprofile">
                <div style="height:30px; float:left; width:950px;">
					<input type="file" id="profilepic" name="profilepic" style="display: none; width:80px; opacity:0; filter:alpha(opacity=0); height: 30px; margin-left:20px; cursor:pointer; 
                    position:relative; top:105px; left:-20px; z-index:999;"></div>
				</form>
            	<ul class="container-news-box">
                	<li>
                		{if $profile.approval}
                			<img src="images/cm-theme/approve.png" width="183" height="220" style=" z-index:2; "/>
                		{/if}
                        <img src="thumbnails.php?file={$profile.picturepath}&w=183&h=220" width="183" height="220" style=" z-index:1;" />
                        <a id="n1" class="settings-button">
                        	<img src="images/cm-theme/bg-news.png" width="183" height="220" style="z-index:3;" />
                        </a>
                        <script type="text/javascript">
                        {literal}
	                        jQuery(document).ready(function($) {
	                        	jQuery('#n1').toolbar({content: '#user-n-options1', position: 'left'});
	                        	jQuery('#n1').on('toolbarItemClick', function (event, elm) {
	        	    				switch (elm.id) {
	        	    					case 'upload':
	        	    						$("#profilepic").trigger('click');;
	        	    						break;
	        	    					case 'upload2':
	        	    						$("#profilepic_form").submit();
	        	    						break
	        	    					case 'delete':
	        	    						document.location.href = "?action=editprofile&proc=delete_profile_picture" 
	        	    						//onclick="if(confirm('Löschen?')) return true; else return false;" class="del-pic">Löschen</a>
	        	    						break;
	        	    				}
	        	    			});
	                        	jQuery("#n1").on('toolbarHidden', function (event, elm) {
	                        		$("#profilepic").hide();
	                        	});
	                        });
                        {/literal}
                        </script>
                        <div id="user-n-options1" class="toolbar-icons" style="display: none;">
                        	<a id='upload' href="#" title="Upload"><i class="icon-upload"></i></a>
                        	<a id='delete' href="#" title="Delete"><i class="icon-trash"></i></a>
                        </div>
                    </li>
                </ul>
                <div class="profile-content" id='profileDetailContainer'> 
                    <p><strong>{#USERNAME#}:</strong> {$profile.username|regex_replace:"/@.*/":""}</p>
                    <p><strong>{#Gender#}:</strong>{$profile.gender}</p>
                    <p><strong>{#Country#}:</strong>{$profile.country}</p>
                    <p><strong>{#Birthday#}:</strong>{$profile.age}</p>
                    <p><strong>{#State#}:</strong>{$profile.state}</p>
                    <p><strong>{#City#}:</strong>{$profile.city}</p>
                    <div class="profil-descrition">
                   		<strong>{#Description#}:</strong>
						{$profile.description}
                    </div>
                </div>
                <br class="clear" />
			</section>
        </div>

        </div>
    </section>
    <!-- profile:{$profile.username} session:{$smarty.session.sess_username} -->
    {if ($profile.username|trim) eq ($smarty.session.sess_username|trim)}
	     <ul class="container-menu-profile">
	        <li><a href="#" id="link_editprofile" onclick ="getPage('?action=editprofile','profileDetailContainer');">EDIT PROFIL</a></li>
	        <li><a href="#" id="link_fotoalbum" onclick="getPage('?action=fotoalbum', 'contentDiv')">FOTOALBUM</a></li>
	        <li><a href="#" id="link_my_favorite" onclick="getPage('?action=my_favorite','contentDiv')">FAVORITEN</a></li>
	        <li><a href="#" id="link_changepassword" onclick="getPage('?action=changepassword','contentDiv')">Passwort ändern</a></li>
	        <li><a href="?action=pay-for-coins" class="btn-right">Coins</a></li>
	     </ul>
	{/if}
     
     <section id='contentDiv'>
    	<div class="container-favoriten">
        
        </div>
    </section>
    
    <script type='text/javascript'>
    	{literal}
    		$(function () {
    			//getPage('?action=fotoalbum', 'contentDiv');
    		});
    	{/literal}
    </script>