app_dir = "ricerca/";
var baseurl = root_dir + app_dir;

var tbl;
var arr_marker = [];

function carica_marker(arr_punti, map){
	
		var myLatLng;
		
		for (index in arr_punti){
			
			if(arr_punti[index]["cod_raccolta"].indexOf("-00-00") > 0  ){
			
				myLatLng = { lat: parseFloat(arr_punti[index]["latitudine"]), lng: parseFloat(arr_punti[index]["longitudine"]) };
								
				arr_marker[index] = crea_marker(myLatLng, map, arr_punti[index]["cod_raccolta"]);
			}
		
		}
			
}

function carica_img ( json ) {
	
	for ( var i=0, ien=json.data.length ; i<ien ; i++ ) {
			
			json.data[i]["file_thumb"] = '<img src="' + img_dir + json.data[i]["file_thumb"]  + '" class="img_thumb">';
			
	}
	
	return json.data;
}





$(document).ready(function(){	
	
	
	
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
				"dataSrc": carica_img,
				"data": get_form_data,
				"contentType": false,
				"processData": false
			},
			
			pageLength: 25,
			responsive: true,
			searching: false,
			dom: "litip",
			
			columns: [
				{ "data": "cod_raccolta" },
				{ "data": "posizione" },
				{ "data": "data" },
				{ "data": "file_thumb" }
				
			]
	});
	

	
	
	tbl.on("xhr", function() {
		setTimeout(function() {
			$("#modale").modal("hide");
		}, 2000);
	} );
								
	
		
	$("#filtra").click (function()  {
		
		$("#modale").modal("show");
		$("#img_caricamento").hide();
		$("#form-ricerca-block").show();
		
		
	} );
	
	
		
	$("#invia").click( 
		function(e) {
			
			e.preventDefault();
			
			$("#form-ricerca-block").hide();
			$("#img_caricamento").show();
			$("#img_caricamento").css({"display": "block", "margin": "auto"});
			$("#modale").modal({"backdrop": "static"});
			tbl.ajax.reload();
			
			return false;
	} );
	
	$("#tutti").click( 
		
		function(event) {
			event.preventDefault();
			document.getElementById("form_ricerca").reset();
			
			$("#img_caricamento").show();
			$("#img_caricamento").css({"display": "block", "margin": "auto"});
			$("#modale").modal({"backdrop": "static"});
			tbl.ajax.reload();
			
			return false;
			
		});

	$("#reset").click( function () {
			
			document.getElementById("form_ricerca").reset();
			
	} );
		
});
