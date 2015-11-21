<div class="container-Lonely-hearts-box">
	{#Date_of_ADs#} : {$datas.datetime|date_format:"%Y-%m-%d" }
    <br />
	{#Headline#} : {$datas.headline|stripslashes}
    <br />
    {assign var="civilstatus" value=$datas.civilstatus}
    {#ADs_text#} : <div style="width:80%;">{$datas.text|stripslashes|wordwrap:100:"<br />":true}</div>
</div>