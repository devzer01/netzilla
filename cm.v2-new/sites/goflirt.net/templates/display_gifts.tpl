<h1 class="title">Geschenke</h1>
<ul class="container-profile-icon">
    {foreach from=$gifts item=gift name=membergifts}
    
        <li>
            <a href="javascript:void(0)" class="fancybox profile-icon {if $item.approval} approval{/if}" data-fancybox-group="gallery"></a>
            <img src="{$gift.image_path}" width="110" height="92" class="profile-img"/>
            <a href="javascript:void(0)" class="quick-icon q-photo gift-cnt"><div class="txt-gift-cnt">{$gift.cnt}</div></a>
        </li>    
    
    {foreachelse}
    <li>Hier können sie ihre versendeten Geschenke an diesen User sehen!</li>
    {/foreach}
</ul>