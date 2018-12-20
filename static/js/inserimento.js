app_dir = "inserimento/";
var baseurl = root_dir + app_dir;
var autocomplete;
var allegato_dacancellare;
var panorama;
var sv ;


function initGoogleStuff() {
			
	panorama = new google.maps.StreetViewPanorama(
		
		document.getElementById('sv_panorama'), {
		  
		  visible: false
	});
	
	sv = new google.maps.StreetViewService();
	
	
	panorama.addListener('position_changed', function() {
		$("#latitudine").val( 	panorama.getPosition().lat() );
		$("#longitudine").val( 	panorama.getPosition().lng() );
	});
	
	panorama.addListener('pov_changed', function() {
			
		$("#angolo").val(panorama.getPov().heading);
		$("#pitch").val(panorama.getPov().pitch);
			
	});
		
	panorama.addListener('zoom_changed', function() {
			
		$("#zoom").val(panorama.getZoom());
			
	});
	
	autocomplete = new google.maps.places.Autocomplete(document.getElementById('posizione'));

	autocomplete.addListener('place_changed', function() {
	 
		var place = autocomplete.getPlace();
		  
		$("#latitudine").val( place.geometry.location.lat() );
		$("#longitudine").val( place.geometry.location.lng() );
		
		sv.getPanorama({location: {lat: parseFloat($("#latitudine").val()), lng:  parseFloat($("#longitudine").val())}, radius: 50}, processSVData);
	  
	});

	if($("#latitudine").val() !== "" && $("#longitudine").val() !== ""){
		
		sv.getPanorama({location: {lat: parseFloat($("#latitudine").val()), lng:  parseFloat($("#longitudine").val())}, radius: 50}, processSVData);
	}
 }


function processSVData(data, status) {
			
	if (status === 'OK') {
		
		var angolo = $("#angolo").val() !== ""  ? parseFloat( $("#angolo").val() ) : 270;
		var pitch = $("#pitch").val() !== ""  ? parseFloat( $("#pitch").val() ) : 0;
		
		panorama.setPano(data.location.pano);
		
		panorama.setPov({
			heading: angolo,
			pitch: pitch
		});
		
		if($("#zoom").val()  !== "")
			panorama.setZoom( parseFloat ($("#zoom").val() ));
		
		panorama.setVisible(true);
		
	}
	else myModal("#msg_alert", "Errore", "Street View non disponibile per questo indirizzo", "red", "show");
}


function check_codice( codice ){
	
	var pattern = /[^0-9]/g;
	
	if(codice.match(pattern) === null)
    	return true;
   
   else return false;
	
}
		
$(document).ready(function(){
	
	var insert_mode = $("#insert_mode").val();
	
	
	$("#form-block").fadeIn("slow");
		
		
		
		
	$("#nuova_rilevazione").click ( function() {
		
		if (insert_mode == "insert"){
			$("input").each( function (index, elem){
				$(elem).val("");
			});
			
			$("select").each( function (index, elem){
				$(elem).val("");
			});
			
			
			$("html, body").animate({"scrollTop": 0}, 500);
			panorama.setVisible(false);
		}
		
		else {
			
			$("#img_caricamento").css({"display": "block", "margin": "auto"});
			$("#caricamento").modal({"backdrop": "static"});
				
			$(location).attr("href", root_dir + app_dir);
		}
		
	} );
	
	$(document).on('keyup keypress', 'form input[type="text"]', function(e) {
		  if(e.which == 13) {
			e.preventDefault();
			return false;
		  }
	});
	
	$(".glyphicon.glyphicon-remove").each( function (index, elem){
		
		$(elem).click( function(event) {
			$("#conferma_elimina").modal("show");
			allegato_dacancellare = $(this);
			
		});
		
	});
	
	$("#canc_ok").click( function(event){
			
			event.preventDefault();
			$("#conferma_elimina").modal("hide");
			$("#caricamento").modal({"backdrop": "static"});
			
			var action = allegato_dacancellare.parent().attr("action");
			var elem_form = $(allegato_dacancellare.parent());
			var param_name = $(elem_form)[0][0].name;
			var param_value = $(elem_form)[0][0].value;
			var csfr_param_name = $("#csrf").attr("name");
			
			var post_res = $.post( action, { "allegato": param_value, 
																"cod_raccolta": $("#sigla_raccolta").val(),
																"cod_unita": $("#unita").val(),
																"cod_operatore": $("#codice_op").val(),
																"formuni" : $("#csrf").val()
												});
			
			post_res.done ( function() {
				$("#caricamento").modal("hide");
				myModal("#inserimento_modal", "Allegato cancellato" , "Allegato cancellato correttamente" , "green", "show");
				$(allegato_dacancellare).parent().remove();
			});
			
			post_res.fail(function() {
				$("#caricamento").modal("hide");
				myModal("#inserimento_modal", "Errore" , "C'è stato un problema tecnico, allegato non cancellato" , "red", "show");
			});
	});
	
	$(document).on("click", "#invia", function(e){
				
				e.preventDefault();
				
				if($("#sigla_raccolta").val().length != 3){
					myModal("#inserimento_modal", "Errore", "Sigla raccolta non valida<br>Inserire un codice di due caratteri e una cifra", "red", "show");
					return false;
				}
				
				if($("#unita").val().length != 3){
					myModal("#inserimento_modal", "Errore", "Codice unità non valido<br>Inserire un codice di tre cifre", "red", "show");
					return false;
				}
				
				if(check_codice( $("#unita").val() ) === false){
					myModal("#inserimento_modal", "Errore", "Codice unità non valido<br>Inserire solo numeri", "red", "show");
					return false;
				}
				
				if(check_codice( $("#cod_sottounita").val() ) === false){
					myModal("#inserimento_modal", "Errore", "Codice sottounità non valido<br>Inserire solo numeri", "red", "show");
					return false;
				}
				
				if(check_codice( $("#cod_segno").val() ) === false){
					myModal("#inserimento_modal", "Errore", "Codice segno non valido<br>Inserire solo numeri", "red", "show");
					return false;
				}
				
				if($("#cod_sottounita").val().length != 2){
					myModal("#inserimento_modal", "Errore", "Codice sottounità non valido<br>Inserire un codice di due cifre", "red", "show");
					return false;
				}				
				
				
				if( $("#cod_segno").val().length != 2) {
					myModal("#inserimento_modal", "Errore", "Codice segno non valido<br>Inserire un codice di due cifre", "red", "show");
					return false;
				}
	
				
				
				if($("#cod_sottounita").val() == "00" && $("#cod_segno").val() == "00"){ //è stata inserita una unità
					
					if($("#categoria_generale").val() == ""){
						myModal("#inserimento_modal", "Errore", "Selezionare una categoria generale", "red", "show");
						return false; 
					}
					
					if( $("#mese").val() == ""){
						myModal("#inserimento_modal", "Errore", "Selezionare un mese per la data  rilevazione unità", "red", "show");
						return false;
					}
					
					if( $("#numero_vetrine").val() == ""){
						myModal("#inserimento_modal", "Errore", "Selezionare un numero vetrine", "red", "show");
						return false;
					}
					
					if( $("#anno").val().trim().length != 4 || !Number($("#anno").val().trim()) ){
						myModal("#inserimento_modal", "Errore", "Valore non valido per l'anno unità", "red", "show");
						return false;
					}
					
					if( $("#posizione").val() == ""){
						myModal("#inserimento_modal", "Errore", "Inserire un indirizzo", "red", "show");
						return false;
					}
					
					if( $("#box_lingue").val() == ""){
						myModal("#inserimento_modal", "Errore", "Selezionare almeno una lingua", "red", "show");
						return false;
					}
					
					if( $("#box_lingua_dominante").val() == ""){
						myModal("#inserimento_modal", "Errore", "Selezionare almeno una lingua dominante", "red", "show");
						return false;
					}
					
					
					if( $("#box_lingua_prominente").val() == ""){
						myModal("#inserimento_modal", "Errore", "Selezionare almeno una lingua prominente", "red", "show");
						return false;
					}
					
					
					if($("#box_categoria").val()== "" && $("#categoria_altro").val() == ""){
						myModal("#inserimento_modal", "Errore", "Selezionare una categoria o digitarne una", "red", "show");
						return false;
					}
					
					
				}
				
				
				if($("#cod_sottounita").val() != "00" && $("#cod_segno").val() == "00"){//è stata inserita una sottounità
					
					if( $("#mese_sottounita").val() == ""){
						myModal("#inserimento_modal", "Errore", "Selezionare un mese per la data  rilevazione sottounità", "red", "show");
						return false;
					}
					
					if( $("#anno_sottounita").val().trim().length != 4 || !Number($("#anno_sottounita").val().trim()) ){
						myModal("#inserimento_modal", "Errore", "Valore non valido per l'anno sottounità", "red", "show");
						return false;
					}
					
					if( !$("#box_sottounita").val() && $("#sottounita_altro").val() == ""){
						myModal("#inserimento_modal", "Errore", "Selezionare un tipo per la sottounita", "red", "show");
						return false;
					}
					
					if( $("#box_lingue").val() == ""){
						myModal("#inserimento_modal", "Errore", "Selezionare almeno una lingua", "red", "show");
						return false;
					}
					
					if( $("#box_lingua_dominante").val() == ""){
						myModal("#inserimento_modal", "Errore", "Selezionare almeno una lingua dominante", "red", "show");
						return false;
					}
					
					
					if( $("#box_lingua_prominente").val() == ""){
						myModal("#inserimento_modal", "Errore", "Selezionare almeno una lingua prominente", "red", "show");
						return false;
					}
				}
				
				
				if($("#cod_segno").val() != "00"){ //è stato inserito un segno
					
					if( $("#mese_segno").val() == ""){
						myModal("#inserimento_modal", "Errore", "Selezionare un mese per la data  rilevazione segno", "red", "show");
						return false;
					}
					
					if( $("#anno_segno").val().trim().length != 4 || !Number($("#anno_segno").val().trim()) ){
						myModal("#inserimento_modal", "Errore", "Valore non valido per l'anno segno", "red", "show");
						return false;
					}
					
					if( $("#cod_segno").val() != "00" && $("#box_genere").val().length === 0){
						myModal("#inserimento_modal", "Errore", "Selezionare un genere per il segno", "red", "show");
						return false;
					}	
					
					if($("#riferimento_spaziale").val().length === 0 ){		
						myModal("#inserimento_modal", "Errore", "Selezionare un valore per riferimento spaziale", "red", "show");
						return false;
					}		
					
					if( $("#box_lingue").val() == ""){
						myModal("#inserimento_modal", "Errore", "Selezionare almeno una lingua", "red", "show");
						return false;
					}
					
					if( $("#box_lingua_dominante").val() == ""){
						myModal("#inserimento_modal", "Errore", "Selezionare almeno una lingua dominante", "red", "show");
						return false;
					}
					
					
					if( $("#box_lingua_prominente").val() == ""){
						myModal("#inserimento_modal", "Errore", "Selezionare almeno una lingua prominente", "red", "show");
						return false;
					}
				}
				
				if($("#file_immagine").val()	== "" && insert_mode == "insert"){		
					myModal("#inserimento_modal", "Errore", "Caricare un'immagine", "red", "show");
					return false;
				}		
					
				// if( $("#cod_segno").val() != "00" && $("#cod_sottounita").val() == "00"){
					// myModal("#inserimento_modal", "Errore", "Inserire un codice sottounità valido [01-99] per il segno", "red", "show");
					// return false;
				// }
				
				
				
								
				$("#sigla_unita").val( $("#unita").val() + '-00-00' );
				$("#sigla_sottounita").val( $("#cod_sottounita").val() + '-00' );
				$("#sigla_segno").val( $("#cod_sottounita").val() + '-' + $("#cod_segno").val() );
				$("#nome_file").val( $("#sigla_raccolta").val() + '-' + $("#codice_op").val() + '-' + $("#unita").val() + '-' + $("#cod_sottounita").val() + '-' + $("#cod_segno").val() );
				
				
				$("#data").val( $("#anno").val() + '-' + $("#mese").val() );
				$("#data_sottounita").val( $("#anno_sottounita").val() + '-' + $("#mese_sottounita").val() );
				$("#data_segno").val( $("#anno_segno").val() + '-' + $("#mese_segno").val() );
				
				$("#caricamento").modal({"backdrop": "static"});

				var form = $('#form_rilevazione').get(0); 
				var formData = new FormData(form);
				
				$.ajax({
					url: baseurl + "salva_rilevazione",
					type: "POST",
					data: formData, 
					processData: false,
					contentType: false
					
				}).done(function(data){
					
					var msg = "I dati sono stati salvati";
				    					
					setTimeout( function() {
						$("#caricamento").modal("hide");
						myModal("#inserimento_modal", "Operazione completata", msg, "green", "show");
						// $("#cronologia_back").prop("disabled", false);
						if(insert_mode == "edit"){
							
							var img_src = $(".img_preview").attr("src");
							img_src = img_src.substring(0, img_src.lastIndexOf("."));
							var nome_file_img = $("#file_immagine").val();
							var estensione_file = nome_file_img.substring(nome_file_img.lastIndexOf("."));
							
							$(".img_preview").attr("src", "");
							$(".img_preview").attr("src", img_src + estensione_file);
						}
							
					}, 2000);
				
					$("#inserimento_modal").on("hidden.bs.modal", function(){
		
						if(insert_mode == "edit")
							$(location).attr("href", baseurl );
						
					});
				
				}).fail(function(data){
					
					var msg;
					
					$("#caricamento").modal("hide");
					
					if(data.responseText.indexOf("Error Number: 1062") > 0) msg = "Codice unità già utilizzato";
					
					else msg = data.responseText;
					
					myModal("#inserimento_modal", "Errore", "Errore nel salvataggio: <br>" + msg, "red", "show");
					return false;	
				
				});
				
				return false;
			});
			
			
		
});
