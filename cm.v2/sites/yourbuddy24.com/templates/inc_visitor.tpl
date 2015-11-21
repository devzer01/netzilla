<!-- {$smarty.template} -->
<ul class="listprofile">
    {section name="visit" loop=$visit}
    <li>
    <h3 class="name-newest"><a href="?action=viewprofile&amp;username={$visit[visit].username}" class="link-inrow">{$visit[visit].username} ({$visit[visit].age})</a></h3>
    <div id="container-newst-img">
    <a href="thumbnails.php?file={$visit[visit].picturepath}" title="{$visit[visit].username} ({$visit[visit].age})" class="lightview"><img src="thumbnails.php?file={$visit[visit].picturepath}&w=129&h=145" border="0" width="129" height="145" class="listimg" alt="{$visit[visit].username}"></a>
    </div>
    <p>
    <!--<strong>Bremen</strong><br />Weiblich,<br />Sucht nach: <strong>Männern</strong> -->
    {$visit[visit].gender}, {$visit[visit].civilstatus}<br />
    {$visit[visit].city}<br />
    {#looking_for#}: 
    {if $visit[visit].lookmen}
    {#Men#}
    {/if}
    {if $visit[visit].lookwomen}
    {#Women#}
    {/if}
    </p>
    <a href="?action=viewprofile&amp;username={$visit[visit].username}" class="btn-img">{#Read_more#}</a>
    </li>
    {/section}
</ul>