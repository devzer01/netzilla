<h1 class="admin-title">SMS Provider</h1>
<div class="container-admin-cotent-box">

    <form method="POST" action="" name="coinform" id="coinform">
    <input type="hidden" name="set_default" value="1"/>
    <table width="100%"  border="0">
    <tr>
        <td align="left" width="100px">SMSCountry : </td>
        <td><input type="submit" name="smscountry" {if $smarty.const.SMS_PROVIDER eq 'smscountry'}disabled="disabled" value=" Default "{else}value=" Set as default "{/if}/></td>
    </tr>
    <tr>
        <td align="left" width="100px">Clickatell : </td>
        <td><input type="submit" name="clickatell" {if $smarty.const.SMS_PROVIDER eq 'clickatell'}disabled="disabled" value=" Default "{else}value=" Set as default "{/if}/></td>
    </tr>
    <tr>
        <td align="left" width="100px">BudgetSMS : </td>
        <td><input type="submit" name="budgetsms" {if $smarty.const.SMS_PROVIDER eq 'budgetsms'}disabled="disabled" value=" Default "{else}value=" Set as default "{/if}/></td>
    </tr>
    </table>
    </form>
    
</div>