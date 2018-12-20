app_dir = "pl/";

$(document).ready ( function() {

	$("#entra").click( function (event) {
		
		event.preventDefault();
		var form = $('#form_login').get(0); 
		var formData = new FormData(form);
		$("#caricamento").modal({"backdrop": "static"});
		
		$.ajax({
			url: root_dir + app_dir + "auth",
			type: "POST",
			data: formData, 
			processData: false,
			contentType: false
		})
		.done(function(data){
			
			if(data == "OK"){
				
				// setTimeout(function () {
						
						$("#caricamento").modal("hide");
						$(location).attr("href", root_dir + app_dir);
					
					// }, 2000);
				
			}
			
			else {
				
				setTimeout(function () {
						
						$("#caricamento").modal("hide");
						myModal("#modale", "Errore", "Credenziali non valide", "red", "show");
					
					}, 2000);
				
			}
		})
		
		.fail( function(data) {
			$("#caricamento").modal("hide");
			myModal("#modale", "Errore", "Errore applicativo:<br>"+ data.responseText, "red", "show");
		} );
	} );
	
} );