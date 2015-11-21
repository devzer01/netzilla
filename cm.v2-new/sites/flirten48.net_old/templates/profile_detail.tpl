<label class="label-profile-box"><strong>{#Gender#}: </strong>{$profile.gender}</label>
<label class="label-profile-box-right"><strong>{#Country#}: </strong>{$profile.country}<br /></label>
<label class="label-profile-box"><strong>{#Birthday#}: </strong> {$profile.age}</label>
<label class="label-profile-box-right"><strong>{#State#}: </strong>{$profile.state}<br /></label>
<label class="label-profile-box">&nbsp;</label>
<label class="label-profile-box-right"><strong>{#City#}: </strong>{$profile.city}<br /></label>
<br class="clear" />

<strong>{#Description#}: </strong>
{if $profile.description_approval}<span style="color: red; text-decoration: bold">Waiting for approval</span>{else}{$profile.description}{/if}