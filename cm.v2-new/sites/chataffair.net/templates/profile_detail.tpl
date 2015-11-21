<div style=" width:400px; float:left;">
<label><strong>{#Gender#}: </strong></label><label>{$profile.gender}</label><br class="clear" />
<label><strong>{#Country#}: </strong></label><label>{$profile.country}</label><br class="clear" />
<label><strong>{#Birthday#}: </strong></label><label>{$profile.age}</label><br class="clear" />
<label><strong>{#State#}: </strong></label><label>{$profile.state}</label><br class="clear" />

<label><strong>{#City#}: </strong></label><label>{$profile.city}</label>
<br class="clear" />
</div>
<div style=" width:470px; float:right; line-height:1.5em;">
<label><strong>{#Description#}: </strong></label><br class="clear" />
{if $profile.description_approval}<span style="color: red; text-decoration: bold;">Waiting for approval</span>{else}{$profile.description}{/if}
</div>