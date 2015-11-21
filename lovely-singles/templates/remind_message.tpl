{include file ="top.tpl"}
<table width='100%'  border='0' height='100%' cellspacing='0' cellpadding='0' bgcolor="#db9ced">
	<tr><td><img src='images/dot.gif' height='5' width='1' border='0'></td></tr>	
	<tr>
	<td width='100%' align='center' valign='top'>
	<table width='680' border='0' cellspacing='0' cellpadding='0' >
		<tr><td><img src='{$url_web}/images/head_mail.gif' height="99" width="680"/></td></tr>
		<tr><td bgcolor="#FFFFFF" height="5"></td></tr>
		<tr>
	  		<td width='680' align='center'>
         		 <table width='100%' height='100%' border='0' cellpadding='0' cellspacing='0'>       		 
				 <tr>
					<td width='12'><img src='{$url_web}/images/clt.gif' width="12" height="16"/></td>
					<td background='{$url_web}/images//cbg.gif'></td>
					<td width='11'><img src='{$url_web}/images/crt.gif' width="11" height="16"/></td>
				</tr>		
				<tr>
					<td width='12' background='{$url_web}/images/cl.gif'></td>
					<td bgcolor="#a05dc0"> 
					<div align='center'>
					<table width='650' align='center'>
					<tr>
						<td>
							<h4 style="color:#ffffff">{#wemissu#}</h4>
						</td>
					</tr>
					<tr>
						<td>
							{section name=index loop=$members}	
							<table align='center' width='90%' cellspacing='1' cellpadding='5' border='0' bgcolor='#9F5CC0'>
							<tr>
								<td width='100%' align='center'>
									<table align='center' bgcolor='#EDCEF7' bordercolor='#9f5cc0' border='0' cellpadding='2' cellspacing='2' style='border:solid 1px' width='100%'>
									<tr>
										<td> 
											<div align='center'>
												<table width='100%' border='0' cellspacing='0' cellpadding='0'>
												<tr>
													<td>
														<div align='center'>
															<table cellspacing='0' cellpadding='0' border='0' width='78px'>
															<tr>
																<td width='100%'>							    
																	<table width='78' height='98' border='1' align='center' cellpadding='0' cellspacing='0' bordercolor='#164A63'>
																	<tr>
																		<td width='78' height='98' bgcolor='#FFFFFF'align='right'> 
																			<img src='{$smarty.const.URL_WEB}/thumbs/{$members[index].picturepath}' width='100'> 
																		</td>
																	</tr>
																	</table>
																</td>
																<td><img src='images/dot.gif' width='5' height='1' border='0'></td>						        
															</tr>
															</table>
														</div>
													</td>
													<td height='110' valign='top'>
														<table width='100%'  border='0' cellspacing='0' cellpadding='2' style='color:#692790;'>
														<tr valign='top'>
															<td colspan='2' style="font-weight: bold; font-size: 14px">{#Name#}: <strong class='text10blackbold'>{$members[index].username}</strong></td>
														</tr>
														<tr valign='top'><td height='9' colspan='2'></td></tr>
														<tr valign='top'> 
															<td width='35%' style="font-weight: bold; font-size: 14px">{#Area#}: <strong class='text10blackbold'>{$members[index].area}</strong></td>
															<td style="font-weight: bold; font-size: 14px">{#City#}: <strong class='text10blackbold'>{$members[index].city}</strong></td>
														</tr>
														<tr valign='top'><td height='9' colspan='2'></td></tr>
														<tr valign='top'>
															<td style="font-weight: bold; font-size: 14px">{#Age#}: <strong class='text10blackbold'>{$members[index].age} {#Year#} </strong></td>
															<td style="font-weight: bold; font-size: 14px">{#Civil_status#}: <strong class='text10blackbold'>{$members[index].civilstatus}</strong></td>
														</tr>
														<tr valign='top'><td height='9' colspan='2'></td></tr>
														<tr valign='top'>
															<td style="font-weight: bold; font-size: 14px">{#Height#}: <strong class='text10blackbold'>{$members[index].height}</strong></td>
															<td style="font-weight: bold; font-size: 14px">{#Appearance#}: <strong class='text10blackbold'>{$members[index].appearance}</strong></td>
														</tr>
														<tr valign='top'><td height='9' colspan='2'></td></tr>
														<tr valign='top'>
															<td height='35' colspan='2' style="font-weight: bold; font-size: 14px">{#Description#}: <strong class='text10blackbold'>{$members[index].description}</strong></td>
														</tr>
														</table>
													</td>
									 
												</tr>
												<tr>
													<td colspan='2'>
														<table width='100%'  border='0' cellspacing='0' cellpadding='2'>
														<tr><td colspan='2' height='9'></td></tr>
														</table>
													</td>
												</tr>
												</table>
											</div>
										</td>

									</tr>
									
									</table>
									<table>
									<tr>
										<td><img src='{$smarty.const.URL_WEB}/images/dot.gif' width='1' height='10'></td>
									</tr>
									</table>
								</td>
							</tr>
							</table>
							{/section}
						</td>
					</tr>
					</table>
					</div>
					</td>
						<td width='10' background='{$url_web}/images/cr.gif' height="120"></td>
					</tr>
					<tr>
						<td width='12'><img src='{$url_web}/images/cld.gif' width="12" height="16"/></td>
						<td background='{$url_web}/images/cbgd.gif'></td>
						<td width='11'><img src='{$url_web}/images/crd.gif' width="11" height="16"/></td>
					</tr>
					</table>
					</td>
				</tr>
				<tr><td bgcolor="#FFFFFF" height="5"></td></tr>
				</table>
			</td>
		</tr>
</table>