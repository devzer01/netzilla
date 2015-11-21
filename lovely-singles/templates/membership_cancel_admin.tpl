{include file ="top.tpl"}
<div style="background:url({$url_web}images/bg.jpg) top center repeat-x;background:#2d2d2d;line-height:1em;color:#000000;width:800px;">
    <div style="background:url({$url_web}images/header.jpg) center top no-repeat;height:195px;width:100%;">
        <div>
            <div style="display:block;float:left;height:133px;width:auto;">
            	<img src="{$url_web}images/logo.png" />
			</div>
        </div>
    </div>
    <div style="height: auto;width:800px;margin:0 auto;margin-top:10px;">
        <div class="listbox" style="display:block;width:798px;height:auto;background:#b6b6b6;border:1px solid #696969;-moz-border-radius: 5px; -webkit-border-radius: 5px;margin-top:10px;">
            <h1 style="display:block;width:800px;line-height:30px;font-size:11px;text-indent:15px;color:#b83232;font-weight:bold;">Hello Admin.</h1>
            <div style="background: url({$url_web}images/newbox-bg.jpg) bottom right no-repeat #7e190d;display:block;width:auto;overflow:auto;height:auto;margin:0 8px 8px 8px;padding:8px;border:1px solid #696969;-moz-border-radius: 5px;-webkit-border-radius: 5px;color:#ffffff;">
                <div style="display:block;text-align:left;line-height:20px;margin-top:5px;margin-left:10px;color:#ffffff;font-weight:bold;">
                    <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                    <tr><td height="10"></td></tr>
                    <tr><td>Hello Admin.</td></tr>
                    <tr>
                    	<td valign='top'>
                            <table width='100%'  border='0' cellspacing='1' cellpadding='4'>
                            <tr valign='top'><td height='10'></td></tr>								 
                            <tr bgcolor="#db9ced" align="center">
                                <td>Payment_log_ID</td>
                                <td>Member_ID</td>
                                <td>Nickname</td>
                                <td>Name</td>
                                <td>Handy-Nr.</td>
                                <td>Email</td>
                                <td>Strasse </td>
                                <td>PLZ </td>
                                <td>Stadt </td>
                                <td>Sum</td>
                                <td>New paid until</td>
                            </tr>
                            {foreach key=key from=$member item=member}
                            <tr bgcolor="{cycle values='#f7f7f7,#d0d0d0'}" align="left">
                                <td>{$member.ID}</td>
                                <td>{$member.member_id}</td>
                                <td>{$member.username}</td>
                                <td>{$member.real_name}</td>
                                <td>{$member.m_mobileno}</td>
                                <td>{$member.email}</td>
                                <td>{$member.real_street}</td>
                                <td>{$member.real_plz}</td>
                                <td>{$member.real_city}</td>
                                <td>{$member.sum_paid}</td>
                                <td>{$member.new_paid_until|date_format:"%Y-%m-%d"}</td>
                            </tr>
                            {/foreach}
                            <tr><td height="10"></td></tr>
                            </table>
                    	</td>
                    </tr>
                    </table>
                </div>
            </div>
        </div>
	</div>
</div>