var root_dir = "/formuni/index.php/";
var select_riempite = 0;
var basedir = "/formuni/";
var img_dir = basedir + "static/uploads/"
	

function myModal( modal_id, titolo, msg, colore_titolo, azione ){
	
	if( azione == "show"){
		if( titolo !== "")	$(modal_id + " .modal-title").html( titolo );
		if( colore_titolo !== "") $(modal_id + " .modal-title").css("color", colore_titolo);
		$(modal_id + "  .modal-body").html("<p>" + msg + "</p>");
		$(modal_id  ).modal("show");
	}
	
	else $(modal_id  ).modal("hide");

}

function aggiungi_altro( id_select){
			
	$(id_select).append("<option value=\"Altro\">Altro</option>");
}


function get_form_data() {
					
	var formRicercaData = new FormData();
	var cod_raccolta ="";
	var arr_cod_raccolta =[];
	
	$("#form_ricerca input").each( function(index, element){
		
		if($(element).attr("type") == "checkbox") 
			return;
		
		if($(element).val() !== "" && $(element).prop("disabled") === false)
			formRicercaData.append($(element).attr("name"), $(element).val());
		
		
	});
	
	$("#form_ricerca select").each( function(index, element){
		
		if($(element).prop("disabled") === true)
			return;
		
		try{
			if($(element).val().length === 0)
				return;
		}
		catch(exc){}
		
		if(!$(element).val() === false)
			formRicercaData.append($(element).attr("name"), $(element).val());
		
	});
	
	if($("#allegati").is(":checked") === true){
	
		$("#allegati").val("true");
		formRicercaData.append($("#allegati").attr("name"), $("#allegati").val());
	}
	if($("#tuttelingue").is(":checked") === true){
	
		$("#tuttelingue").val("true");
		formRicercaData.append($("#tuttelingue").attr("name"), $("#tuttelingue").val());
	}
	
	
	return formRicercaData;
}

