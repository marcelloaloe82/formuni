var lista_punti = [];
app_dir = "ricerca/";
var baseurl = root_dir + app_dir;
var map;
var infowindow;
var arr_marker = [];
var stato = "ricerca";
var num_riga_elimina;
var tbl;

var flag_ricerca = false;
var flag_immagini = false;

var colonne = [
				{ "data": "cod_raccolta" },
				{ "data": "posizione" },
				{ "data": "data" },
				{ "data": "file_thumb" },
				{ "data": "dettaglio" }
				
			];



function filtra_dati ( json ) {

	var ruolo = $("#ruolo").val();
	var cod_op_corrente;
	var codice_op_record ;
	
	for ( var i=0, ien=json.data.length ; i<ien ; i++ ) {
			
			codice_op_record = json.data[i]["cod_raccolta"].split("-")[1];
			cod_op_corrente = $("#codice_op_corrente").val();
			
			json.data[i]["file_thumb"] = '<img src="' + img_dir + json.data[i]["file_thumb"] + '" style="cursor: pointer;" class="img_thumb">';
			json.data[i]["dettaglio"] = "<span  id=\"dettaglio-" + i + "\" class=\"glyphicon glyphicon-list-alt\" style=\"cursor: pointer;\" title=\"dettaglio scheda\" aria-hidden=\"true\"></span>";
			
			if( ruolo == "supervisore" || cod_op_corrente == codice_op_record){
				
				json.data[i]["modifica"] = "<span id=\"modifica-" + i + "\" class=\"glyphicon glyphicon-pencil\" style=\"cursor: pointer;\" title=\"modifica\" aria-hidden=\"true\"></span>";
				json.data[i]["zip"] = "<span id=\"zip-" + i + "\" class=\"glyphicon glyphicon-download-alt\" style=\"cursor: pointer;\" title=\"scarica immagini e allegati\"  aria-hidden=\"true\"></span>";
				json.data[i]["elimina"] = "<span id=\"elimina-" + i + "\" class=\"glyphicon glyphicon-remove\" style=\"cursor: pointer;\" title=\"elimina\"  aria-hidden=\"true\"></span>";
				
			}
			
			else {
				
				json.data[i]["modifica"] = "<span id=\"modifica-" + i + "\" class=\"glyphicon glyphicon-ban-circle\" title=\"operazione non consentita\" aria-hidden=\"true\"></span>";
				json.data[i]["zip"] = "<span id=\"zip-" + i + "\" class=\"glyphicon glyphicon-ban-circle\" title=\"operazione non consentita\"  aria-hidden=\"true\"></span>";
				json.data[i]["elimina"] = "<span id=\"elimina-" + i + "\" class=\"glyphicon glyphicon-ban-circle\" title=\"operazione non consentita\"  aria-hidden=\"true\"></span>";
				
			}
	 }
	 
	
	 if( stato == "ricerca") {
		
		if(infowindow) infowindow.close();
		pulisci_mappa(); 
		pulisci_scheda(); 
		carica_marker(json.data, map);
	 }
		  
	 
	 return json.data;
}

function pulisci_scheda(){
	
	$("#dati_rilevazione span").each( function(index, element){
		
		if( $(element).attr("class") != "didascalia")
			$(element).html("");
		
	})
	
	
}

function pulisci_mappa(){
	
	for (i in arr_marker){
		var marker = arr_marker[i];
		marker.setMap(null);
	}
	
	arr_marker = [];
}

function initMap() {
	
					
	map = new google.maps.Map(document.getElementById('map'), {
			  center: {lat: 45.482009, lng: 9.1850684},
			  zoom: 11
	});
	
	google.maps.event.addListenerOnce(map, 'idle', function(){
		
		tbl = $("#tbl_risultati_ricerca").DataTable( {
			language: {
				"sEmptyTable":     "Nessun dato presente nella tabella",
				"sInfo":           "Vista da _START_ a _END_ di _TOTAL_ elementi",
				"sInfoEmpty":      "Vista da 0 a 0 di 0 elementi",
				"sInfoFiltered":   "(filtrati da _MAX_ elementi totali)",
				"sInfoPostFix":    "",
				"sInfoThousands":  ".",
				"sLengthMenu":     "Visualizza _MENU_ elementi",
				"sLoadingRecords": "Caricamento...",
				"sProcessing":     "Elaborazione...",
				"sSearch":         "Cerca:",
				"sZeroRecords":    "La ricerca non ha portato alcun risultato.",
				"oPaginate": {
					"sFirst":      "Inizio",
					"sPrevious":   "Precedente",
					"sNext":       "Successivo",
					"sLast":       "Fine"
				}
			},
			ajax: {
				"url": baseurl + "filtra_ricerca",
				"type": "POST",
				"dataSrc": filtra_dati,
				"data": get_form_data,
				"contentType": false,
				"processData": false
			},
			
			responsive: true,
			searching: false,
			pageLength: 25,
			dom: "litip",
			
			columns: colonne
		});
		
		tbl.on("draw.dt", function(){
		
			aggancia_callback_dettaglio();
			aggancia_callback_elimina();
			aggancia_callback_modifica();
			aggancia_callback_scarica();
		
		});
		
		
			
		tbl.on("xhr", function() {
		
			if (stato == "click_marker"){
				
				flag_ricerca = true;
				
				if(flag_immagini && flag_ricerca){
					$("#caricamento").modal("hide");
					flag_ricerca = false;
				}
				
			}
			
			else $("#caricamento").modal("hide");
		
		} );	
	});
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
	
		stato = "click_marker";
		$("#latitudine").val( event.latLng.lat() );
		$("#longitudine").val( event.latLng.lng() );
		
		// $("#tipo_rilevazione").val("tutte");
		
		if(infowindow) infowindow.close();

		infowindow = new google.maps.InfoWindow({
			
			content: codice_raccolta
		
		});
		
		infowindow.open(map, marker);
		
		
		
		$("#invia").trigger("click");

		var formdata= new FormData();
		
		formdata.append("latitudine",  event.latLng.lat());
		formdata.append("longitudine", event.latLng.lng());
		formdata.append($("#token_csrf").attr("name"), $("#token_csrf").val());
		
		//carico immagini (unità, segni, sottounita)
		$.ajax({
				url: baseurl + "get_images_from_coords/", 
				type: "POST",
				data:	formdata,
				contentType: false,
				processData: false
				
		}).done( function(data) {
			
			data = $.parseJSON(data);
			var html_img = ["<a class=\"fancybox\" rel=\"gallery1\" href='", "", "' title='", "", "'><img src='", "", "' /></a>"];
			var filename_tokens;
			var file_ext;
			var thumb_filename;
			
			$("#immagini").html("");
			
			$("#cod_raccolta").html(data[0]["cod_raccolta"]);
			
			for(ind in data){
				
				filename = data[ind]["file_immagine"];
				
				thumb_filename = data[ind]["file_thumb"];
				
				html_img[1] = img_dir + filename;
				html_img[3] = data[ind]["cod_raccolta"] + " Lingue: " +  data[ind]["lingue"];
				html_img[5] = img_dir + thumb_filename;
				
				$("#immagini").append( html_img.join("") );
					
			}
			
			$('.fancybox').fancybox();
			
			flag_immagini = true;
			
			if(flag_immagini && flag_ricerca){
				$("#caricamento").modal("hide");
				flag_immagini = false;
			}
			
		}).fail( function(data) {
			$("#caricamento").modal("hide");
			myModal("#modale", "Errore", "Errore nel caricamento delle immagini<br>" + data.responseText, "red", "show");
		});
		
		
					
					
	});//end function
	
	return marker;
}

		

function dettaglio_record( tipo_record, cod_raccolta_split ){
	

	var cod_raccolta = cod_raccolta_split[0];
	var codice;
	var codice_operatore = cod_raccolta_split[1];
	var formdata = new FormData();
	
	if(tipo_record == "unita"){
		codice =  cod_raccolta_split[2];
	}
	
	else{
		codice = [ cod_raccolta_split[3], cod_raccolta_split[4] ].join('-');
		formdata.append("codice_unita", cod_raccolta_split[2] );
	}
	
	
	formdata.append("codice", codice);
	formdata.append("codice_raccolta", cod_raccolta);
	formdata.append("codice_operatore", codice_operatore);
	
	formdata.append($("#token_csrf").attr("name"), $("#token_csrf").val());
	
	$.ajax({
				url: baseurl + "dettaglio_" + tipo_record, 
				type: "POST",
				data:	formdata,
				contentType: false,
				processData: false
				
		}).done( function(data) {
			
			data = $.parseJSON( data );
			$("#dettaglio .modal-body").html("");
			var html = ["<div><span class='didascalia'>", "", "</span>", "&nbsp;<span>", "", "</span></div>"];
			var skip_keys = ["latitudine", "longitudine", "angolo", "zoom"];
			
			for ( var key  in data [0] ){
				
				if(skip_keys.indexOf( key ) >= 0)
					continue;
				
				if(["file_immagine", "file_altro", "codice_sottounità"].indexOf( key ) < 0)
					html[4] = data[0][ key ] ;
				
				if( key == "codice_sottounità")
					html[4] = data[0][ key ].split("-")[0] ;
				
				if( key == "file_immagine")
					html[4] = "<img src='" + img_dir + data[0][key] + "' style='max-width:100%; margin-top: 15px;' />";
				
				if( key  == "allegati" && data[0][key]){
					var arr_allegati = data[0][key].split(",");
					var html_link = ["<a target=\"_blank\" href='", "", "'>", "", "</a>"];
					var arr_html_link = [];
					
					for (ind in arr_allegati){
						
						html_link[1] = img_dir + arr_allegati[ind];
						html_link[3] = arr_allegati[ind]; 
						arr_html_link.push( html_link.join("") );
					}
					
					html[4] =  arr_html_link.join("<br>");
				}
				
				key = key[0].toUpperCase().concat( key.substr (1) ) ;
				html[1] = key.replace("_", " ");
				
				$("#dettaglio .modal-body").append( html.join("") );
			}
			$("#caricamento").modal("hide");
			setTimeout(function(){ $("#dettaglio").modal("show"); } , 500);
			
		}).fail( function( data ){
			$("#caricamento").modal("hide");
			myModal("#msg_alert", "Errore", "Errore nel caricamento dati<br>" + data.responseText, "red", "show");
		});
	
}

function dettaglio(event){
	
	$("#caricamento").modal({backdrop: "static"});
	
	var cod_raccolta = tbl.row( event.data.num_riga ).data()["cod_raccolta"];		
	var cod_raccolta_split = cod_raccolta.split("-");
	var tipo_record;
	
	if(cod_raccolta_split[3] == "00" && cod_raccolta_split[4] == "00")
		tipo_record = "unita";
	
	if(cod_raccolta_split[3] != "00" && cod_raccolta_split[4] == "00")
		tipo_record = "sottounita";
	
	if(cod_raccolta_split[4] != "00")
		tipo_record = "segno";
	
	dettaglio_record(tipo_record, cod_raccolta_split);
	
}

function modifica(event){
	
	var num_riga = parseInt(event.data.num_riga);
			
	var cod_raccolta = tbl.row( num_riga ).data()["cod_raccolta"];		
	
	$("#cod_raccolta_modifica").val( cod_raccolta );
	
	$("#form_modifica").submit(); 
}

function scarica(event){
	
	var num_riga = parseInt(event.data.num_riga);
			
	var cod_raccolta = tbl.row( num_riga ).data()["cod_raccolta"];		
	
	$("#cod_rilevazione_scarica").val( cod_raccolta );
	
	$( "#form_scarica" ).submit();
}

function elimina(event){
	
	num_riga_elimina = parseInt(event.data.num_riga);
			
	var cod_raccolta = tbl.row( num_riga_elimina ).data()["cod_raccolta"];		
	
	$("#cod_raccolta_elimina").val( cod_raccolta );
	
	$("#conferma_elimina").modal("show");

}

function do_elimina(){
	
	$("#conferma_elimina").modal("hide");
	// tbl.row( num_riga_elimina ).remove().draw();
	var result = $.post( baseurl + "elimina", $("#form_elimina").serialize());
	
	result.done(  function( ){
		myModal("#canc_confirm", "Operazione completata", "Rilevazione cancellata", "green", "show");
	});
	
	result.fail( function(data) {  
		myModal("#canc_confirm", "Errore", data.responseText, "red", "show");
	});	
	
}



function aggancia_callback_modifica() {
	
	$(".glyphicon.glyphicon-pencil").each( function (index, element) {
		var num_riga = element.id.split("-")[1];
		$(document).off("click", "#" + element.id,  modifica);
		$(document).on("click", "#" + element.id, { num_riga: num_riga }, modifica);
	} );
}

function aggancia_callback_elimina() {
	
	$(".glyphicon.glyphicon-remove").each( function (index, element) {
		var num_riga = element.id.split("-")[1];
		$(document).off("click", "#" + element.id, elimina);
		$(document).on("click", "#" + element.id, { num_riga: num_riga }, elimina);
	} );
}

function aggancia_callback_scarica() {
	
	$(".glyphicon.glyphicon-download-alt").each( function (index, element) {
		var num_riga = element.id.split("-")[1];
		$(document).off("click", "#" + element.id, scarica);
		$(document).on("click", "#" + element.id, { num_riga: num_riga }, scarica);
	} );
}

function aggancia_callback_dettaglio() {
	
	$(".glyphicon.glyphicon-list-alt").each( function (index, element) {
		var num_riga = element.id.split("-")[1];
		$(document).off("click", "#" + element.id, dettaglio);
		$(document).on("click", "#" + element.id, { num_riga: num_riga }, dettaglio);
	} );
}



$(document).ready( function () {
	
	
			
	if($("#ruolo").val() != "ricercatore"){
		
		colonne = colonne.concat( [ {"data": "modifica"}, {"data": "zip"}, {"data": "elimina"} ] ); 
		
	}
			
								
	
	$("#filtra").click (function()  {
		
		$("#modale").modal("show");
		
		
	} );
	
	$("#canc_ok").click ( do_elimina );
	
	$("#canc_confirm").on("hidden.bs.modal", function(){
		
		tbl.ajax.reload().draw();
		$("#tutti").trigger("click");
		
	});
			
	$("#invia").click( 
		function(event) {
			
			event.preventDefault();
			
			if(event.clientX != undefined){
				$("#latitudine").val("");
				$("#longitudine").val("");
				stato = "ricerca";
			}
					
			$("#modale").modal("hide");
			$("#caricamento").modal({backdrop: "static"});
			tbl.ajax.reload();
			
			return false;
	} );
	
	$("#tutti").click( 
		
		function(event) {
			event.preventDefault();
			document.getElementById("form_ricerca").reset();
			pulisci_scheda();
			stato = "ricerca";
			
			$("#tipo_rilevazione").val("tutte");
			$("#caricamento").modal({backdrop: "static"});
			tbl.ajax.reload();
			
			return false;
			
		});
		
		$("#reset").click( function () {
			
			document.getElementById("form_ricerca").reset();
			$("#numero_vetrine").val("");
			
		} );

} );