<body> 
<div class="container"> 
	<div class="jumbotron" style="margin: 150px auto; border: 2px solid; background-color: white; position: absolute; top: -1000px; width: 0">
		<h2 style="display: none;">Iscrizione completata!</h2> 
		<p style="display: none;">La tua richiesta sarà esaminata entro breve. <br> Quando sarà approvata riceverai una mail di avviso all'indirizzo che hai indicato</p> 
	</div>
</div>
</body>
<script type="text/javascript">
	$(document).ready( function() {
		
		$(".jumbotron").animate({"top": 0, "width": "80%"}, 1000, "swing", function(){
			// $(".jumbotron").css("position", "relative");
			$(".jumbotron h2").fadeIn(1500);
			$(".jumbotron p").fadeIn(1500);
			
		});
		
	} );
</script>
</html>