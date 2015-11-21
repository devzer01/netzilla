<h1 class="admin-title">Emoticon List</h1>
<div class="container-admin-cotent-box">

    <table width="100%" border="0" cellspacing="1">
        <tr bgcolor="{$table_bg_top}" height="28px">
            <td align="center"><a href="#" class="sitelink">Text Version</a></td>
            <td align="center"><a href="#" class="sitelink">Emoticon</a></td>
        </tr>
        {foreach from=$emoticons item=emoticon name=emoticons}
            <tr bgcolor="{cycle values="$table_bg_1,$table_bg_2"}">
            <td align="center"><strong>{$emoticon.text_version}</strong></td>
            <td align="center"><img src="../{$emoticon.image_path}" /></td>
            </tr>
        {/foreach}
    </table>
    <h1 class="admin-title">Add New Emoticon</h1>
    <div class="container-add-new-emoticon">
    <form method='post' id='sform' action='?action=admin_emoticons&sub_action=upload' enctype="multipart/form-data">
        <strong class="text-title-left" style="width:150px;">Text Representation</strong>
        <input type='text' name='text_version' class="formfield_admin" style="width:250px;"/>
        <br class="clear" />
        <strong class="text-title-left" style="width:150px;">Emoticon File</strong>
        <input type='file' name='emoticon' /> <br />
    
        <a href="#" onclick="$('#sform').submit(); return false;" class="btn-admin"> add</a>
    </form>
    <br class="clear" />
    </div>
 
</div>