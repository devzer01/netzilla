<div class="container-profile-page-content">
	<div class="title">
    	<div class="title-left"></div><h1>My Gifts</h1><div class="title-right"></div>
    </div>
    
    <div class="container-gifts">
    
    	{if $mygifts}
	    	{foreach from=$mygifts item="item"}
	    	<div class="container-gift-list">
	            <ul class="container-send-gift">
	                <li>
	                    <img src="{$item.info.image_path}" width="102" height="102">
	                    <a href="javascript:;" class="link-profile"></a>
	                </li>
	            </ul>
	            <div class="container-who-gift">
	            	<ul class="container-profileList-send-gift">
	            	{foreach from=$item.senders item="sender"}
	            		<li><a href="?action=viewprofile&username={$sender.sender}"><div class="bg-img-profile-send-gift"><img src="thumbnails.php?file={$sender.picturepath}&w=30&h=30" width="30" height="30"></div><p>{$sender.sender}</p> <span>{$sender.times}</span></a></li>
            		{/foreach}
	                </ul>
	            </div>
	        </div>
	    	{/foreach}
    	{/if}
    	
        
    </div>
</div>