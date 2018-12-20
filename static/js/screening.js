var baseurl = root_dir + "screening/";
var panorama;
var sv ;


function processSVData(data, status, panorama, angolo, zoom) {
			
	if (status === 'OK') {

		panorama.setPano(data.location.pano);
		panorama.setPov({
			heading: angolo,
			pitch: 0
		});
		
		panorama.setZoom(zoom);
		panorama.setVisible(true);
		
		$("#latitudine").val( 	data.location.latLng.lat() );
		$("#longitudine").val( 	data.location.latLng.lng() );
		  
		
	}
	else myModal("#modale", "Errore ", "Street View non disponibile per questo indirizzo", "red" , "show");
}
		
		
function initStuff() {
		
		var autocomplete;
		var geocoder = new google.maps.Geocoder;
		
		panorama = new google.maps.StreetViewPanorama(	document.getElementById('pano'), {  visible: false });
		
		sv = new google.maps.StreetViewService();
		
		autocomplete = new google.maps.places.Autocomplete(document.getElementById('indirizzo'));

		autocomplete.addListener('place_changed', function() {
			
			var place = autocomplete.getPlace();	
			
			sv.getPanorama({location: place.geometry.location, radius: 50}, function( data, status){
							processSVData( data, status, panorama, 270, 0.99 );
				
				$("#insert").prop("disabled", false);
			
			} );

		});
		
		panorama.addListener('position_changed', function() {
				$("#latitudine").val( 	panorama.getPosition().lat() );
				$("#longitudine").val( 	panorama.getPosition().lng() );
				var latlng = {"lat": panorama.getPosition().lat(), "lng": panorama.getPosition().lng()};
				geocoder.geocode({'location': latlng}, function(results, status) {
					if (status === 'OK') {
					  if (results[0]) {
							$("#indirizzo").val(results[0].formatted_address);
						};
					}
				});
		});
			
		panorama.addListener('pov_changed', function() {
			
			$("#angolo").val(panorama.getPov().heading);
			$("#pitch").val(panorama.getPov().pitch);
			
		});
		
		panorama.addListener('zoom_changed', function() {
			
			$("#zoom").val(panorama.getZoom());
			
		});
}
	  
$(document).ready( function () {
	
	var mode = $("#screening_mode").val();
	
	if(mode == "insert"){

		myModal("#modale" , "", "<p>Digitare un indirizzo per far apparire lo street view (se disponibile)</p> ", "green" , "show");

	}
	
	else{
		var start_lat = parseFloat($("#latitudine").val());
		var start_lng = parseFloat($("#longitudine").val());
		var start_angolo = parseFloat($("#angolo").val());
		var start_zoom = parseFloat($("#zoom").val());
		
		sv.getPanorama({location:{lat: start_lat, lng: start_lng}, radius: 50}, function( data, status){
						processSVData( data, status, panorama, start_angolo, start_zoom )
		});
					
			
		$("#insert").prop("disabled", false);
		$("#salva").prop("disabled", false);
	}
	
	$(document).on('keyup keypress', 'form input[type="text"]', function(e) {
		 
		 if(e.which == 13) {
			e.preventDefault();
			return false;
		  }
		});
	
	$("[name='lingua']").click( function () {
		
		if($("#indirizzo").val() !== "")
			$("#salva").prop("disabled", false);
		
	} );
	
	$("#salva").click( function (e) {
		
		e.preventDefault();
		
		var action = mode == "edit" ? "edit" : "salva_screening";
		var form = $('#form_sv').get(0); 
		var formData = new FormData(form);
				
		$.ajax({
			url: baseurl + action,
			type: "POST",
			data: formData, 
			processData: false,
			contentType: false
			
		}).done(function(){
			
			myModal("#modale" , "Salvataggio riuscito ", "Screening salvato con successo ", "green" , "show");
			
			if(mode == "edit")
				mode = "insert";
			
		}).fail( function (data){
			
			myModal("#modale", "Errore ", data.responseText, "red" , "show");
			
		});
				
		
	} );
	
} );