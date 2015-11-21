<!-- {$smarty.template} -->
<div id="container-content">
<h1 class="title">Registrierung abgeschlossen.</h1>
<div align="center" style="margin:10px;">{#registration_completed_text#}<a href="{$next_page}" style="color:#F00; font-weight:bold;">{#registration_completed_click#}</a>.</div>
</div>
<script type="text/javascript">
  setTimeout("window.location='{$next_page}'",5*1000);
</script>
