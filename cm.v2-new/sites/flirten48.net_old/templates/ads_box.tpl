<!-- {$smarty.template} -->
<br />
<div class="result-box-inside-nobg-in">
	{#Date_of_ADs#} : {$datas.datetime|date_format:"%Y-%m-%d" }
    <br />
	{#Headline#} : {$datas.headline|stripslashes}
    <br />
    {assign var="civilstatus" value=$datas.civilstatus}
    {#ADs_text#} : <div style="width:80%;">{$datas.text|wordwrap:100:"<br />":true|stripslashes}</div>
</div>