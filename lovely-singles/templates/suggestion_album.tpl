<div class="result-box">
<h1>{#Suggestion_box#} | {#FOTOALBUM#}:</h1>
<div class="result-box-inside-nobg">

<div class="photo-album-banner">
<span class="photo-album-text">Gabi's {#Foto_Album#}</span>
</div>

<!--<div style="font-size:16px; text-decoration:underline;">Photo Album:</div> -->
<div align="center">
{section name=index loop=$picture_list}
<div class="cardlist">
	<a href="{$picture_list[index].big}" class='lightview' rel='gallery[mygallery]' title=""><img src="{$picture_list[index].small}" alt="Album{$smarty.section.index.index+1}" style="{if $picture_list[index].big ne ''} cursor:pointer{/if}"></a><br/>
</div>
{/section}
</div>
<br />

<input id="back_button" name="back_button" type="button" onclick="parent.location='{$smarty.server.HTTP_REFERER}';" value="{#BACK#}" class="button">
</div></div>