app_dir = $("#id_ruolo").val() ? "iscriviti/" : "operatori/";
var baseurl = root_dir + app_dir;

$(document).ready( function () {

	$("#iscriviti").click ( function (e) {
			
			e.preventDefault();
			
			if($("#g-recaptcha-response").val() === ""){
				myModal("#modale", "Errore", "Cliccare sul recaptcha", "red", "show");
				return false;
			}
			
			$("#caricamento").modal({backdrop: "static"});
			
			
			var form = $('#form_iscrizione').get(0); 
			var formData = new FormData(form);
		
			$.ajax({
				url: baseurl + "invia_iscrizione",
				type: "POST",
				data: formData, 
				processData: false,
				contentType: false
			
			}).done(function(data){
				// document.getElementById("form_approva").reset();
				$(location).attr("href", baseurl + "success");
				
			}).fail( function(data ){
				
				var msg = data.responseText;
				
				$("#caricamento").modal("hide");
				
				if(msg === "")
					msg = "Spiacente, a causa delle tue continue richieste respinte sei stato bandito";
				
				myModal("#modale", "Iscrizione non riuscita", "Errore: " + msg, "red", "show");
			
			});
			
			return false;
		
	} );

	
} );