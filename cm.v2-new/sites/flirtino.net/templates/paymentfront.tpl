<!-- {$smarty.template} -->
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td class="text14grey" align="center" >
            {$text}
            {if $payment_history}
            <table border="0" cellpadding="2" cellspacing="1" width="100%" align="center">
                
                <tr bgcolor="#db9ced" bordercolor="#FFFFFF" height="20px" style="font-size: medium; color:#FFFFFF" valign="middle">
                    <th>
                        Deine Mitgliedschaft zur Zeit
                    </th>
                    {if $payment_history.id}
                    <th>
                        Seit
                    </th>
                    <th>
                        Gültig bis
                    </th>
                    <th>
                        Kündigung
                    </th>{/if}
                </tr>
                <tr bgcolor="#eeeeee">
                    <td align="center" style="font-size: medium">
                        {$payment_history.type}
                    </td>
                    {if $payment_history.id}
                    <td align="center" style="font-size: medium">
                        {$payment_history.start_date}
                    </td>
                    <td align="center" style="font-size: medium">
                        {$payment_history.end_date}
                    </td>
                    <td align="center">
                        {if $payment_history.cancelled eq 0}<span style="color: red; cursor: pointer" onclick="if(confirm('Möchtest du deine Mitgliedschaft tatsächlich beenden?')) location='?action=membership&type=cancel&id={$payment_history.id}'">Mitgliedschaft beenden.</span>
                        {else}<span style="color: red;">beendet</span>
                        {/if}
                    </td>{/if}
                </tr>
                <tr>
                    <td height="30px">
                    </td>
                </tr>
            </table>
            {/if}

            <table align="center">
                <tr>
                    <td>
                        {include file=membership_listing_front.tpl}
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td height="10">
                    </td>
                </tr>
                {if $today neq ''}
                <tr>
                    <td align="center" class="text12_bggrey">
                        Wenn das Abo nicht gek&uuml;ndigt wird, verl&auml;ngert sich das 3 Tage Testabo in ein VIP Monatsabo. Das 1 Monatsabo wird automatisch in ein 3 Monatsabo umgewandelt, das 3 Monatsabo wandelt sich zu einem Jahresabo. Das Jahresabo wird um ein weiteres Jahr verlängert.
                        <br>
                        <br>
                    </td>
                </tr>
                {/if}
            </table>
        </td>
        </tr>
        {if $today neq ''}
        <tr>
            <td height="15">
            </td>
        </tr>
        <tr>
            <td align="center">
            <input type="image" src="images/zurueck_bt.gif" id="back_button" name="back_button" onclick="location = '?action=payportal'; return false;"/>
        </tr>{/if}
</table>
<table>
    <tr>
        <td height="40">&nbsp;
            
        </td>
    </tr>
</table>
