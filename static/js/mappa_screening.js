var lista_punti = [];
app_dir = "MappaScreening/";
var baseurl = root_dir + app_dir;
var arr_marker = [];
var panorama;
var map;
var current_marker;
var ruolo_utente;

var dict_marker  = { "italiano e/o inglese internazionale": "ita_ing.png",
								  "italiano etnico": "ita_etnico.png",
								  "misto": "misto.png",
								  "altre lingue": "non_italiano.png",
								  "italiano": "italiano.png"
};

function initMap() {
	
	map = new google.maps.Map(document.getElementById('map'), {
		  center: {lat: 45.482009, lng: 9.1850684},
		  zoom: 11,
		  streetViewControl: false
		});
		
		
		
	panorama = new google.maps.StreetViewPanorama(
		document.getElementById('pano1'), {
		  position: {lat: 45.4627338, lng: 9.1777323},
		  pov: {
			heading: 270,
			pitch: 0
		  },
		  visible: false
	});
		
	google.maps.event.addListenerOnce(map, 'idle', function(){
    // do something only the first time the map is loaded

		$.get(root_dir + "MappaScreening/get_screenings").done( function(data) {
			data = $.parseJSON(data);
			carica_marker(data, map);
		} );
	});	
}

function processSVData(data, status, panorama, angolo, zoom, pitch) {
			
	if (status === 'OK') {

		panorama.setPano(data.location.pano);
		panorama.setPov({
			heading: angolo,
			pitch: pitch
		});
	
		panorama.setZoom(zoom);
		panorama.setVisible(true);
		
		$("[name='pitch']").val( pitch );
		$("[name='angolo']").val( angolo );
		$("[name='zoom']").val( zoom );
		
	}
	
	else myModal("#modale", "Errore", "Street View non disponibile per questo indirizzo", "red", "show");
}

function trova_marker_from_coords( current_pos ){
	
	for(var i in arr_marker){
		
		if(arr_marker[i].position.lat() == current_pos.lat() && arr_marker[i].position.lng() == current_pos.lng()){
			arr_marker[i].setMap(null);
			return arr_marker[i];
		}
	}
	
}

function carica_marker(arr_punti, map){
	
	var myLatLng;
	var img_marker;
	var marker;
	var sv = new google.maps.StreetViewService();
	
	for (index in arr_punti){
		
		myLatLng = { lat: parseFloat(arr_punti[index]["latitudine"]), lng: parseFloat(arr_punti[index]["longitudine"])};
		
		img_marker = basedir + "static/img/markers/" + dict_marker [ arr_punti[index]["lingua"] ];
		
		marker = new google.maps.Marker({
			  position: myLatLng,
			  map: map,
			  icon: img_marker
		});

		arr_marker[index] = marker;
		
		google.maps.event.addListener(marker, "click", function(event) {
			
			$("#caricamento").modal({"backdrop": "static"});
			current_marker  = event.latLng;
			var html_img_big = ["<img src=\"", "", "\">"];
			var formdata= new FormData();
			
			formdata.append("latitudine",  event.latLng.lat());
			formdata.append("longitudine", event.latLng.lng());
			formdata.append($("#token_csrf").attr("name"), $("#token_csrf").val());
			
			$.ajax({
					url: baseurl + "get_screening_from_coords/", 
					type: "POST",
					data:	formdata,
					contentType: false,
					processData: false
					
			}).done( function(data) {
					
					data = $.parseJSON(data);
					var latitudine;
					var longitudine;
					var angolo;
					var zoom;
					var skip_keys = ["angolo", "zoom", "id", "pitch"];
					
				
					latitudine = parseFloat(data[0]["latitudine"]);
					longitudine = parseFloat(data[0]["longitudine"]);
					angolo = parseFloat(data[0]["angolo"]);
					zoom = parseFloat(data[0]["zoom"]);
					pitch = parseFloat(data[0]["pitch"]);
					
					for(key in data[0]){
						
						if(skip_keys.indexOf (key) < 0)
							$("#" + key) .html( data[0][key] );
						
						$("[name='" + key + "']") .val( data[0][key] );
						
					}
					
					sv.getPanorama({location:{lat: latitudine, lng: longitudine}, radius: 50}, function( data, status){
						processSVData( data, status, panorama, angolo, zoom, pitch )
					});
					
					
					$("#inserimento").prop("disabled", false);
					
					if(ruolo_utente == "supervisore"){
						
						
						$("#modifica").prop("disabled", false);
						$("#elimina").prop("disabled", false);
						
						setTimeout(function () {
							$("#caricamento").modal("hide");
						}, 500);
						
						return;
					
					}
					
					if($("[name='codice_op']").val() == $("[name='codice_op_utente']").val()){
						
						
						$("#modifica").prop("disabled", false);
						$("#elimina").prop("disabled", false);
					
					}
					
					else{
						
						$("#modifica").prop("disabled", true);
						$("#elimina").prop("disabled", true);
					}
					
					setTimeout(function () {
							$("#caricamento").modal("hide");
					}, 500);
					
					
			}).fail( function (data){
					$("#caricamento").modal("hide");
					myModal("#modale", "Errore", "Errore nel recupero dati: " + data.responseText, "red", "show");
				});
		} );
	}
	
	
}

function pulisci_scheda(){
	
	$("#dati_rilevazione span").each( function (index, elem){
		
		if( $(elem).attr("class") != "didascalia"){
			
			$(elem).html("");
		}
		
	});
	
}
	
$(document).ready( function () {
	
	ruolo_utente = $("#ruolo_utente").val();
	
	$("#elimina").click( function (event) { 
		
		event.preventDefault();
		$("#conferma_elimina").modal("show");
		
	});
	
	$("#canc_ok").click( function (event) { 
	
		var post_result = $.post( baseurl + "elimina", $("#form_screening").serialize() );
		
		post_result.done ( function () { 
			
			$("#conferma_elimina").modal("hide");
			myModal("#modale", "Cancellazione riuscita", "Cancellazione avvenuta con successo", "green", "show");
			// current_marker.setVisible(false);
			// current_marker = null;
			panorama.setVisible(false);
			pulisci_scheda();
			
			var marker_index = arr_marker.indexOf(trova_marker_from_coords(current_marker));
			arr_marker.splice(marker_index, 1);
			
			$("#inserimento").prop("disabled", true);
			$("#modifica").prop("disabled", true);
			$("#elimina").prop("disabled", true);
		});
		
		post_result.fail ( function () { 
			$("#conferma_elimina").modal("hide");
			myModal("#modale", "Errore", "C'è stato un problema tecnico, riprova", "red", "show");
		});
	
	});
		
	$("#modifica").click( function (event) {
		
		event.preventDefault();
		$("#form_screening").attr("action", root_dir + "screening");
		$("#form_screening").submit();
		
	} );

} );