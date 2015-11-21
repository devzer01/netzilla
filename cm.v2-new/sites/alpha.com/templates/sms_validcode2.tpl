<!-- {$smarty.template} --> 
{literal}

{/literal}

<table valign="top" width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td align="center">

		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="12" height="24"><img src="images/bgl.gif" width="12" height="24" /></td>
				<td align="center" background="images/bgcenter.gif" class="text12whitebold" ><img src="images/xx.gif" />:: {#Valid_Code2#} ::</td>
				<td background="images/bgr.gif" width="12" height="24"></td>
			</tr>
			<tr>
				<td height="24px" colspan="2"></td>
			</tr>
		</table>

		<table width="90%"  border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td valign="top">
				
					{$message}
<!--
					
					<form name="register_form" method="post" action="?action=validCode2&section=send">	

					<table border="0" cellpadding="5" cellspacing="0" width="100%">
					<tr>
						<td height="30px" align="center" colspan="2" valign="top" class="text14grey"><b>{#Welcome#} {$username},</b></td>
					</tr>
					<tr>
						<td height="40px" align="center" colspan="2" valign="top" class="text14grey"><b>{#Valid_Message1#}</b></td>
					</tr>
					<tr>
						<td align="left" width="" class="text14grey">{#FORNAME#} *:</td>
						<td align="left">
							<input name="forname" type="text" id="forname" style="width:170px" value="{$save.forname}" maxlength="30" class="input" />
						</td>
					</tr>
					<tr>
						<td align="left" width="" class="text14grey">{#SURNAME#} *:</td>
						<td align="left">
							<input name="surname" type="text" id="surname" style="width:170px" value="{$save.surname}" maxlength="30" class="input" />
						</td>
					</tr>
					<tr>
						<td align="left" width="" class="text14grey">{#City#} *:</td>
						<td align="left">
							<input name="city" type="text" id="city" style="width:170px" value="{$save.city}" maxlength="30" class="input" /> 
						</td>
					</tr>
					<tr>
						<td align="left" width="" class="text14grey">{#Street#} *:</td>
						<td align="left">
							<input name="street" type="text" id="street" style="width:170px" value="{$save.street}" maxlength="30" class="input" /> 
						</td>
					</tr>
					
					{literal}

					{/literal}
					
					<tr>
						<td align="left" class="text14grey">Mobiltelefon *:</td>
						<td align="left">
							<table width="100%" cellspacing="0" cellpadding="0" border="0">
							
							<tr>
							<td width="20%">
                            	<div style="float:left;padding-top:2px">+&nbsp;</div><input type="text" id="phone_code" name="phone_code" value="{$save.phone_code}" class="code" maxlength="4" />
                            </td>
							<td width="80%">
								<input type="text" id="phone_number" name="phone_number" value="{$save.phone_number}" style="width:104px" />
								<a id="phone_numbertip" href="javascript:" title="Bitte gebe deine Handynummer in dem Format '01239999999' ein" class="check">?</a>
								<a href="javascript: void(0)" onclick="checkMobilePhone('phone_code', 'phone_number')" class="check">Nr. prüfen</a>
							</td>
							</tr>
					
							</table>
						</td>
					</tr>
					<tr>
						<td height="30px" align="left" colspan="2" valign="bottom"><b>{#Valid_Message2#}</b></td>
					</tr>
					<tr>
						<td height="30px" align="left" colspan="2" valign="bottom" class="h11">
							<input type="checkbox" name="accept" id="accept" value="1"> Ich habe die
							<a href="#" onclick="window.open('?action=agbs', 'popup', 'location=0, status=0, scrollbars=1, width=930, height=600, resizable=0')">
							AGB gelesen und akzeptiere diese.</a>
						</td>
					</tr>
					<tr>
						<td height="60px" align="left" colspan="2" valign="bottom" >* Sämtliche Angaben sind Optional und dienen lediglich zur Verbesserung unserer Services. Die Handy Anmeldung dient der Altersverifizierung! Bei Eingabe erh&auml;ltst Du einen Verifizierungscode an Deine Handynummer.</td>
					</tr>
					<tr>
						<td height="20px" align="center" colspan="2" valign="middle" >

							<input type="hidden" id="password" name="password" value="{$password}" />
							<input type="button" onclick="parent.location='{$url_back}'" value="{#CANCEL#}" /> 
							<input type="submit" name="temp" onclick="return checkNullSignup_adv()" value="{#FINISH#}" />
							</td>
						</td>
					</tr>
					{*
					<tr>
						<td align="center" colspan="2" >
							<table align="center" cellspacing="0" cellpadding="0" border="0">
								<tr>
							    	<td width="99" height="15" align="center" background="images/bglogin.gif">
								    	<a href="#" onclick="parent.location='{$url_back}'" class="link">{#CANCEL#}</a>
							        </td>
									<td width="4"></td>
									<td width="99" height="15" align="center" background="images/bglogin.gif">
								    	<a href="#" onclick="if(checkNullSignup1())stepWizard('stepPage2', Array('stepPage1', 'stepPage3'))" class="link">{#NEXT#}</a>
							        </td>
								</tr>
							</table>
						</td>
					</tr>
					*}
					</table>

					</form> 
					
-->	

					<table align="center" border="0" cellpadding="0" cellspacing="0" width="80%">
						<tr>
							<td height="35px"></td>
						</tr>	
						<tr>
							<td colspan="2" class="text14red"  align="center">"Dein Ersatzpasswort wurde akzeptiert. <br> <br> Vielen Dank und viel Spaß weiterhin auf connectforever"</td>
						</tr>
					
						<tr>
							<td height="35px"></td>
						</tr>
						<tr>
							<td colspan="2" class="text14grey"  align="center">Du wirst jetzt automatisch zur Homepage weitergeleitet.</td>
						</tr>
						<tr>
							<td height="30px"></td>
						</tr>
						<tr>
							<td colspan="2"><META http-equiv='refresh' content='3;URL=http://www.lovely-singles.com/'></td>
						</tr>
						<tr>
							<td height="10px"></td>
						</tr>
					
					</table>


					</td>
				</tr>
        	</table>
		</td>
	</tr>
</table>
