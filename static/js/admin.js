app_dir = "admin/";
var baseurl = root_dir + app_dir;

function aggiorna_lista(){
	
	$("#utenti").html("");
	
	var get_result = $.get( baseurl + "lista_utenti_json");
	
	get_result.done( function( data ) {
		
		data = $.parseJSON(data);
		
		for (var ind in data){
			
			$("#utenti").append( "<option value='"+ data[ ind ].id + "'>" + data[ ind ].nome + " " + data[ ind ].cognome + " - " + data[ind].email + " - " + data[ind].codice_op + "  |  " + data [ ind ].ruolo );
			
		}
		
	});
	
	get_result.fail( function( data ) {
		
		$("#caricamento").modal("hide");
		myModal("#modale", "Errore", "C'è stato un errore <br>" + data.responseText, "red", "show");
	
	});
	
}

$(document).ready( function () {

	var selected_flag = false;
	var radio_selected_flag = false;
	
	$("#utenti").click( function () {
		
		if($("#utenti option:selected").val() !== "") selected_flag = true;
		else selected_flag = false;
		
		$("#canc_utente").prop("disabled", !selected_flag );
		
	} );
	
	$("[name='ruolo']").click( function () {
		
		radio_selected_flag = true;

		$("#cambia").prop("disabled", !(selected_flag && radio_selected_flag) );
		$("#approva").prop("disabled", !(selected_flag && radio_selected_flag) );
		$("#non_approva").prop("disabled", !(selected_flag && radio_selected_flag) );
		$("#ban").prop("disabled", !(selected_flag && radio_selected_flag) );
		
		
	} );
	

	$("#approva").click ( function (e) {
			e.preventDefault();
			$("#caricamento").modal({backdrop: "static"});
			var form = $('#form_approva').get(0); 
			var formData = new FormData(form);
			
			$.ajax({
				url: baseurl + "approva",
				type: "POST",
				data: formData, 
				processData: false,
				contentType: false
				
			}).done(function(data){
				// document.getElementById("form_approva").reset();
				$("#utenti option:selected").remove();
				// $("[name='ruolo']:checked").prop("checked", false);
				
				setTimeout( function() {
					$("#caricamento").modal("hide");
					myModal("#modale", "Approvazione utente riuscita", "L'utente è stato correttamente approvato", "green", "show");
					
				}, 1500);
				
			}).fail( function(data ){
				$("#caricamento").modal("hide");
				myModal("#modale", "Errore", "C'è stato un errore <br>" + data.responseText, "red", "show");
			
			});
			
			return false;
			
	} );
	
	$("#cambia").click ( function (e) {
			e.preventDefault();
			$("#caricamento").modal({backdrop: "static"});
			var form = $('#form_approva').get(0); 
			var formData = new FormData(form);
			
			$.ajax({
				url: baseurl + "cambia_ruolo",
				type: "POST",
				data: formData, 
				processData: false,
				contentType: false
				
			}).done(function(data){
				
				setTimeout( function() {
					$("#caricamento").modal("hide");
					myModal("#modale", "Cambio riuscito", "L'utente è stato modificato", "green", "show");
					
				}, 1500);
				
				aggiorna_lista();
				
			}).fail( function(data ){
				$("#caricamento").modal("hide");
				myModal("#modale", "Errore", "C'è stato un errore <br>" + data.responseText, "red", "show");
			
			});
			
			return false;
			
	} );
	
	$("#non_approva").click ( function (e) {
			e.preventDefault();
			$("#caricamento").modal({backdrop: "static"});
			var form = $('#form_approva').get(0); 
			var formData = new FormData(form);
			
			$.ajax({
				url: baseurl + "non_approvare",
				type: "POST",
				data: formData, 
				processData: false,
				contentType: false
				
			}).done(function(data){

				$("#utenti option:selected").remove();
				$("[name='ruolo']:checked").prop("checked", false);
				
				setTimeout( function() {
					$("#caricamento").modal("hide");
					myModal("#modale", "Richiesta di iscrizione annullata", "L'utente NON è stato approvato", "green", "show");
					
				}, 1500);
				
			}).fail( function(data ){
				$("#caricamento").modal("hide");
				myModal("#modale", "Errore", "C'è stato un errore <br>" + data.responseText, "red", "show");
			
			});
			
			return false;
			
	} );

	$("#ban").click ( function (e) {
			
			e.preventDefault();
			$("#msg_confirm").modal("show");
			
	});
	
	$("#canc_utente").click ( function (e) {
			
			e.preventDefault();
			$("#msg_canc_confirm").modal("show");
			
	});
	
	$("#ban_cancel").click( function() {
		$("#msg_confirm").modal("hide");
	});
	
	$("#canc_cancel").click( function() {
		$("#msg_canc_confirm").modal("hide");
	});
		
	$("#ban_ok").click( function() {
			
			$("#msg_confirm").modal("hide");	
			$("#caricamento").modal({backdrop: "static"});
			
			var form = $('#form_approva').get(0); 
			var formData = new FormData(form);
			
			$.ajax({
				url: baseurl + "ban",
				type: "POST",
				data: formData, 
				processData: false,
				contentType: false
				
			}).done(function(data){
				// document.getElementById("form_approva").reset();
				$("#utenti option:selected").remove();
				$("[name='ruolo']:checked").prop("checked", false);
				
				setTimeout( function() {
					$("#caricamento").modal("hide");
					myModal("#modale", "Operazione riuscita", "L'utente è stato bandito", "green", "show");
					
				}, 1500);
				
			}).fail( function(data ){
				$("#caricamento").modal("hide");
				myModal("#modale", "Errore", "C'è stato un errore <br>" + data.responseText, "red", "show");
			
			});
			
			return false;
			
	} );
	
	$("#canc_ok").click( function() {
			
			$("#msg_canc_confirm").modal("hide");	
			$("#caricamento").modal({backdrop: "static"});
			
			var form = $('#form_approva').get(0); 
			var formData = new FormData(form);
			
			$.ajax({
				url: baseurl + "cancella_utente",
				type: "POST",
				data: formData, 
				processData: false,
				contentType: false
				
			}).done(function(data){
				// document.getElementById("form_approva").reset();
				$("#utenti option:selected").remove();
				$("[name='ruolo']:checked").prop("checked", false);
				
				setTimeout( function() {
					$("#caricamento").modal("hide");
					myModal("#modale", "Operazione riuscita", "L'utente è stato cancellato", "green", "show");
					
				}, 1500);
				
			}).fail( function(data ){
				$("#caricamento").modal("hide");
				myModal("#modale", "Errore", "C'è stato un errore <br>" + data.responseText, "red", "show");
			
			});
			
			return false;
			
	} );

} );