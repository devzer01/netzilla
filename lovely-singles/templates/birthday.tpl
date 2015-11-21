<div class="result-box">
<h1>{#Birthday_Head#}</h1>

{*************************** Start BirthDay Result ******************************}
{if $countRow > 0}
	{section name=newmen loop=$datas}
	 <div class="result-box-inside">
    {******************}
        {if $smarty.session.resulttype != 2 }
            {include file="body-search-profile.tpl" datas=$datas[newmen]}
        {else}
            13{include file="body-search-ads.tpl" datas=$datas[newmen]}
        {/if}
	{******************}
    </div>
    {/section}
    <div class="page">{$page_number}</div>
{else}
{#Have_no_data_yet#}
{/if}
{****************************** End BirthDay Result *****************************}
</div>