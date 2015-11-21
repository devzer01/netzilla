<div id="container-footer">
    	<div class="footer">
        	<a href="?action=terms">{#AGB#}</a>   |  <a href="?action=imprint">{#IMPRESSUM#}</a>   |  <a href="?action=policy">{#WIDERRUFSRECHT#}</a> | <a href="?action=faqs">{#FAQS#}</a> | <a href="?action=refund">{#REFUND_POLICY#}</a>
        </div>
    </div>


<!-- Get Google CDN's jQuery and jQuery UI with fallback to local -->
{literal}
	<!-- mousewheel plugin -->
	<script src="js/jquery.mousewheel.min.js"></script>
	<!-- custom scrollbars plugin -->
	<script src="js/jquery.mCustomScrollbar.js"></script>
	<script>
		var currentMsgCount = null;
		function showCurrentStatus()
		{
			var mbox = new Ajax.Request("ajaxRequest.php", 
										{
											method: "post", 
											parameters: "action=fetchAllStatus", 
											onComplete: function(originalRequest) {
												if(originalRequest.responseText!="")
												{
													var currentStatus = new Array();
													currentStatus = eval(originalRequest.responseText);
													if(currentStatus['0']==0)
														jQuery("#text_msg").html("");
													else
														jQuery("#text_msg").html("<font class='alert-message-box'>"+ currentStatus['0'] + " " + newmessage + "!</font>");
													
													if(currentStatus['1']==0)
														jQuery("#email_msg").html("");
													else
														jQuery("#email_msg").html("<font class='alert-message-box'>"+ currentStatus['1'] + " " + newmessage + "!</font>");

													if(currentStatus['2']==0)
													{
														currentMsgCount = 0;
														jQuery("#new_msg").html("");
													}
													else
													{
														jQuery("#new_msg").html("<font style='display:block; position:relative; top:-88px; left:35px; margin-left:1px; text-align:center; height:26px; font-size:10px; font-weight:bold; font-style:italic; line-height:24px;  width:27px; background: url(images/cm-theme/alert.png) no-repeat 2px 0; float:left; color:#FFFFFF;'>"+ currentStatus['2'] + "</font>");

														if(currentMsgCount != null)
														{
															if(currentMsgCount < currentStatus['2'])
															{
																currentMsgCount = currentStatus['2'];
																notificationSoundElement.pause();
																notificationSoundElement.play();
															}
														}
														else
															currentMsgCount = currentStatus['2'];
													}
													
													if(currentStatus['3']==0)
														jQuery("#new_sugg").html("");
													else
														jQuery("#new_sugg").html("<font style='display:block; position:relative; top:-8px; margin-left:10px; text-align:center; height:26px; font-size:11px; font-weight:bold; font-style:italic; line-height:26px; float:left; width:27px; background: url(images/cm-theme/alert.png) no-repeat; color:#FFFFFF;'>"+ currentStatus['3'] + "</font>");
												}
											}
										});
		}

		(function(jQuery){
			jQuery(window).load(function(){
				jQuery("#content_1").mCustomScrollbar({
					horizontalScroll:true,
					scrollButtons:{
						enable:true,
						scrollType:"pixels",
						scrollAmount:116
					}
				});
				jQuery("#content_2").mCustomScrollbar({
					horizontalScroll:true,
					scrollButtons:{
						enable:true,
						scrollType:"pixels",
						scrollAmount:116
					},
					callbacks:{
						onScroll:function(){
							snapScrollbar();
						}
					}
				});
				/* toggle buttons scroll type */
				jQuery("a[rel='toggle-buttons-scroll-type']").click(function(e){
					e.preventDefault();
					var $this=jQuery(this);
					var cont=jQuery("#content_2");
					var scrollType;
					if(cont.data("scrollButtons-scrollType")==="pixels"){
						scrollType="continuous";
					}else{
						scrollType="pixels";
					}
					cont.data({"scrollButtons-scrollType":scrollType}).mCustomScrollbar("update");
					$this.toggleClass("off");
				});
				/* snap scrollbar fn */
				var snapTo=[];
				jQuery("#content_2 .images_container img").each(function(){
					var $this=jQuery(this);
					var thisX=$this.position().left;
					snapTo.push(thisX);
				});
				function snapScrollbar(){
					if(!jQuery(document).data("mCS-is-touch-device")){ //no snapping for touch devices
						var posX=jQuery("#content_2 .mCSB_container").position().left;
						var closestX=findClosest(Math.abs(posX),snapTo);
						if(closestX===0){
							jQuery("#content_2").mCustomScrollbar("scrollTo","left",{
								callback:false //scroll to is already a callback fn
							});
						}else{
							jQuery("#content_2").mCustomScrollbar("scrollTo",closestX,{
								callback:false //scroll to is already a callback fn
							});
						}
					}
				}
				function findClosest(num,arr){
	                var curr=arr[0];
    	            var diff=Math.abs(num-curr);
        	        for(var val=0; val<arr.length; val++){
            	        var newdiff=Math.abs(num-arr[val]);
                	    if(newdiff<diff){
                    	    diff=newdiff;
                        	curr=arr[val];
                    	}
                	}
                	return curr;
            	}
			});

			{/literal}
			{if $smarty.session.sess_id}
			{literal}
			showCurrentStatus();
			var refreshStatus = setInterval(function() {
				showCurrentStatus();
			}, 5000);
			{/literal}
			{/if}
			{literal}
		})(jQuery);
	</script>
{/literal}