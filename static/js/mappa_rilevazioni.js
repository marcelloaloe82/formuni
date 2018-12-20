var lista_punti = [];
var arr_marker = [];
app_dir = "mappa/";
var baseurl = root_dir + app_dir;
var infowindow;
var map;

function initMap() {
	
		map = new google.maps.Map(document.getElementById('map'), {
			  center: {lat: 45.482009, lng: 9.1850684},
			  zoom: 12
			});
		
		
		$.get(baseurl + "lista_punti").done( function(data) {
			data = $.parseJSON(data);
			carica_marker(data, map);
		} );
		
}

function carica_marker(arr_punti, map){
	
		var myLatLng;
		
		for (index in arr_punti){
			
			if(arr_punti[index]["cod_raccolta"].indexOf("-00-00") > 0  ){
			
				myLatLng = { lat: parseFloat(arr_punti[index]["latitudine"]), lng: parseFloat(arr_punti[index]["longitudine"]) };
								
				arr_marker[index] = crea_marker(myLatLng, map, arr_punti[index]["cod_raccolta"]);
			}
		
		}
			
}


function crea_marker (myLatLng, map, codice_raccolta){
	
	var marker = new google.maps.Marker({
								  position: myLatLng,
								  map: map
	});
	
	
	google.maps.event.addListener(marker, "click",  function (event){
	
		$("#caricamento").modal("show");
		$("#latitudine").val( event.latLng.lat() );
		$("#longitudine").val( event.latLng.lng() );
		
		if(infowindow) infowindow.close();

		infowindow = new google.maps.InfoWindow({
			
			content: codice_raccolta
		
		});
		
		infowindow.open(map, marker);
		
		var formdata = new FormData();
		
		formdata.append("latitudine",  event.latLng.lat());
		formdata.append("longitudine", event.latLng.lng());
		formdata.append("solo_unita", "unita");
		formdata.append($("#token_csrf").attr("name"), $("#token_csrf").val());
		
		//carico immagini (unità, segni, sottounita)
		$.ajax({
				url: baseurl + "get_img_unita_from_coords", 
				type: "POST",
				data:	formdata,
				contentType: false,
				processData: false
				
		}).done( function(data) {
			
			data = $.parseJSON(data);
			var html_img = ["<img src='", "", "' />"];
			var filename_tokens;
			var file_ext;
			var thumb_filename;
			
			$("#immagini").html("");
			
			for(ind in data){
				
				thumb_filename = data[ind]["file_thumb"];
				
				html_img[1] = img_dir + thumb_filename;
				
				$("#immagini").append( html_img.join("") );
					
			}
			
			
		}).fail( function(data) {
			
			myModal("#msg_alert", "Errore", "Errore nel caricamento delle immagini<br>" + data.responseText, "red", "show");
		});
		
		//carico dati singola rilevazione (tutto il resto)
		$.ajax({
				url: baseurl + "get_rilevazione_from_coords/", 
				type: "POST",
				data:	formdata,
				contentType: false,
				processData: false
				
		}).done( function(data) {
				
				data = $.parseJSON(data);
				
				for(key in data[0]){
					
					$("#" + key) .html( data[0][key] );
					
				}
				
				setTimeout( function() {
					
					$("#caricamento").modal("hide");
					
				}, 2000);
				
				
		}).fail( function (data){
			
			myModal("#msg_alert", "Errore", "Errore nel recupero dati: <br>" + data.responseText, "red", "show");
		
		});
		
					
					
	});//end function

	return marker;
}
		
	
$(document).ready( function () {
	
	

} );