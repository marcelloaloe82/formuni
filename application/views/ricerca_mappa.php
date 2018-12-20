<body  >

	<div class="container">
		<div class="top_wrapper">
			<div class="map_container">
				<div id="map" ></div>
			</div>
			<div style="float: right; width: 50%;">
				<div id="dati_rilevazione">
					<div class="title">
						<h3>Dati rilevazione</h3>
					</div>
					<div>
						<span class="didascalia">Codice rilevazione:</span><span id="cod_raccolta"></span>
					</div>
					
					<div>
						<span class="didascalia" style="display: block;">Immagini</span><span id="immagini"></span>
					</div>
					
					
				</div>
			</div>
		</div>
		<div class="table-title"><h3>Tabella rilevazioni</h3></div>
		<div class="button-menu-top">
				<button class="btn btn-default" id="filtra" >Filtra dati</button>
				<button class="btn btn-default" id="tutti" >Tutti i dati</button>
		</div>
		
		<div id="bottom">
			<div id="risultati_ricerca">
				<table id="tbl_risultati_ricerca" width="100%" class="display compact">
					<thead>
						<th>CODICE Rilevazione</th>
						<th>Indirizzo</th>
						<th>DATA</th>
						<th>Immagine</th>
						<th>Dettaglio</th>
						<?php if($ruolo != "ricercatore"): ?>
						<th>Modifica</th>
						<th>Scarica immagini</th>
						<th>Elimina</th>
						<?php endif; ?>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		<input type="hidden" id="ruolo" value="<?php echo $ruolo; ?>">	
		<!-- Modal -->
		<div id="msg_alert" class="modal" role="dialog">
			<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"></h4>
					</div>
					<div class="modal-body"></div>
					 <div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
					</div>
				</div>
			</div>
		</div>
		
		<div id="canc_confirm" class="modal" role="dialog">
			<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"></h4>
					</div>
					<div class="modal-body"></div>
					 <div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
					</div>
				</div>
			</div>
		</div>
		
		<!-- Modal -->
		<div id="conferma_elimina" class="modal" role="dialog">
			<div class="modal-dialog">
				 
				<!-- Modal content-->
				<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Conferma cancellazione</h4>
					</div>
					<div class="modal-body">
						<p>La rilevazione sarà eliminata; se si tratta di un'unità saranno cancellate anche le relative sottoschede.<br>Cliccare su OK per procedere</p>
					</div>
					
					 <div class="modal-footer">
						<button type="button" style="float: left;" class="btn btn-default" id="canc_ok" data-dismiss="modal">OK</button>
						<button type="button" style="float: right;" class="btn btn-default" data-dismiss="modal">Annulla</button>
					</div>
					
				</div>
			</div>
		</div>
		<!-- Modal -->
		<div id="dettaglio" class="modal fade" role="dialog">
		  <div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Dettaglio scheda</h4>
			  </div>
			  <div class="modal-body">
				
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
			  </div>
			</div>

		  </div>
		</div><!-- Modal -->
		<div id="caricamento" class="modal fade" role="dialog">
		  <div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				
				<h4 class="modal-title">Caricamento in corso...</h4>
			  </div>
			  <div class="modal-body">
				<img src="<?php echo base_url(); ?>static/img/loading.gif" id="img_caricamento" style="display: block; margin: auto;" />
			  </div>
			  
			</div>

		  </div>
		</div>
		
		<form id="form_modifica" action="<?php echo base_url(); ?>index.php/modifica" method="post">
			<input type="hidden" name="cod_raccolta" id="cod_raccolta_modifica">
			<input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
		</form>
		
		<form id="form_elimina" action="<?php echo base_url(); ?>index.php/ricerca/elimina" method="post">
			<input type="hidden" name="codice_rilevazione" id="cod_raccolta_elimina">
			<input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
		</form>
		<form id="form_scarica" action="<?php echo base_url(); ?>index.php/ricerca/scarica_immagini_allegati" method="post">
			<input type="hidden" name="codice_rilevazione" id="cod_rilevazione_scarica">
			<input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
		</form>
		
		<input type="hidden" id="codice_op_corrente" value="<?php echo $codice_op; ?>">
		
		<?php echo $form_ricerca; ?>
		
	</div>
	
    <script src="<?php echo base_url(); ?>static/js/datatables/jquery.dataTables.min.js"></script>	
    <script src="<?php echo base_url(); ?>static/js/datatables/dataTables.responsive.min.js"></script>	
    <script src="<?php echo base_url(); ?>static/js/datatables/dataTables.bootstrap.min.js"></script>	
    <script src="<?php echo base_url(); ?>static/js/datatables/responsive.bootstrap.min.js"></script>	
    <script src="<?php echo base_url(); ?>static/js/bootstrap-datepicker/bootstrap-datepicker.js"></script>	
    <script src="<?php echo base_url(); ?>static/js/bootstrap-datepicker/bootstrap-datepicker.it.min.js"></script>	
	<script src="<?php echo base_url(); ?>static/js/fancybox/jquery.fancybox.pack.js"></script>	

    <script src="<?php echo base_url(); ?>static/js/global.js"></script>	
    <script src="<?php echo base_url(); ?>static/js/ricerca_mappa.js"></script>	
	
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAk6IVwu8LAIUse4mj2E1zbpxNsS3iftaE&libraries=places&callback=initMap"async defer></script>
</body>
</html>