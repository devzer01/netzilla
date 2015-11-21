<html>
<head>
<style>
p {
	cursor: pointer;
}

p:hover {
	background: yellow;
}
</style>
<script type="text/javascript" src="include/jquery-1.7.2.js"></script>
<script type="text/javascript">
$(document).ready(function() {

	$("#testdata").click(function(){

		for(var i = 1; i<=10; i++)
		{
			$.ajax({
			  type: 'POST',
			  url: 'jquery-post.php',
			  data: "number=" + i,
			 
			}).done(function(html) {
				//$("#data").empty().html('');
				 $("#data").append(html);
				
			});
		}//end for
		
	});
});
</script>
</head>
<body>
	<p id="testdata">test data</p>
	<div id="data"></div>

</body>
</html>
