<ul>
{section name="visit" loop=$visit}
<li>
	<div class="listleft">
		<a href="thumbnails.php?file={$visit[visit].picturepath}" title="{$visit[visit].username} ({$visit[visit].age})" class="lightview"><img src="thumbnails.php?file={$visit[visit].picturepath}&w=78&h=104" border="0" width="78" height="104" class="listimg" alt="{$visit[visit].username}"></a>
	</div>
	<div class="listright">
		<a href="?action=viewprofile&amp;username={$visit[visit].username}" class="link-inrow">{$visit[visit].username} ({$visit[visit].age})</a>
		<br />
		{$visit[visit].gender}, {$visit[visit].civilstatus}<br />
		{$visit[visit].city}<br />
		{#looking_for#}: 
		{if $visit[visit].lookmen}
			{#Men#}
		{/if}
		{if $visit[visit].lookwomen}
			{#Women#}
		{/if}
		<br />
	</div>
</li>
{/section}
</ul>