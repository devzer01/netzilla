{literal}
<script language="javascript">
	function swaptr(tr){
		if(tr==1){
			document.getElementById('trList').style.display = 'none';
			document.getElementById('trAdd').style.display = '';
		}
		if(tr==2){
			document.getElementById('trAdd').style.display = 'none';
			document.getElementById('trList').style.display = '';
		}
	}
	function confirmDelete(path){
		if(confirm('Confirm delete this card image?')){ 
			window.location.href=path;
		}
	}
	 onload = function(){
	 {/literal}  
		swaptr({$tr})
	{literal}  
	}
</script>
{/literal}  
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" celpadding="0">
	<tr>
		<td align="center">
			<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td height="26" background="images/bg_sex.jpg">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					<td width="12" height="24"><img src="images/bgl.gif" width="12" height="24" /></td>
					<td align="center" background="images/bgcenter.gif" class="text14whitebold" ><img src="images/xx.gif" />
					::{#Manage_card#}::</td>
					<td background="images/bgr.gif" width="12" height="24"></td>
				 	</tr>
					</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr id="trList" style="display:{'none'} ">
		<td><table width="100%"  border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td><table width="90%" height="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td><table  border="0" align="right" cellpadding="2" cellspacing="0" style="cursor:pointer; " onClick="swaptr(1)">
                  <tr>
                    <td><img src="images/icon/b_insrow.png" width="16" height="16"></td>
                    <td> {#Add_New_Card#} </td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>
				  <table width="100%"  border="0" cellspacing="1" cellpadding="1">
				  	 <tr bgcolor="#66CCFF">
						   <td><div align="center">{#Picture#}</div></td>
						   <td><div align="center">{#Show#}</div></td>
				           <td><div align="center">{#Delete#}</div></td>
			  	    </tr>
					{foreach  key=key  from=$cardrec item=curr_id}  
						 <tr bgcolor="#CCCCCC"> 
						 	<td>
						 	  <div align="center">
						 	    <table width="150" height="100"  border="0" cellpadding="1" cellspacing="1" bgcolor="#000000">
                                  <tr>
                                    <td width="200" bgcolor="#FFFFFF"><div align="center"><img src="{$curr_id.cardtmp}"></div></td>
                                  </tr>
							    </table>
				 	       </div></td>  
						 	<td>
								<div align="center">
								{if $curr_id.cardshow==1}
								  <a href="?action=managecard&proc=open&cid={$curr_id.cardid}&value=0&page={$smarty.get.page}">
								  <img src="images/icon/checked.png" width="16" height="16" border="0">
								  </a>
								{else}
								  <a href="?action=managecard&proc=open&cid={$curr_id.cardid}&value=1&page={$smarty.get.page}">
								<img src="images/icon/unchecked.png" width="16" height="16" border="0">
								</a>
								{/if}
								</div>							
							</td>
						    <td>
							<div align="center">
							<a href="javascript: confirmDelete('?action=managecard&proc=del&cid={$curr_id.cardid}&page={$smarty.get.page}')">
							  <img src="images/icon/b_drop.png" width="16" height="16" border="0">
							  </a>
							</div></td>
						 </tr>
					{/foreach}   
                  </table>
				</td>
              </tr>
              <tr>
                <td>{$page_number}&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
		</td>
	</tr>
	<tr id="trAdd" style="display:{'none'} ">
	  <td><table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td><table  border="0" align="right" cellpadding="2" cellspacing="0" style="cursor:pointer; " onClick="window.location.href='?action=managecard'">
              <tr>
                <td><img src="images/icon/b_browse.png" width="16" height="16"></td>
                <td> {#card_list#} </td>
              </tr>
          </table></td>
        </tr>
        <tr>
          <td><table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td valign="top" width="35%">{#Upload_Card#}</td>
				<td>
				<div align="left" id="FlashUpload">
				<OBJECT id="FlashFilesUpload" codeBase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="450px" height="350px" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" VIEWASTEXT>
					<PARAM NAME="FlashVars" VALUE="uploadUrl=uploadcard.php&useExternalInterface=Yes">
					<PARAM NAME="BGColor" VALUE="#F8F6E6">
					<PARAM NAME="Movie" VALUE="ElementITMultiPowUpload1.6.swf">
					<PARAM NAME="Src" VALUE="ElementITMultiPowUpload1.6.swf">
					<PARAM NAME="WMode" VALUE="Window">
					<PARAM NAME="Play" VALUE="-1">
					<PARAM NAME="Loop" VALUE="-1">
					<PARAM NAME="Quality" VALUE="High">
					<PARAM NAME="SAlign" VALUE="">
					<PARAM NAME="Menu" VALUE="-1">
					<PARAM NAME="Base" VALUE="">
					<PARAM NAME="AllowScriptAccess" VALUE="always">
					<PARAM NAME="Scale" VALUE="ShowAll">
					<PARAM NAME="DeviceFont" VALUE="0">
					<PARAM NAME="EmbedMovie" VALUE="0">	    
					<PARAM NAME="SWRemote" VALUE="">
					<PARAM NAME="MovieData" VALUE="">
					<PARAM NAME="SeamlessTabbing" VALUE="1">
					<PARAM NAME="Profile" VALUE="0">
					<PARAM NAME="ProfileAddress" VALUE="">
					<PARAM NAME="ProfilePort" VALUE="0">
					<embed bgcolor="#F8F6E6" id="EmbedFlashFilesUpload" src="ElementITMultiPowUpload1.6.swf" quality="high" 
						pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"	
						type="application/x-shockwave-flash" width="450px" height="350px" 
						flashvars="uploadUrl=uploadcard.php&useExternalInterface=Yes">
					</embed>
				  </OBJECT>
				 </div>

				 <div align="left" id="JSUpload" style="visibility:visible;">
				  <select id="fileslist" name="fileslist" style="height: 25px; width: 300px;" multiple></select> <input type="Button" value="{#BROWSE_FILES#}" name="flashInfoButton" id="flashInfoButton" onClick="browsefiles();" style="width:100px;" /><br>
					<table style="border:solid 1px #000033;" width="260px" height="15px" cellpadding="0" cellspacing="0">
						<tr>
							<td>
								<table id="rowProgress" bgcolor="#0033ff" width="1px" height="15px" cellpadding="0" cellspacing="0">
									<tr>
										<td>&nbsp;</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<div id="lable">&nbsp;</div>
					<input type="button" value="{#UPLOAD#}" name="flashInfoButton" onClick="uploadcard()" style="width:100px;" /> 
				</div> 
				</td>
			</tr>			
		</table></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
