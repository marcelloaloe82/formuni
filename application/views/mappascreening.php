
	<div class="bottom">
		<div class="sv_wrapper">
			<div class="map_container" style="width:100%; float:none; height: 250px;">
				<div id="map"></div>
			</div>
			<?php if($ruolo != "lettore" ) : ?>
			<div class="map_container" style="width:100%; float:none;">	
				<div id="pano1"></div>
			</div>
			<?php endif; ?>
		</div>
		
		<div style="display: inline-block; vertical-align: top; width: 50%;">
		<div id="dati_rilevazione">
			<div class="title">
				<h3>Dati screening</h3>
			</div>
			<form id="form_screening" method="post" action="<?php echo base_url(); ?>index.php/inserimento">
			<div>
				<span class="didascalia">Indirizzo:</span><span id="indirizzo"></span><input type="hidden" name="indirizzo" value="" />
			</div>
			
			<div>
				<span class="didascalia">Latitudine:</span><span id="latitudine"></span><input type="hidden" name="latitudine" value="" />
			</div>
			<div>
				<span class="didascalia">Longitudine:</span><span id="longitudine"></span><input type="hidden" name="longitudine" value="" />
			</div>
			
			<div>
				<span class="didascalia">Lingua:</span><span id="lingua"></span><input type="hidden" name="lingua" value="" />
			</div>
			<input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
			<input type="hidden" name="angolo" value="" />
			<input type="hidden" name="pitch" value="" />
			<input type="hidden" name="zoom" value="" />
			<input type="hidden" name="codice_op_utente" value="<?php echo $codice_op; ?>" />
			<input type="hidden" name="codice_op" value="" />
			<input type="hidden" id="ruolo_utente" value="<?php echo $ruolo; ?>" />
			
		</div>
		<?php if($ruolo != "lettore" && $ruolo != "ricercatore") : ?>
		<div style="margin-top: 20px;">
			<button id="inserimento" class="btn btn-primary" disabled="disabled">Usa per l'inserimento</button>
			<button id="modifica" class="btn btn-primary" disabled="disabled">Modifica</button>
			<button id="elimina" class="btn btn-danger" disabled="disabled">Elimina</button>
			
		</div>
		<?php endif; ?>
		<input type="hidden" name="id">
		</form>
	</div>
	</div>
	
	<!-- Modal -->
		<div id="modale" class="modal fade" role="dialog">
		  <div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"></h4>
			  </div>
			  <div class="modal-body">
				
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
			  </div>
			</div>

		  </div>
		</div>
	
	<div id="caricamento" class="modal fade" role="dialog">
		  <div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			  <div class="modal-header">
				<h4 class="modal-title">Recupero dati in corso...</h4>
			  </div>
			  <div class="modal-body">
				<img src="<?php echo base_url(); ?>static/img/loading.gif" id="img_caricamento" style="display: block; margin: auto;" />
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
						<p>Eliminare lo screening?</p>
					</div>
					
					 <div class="modal-footer">
						<button type="button" style="float: left;" class="btn btn-default" id="canc_ok" data-dismiss="modal">OK</button>
						<button type="button" style="float: right;" class="btn btn-default" data-dismiss="modal">Annulla</button>
					</div>
					
				</div>
			</div>
	</div>
	
	<input type="hidden" id="token_csrf" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
	
	<script src="<?php echo base_url(); ?>static/js/global.js"></script>	
    <script src="<?php echo base_url(); ?>static/js/mappa_screening.js"></script>	
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAk6IVwu8LAIUse4mj2E1zbpxNsS3iftaE&libraries=places&callback=initMap"async defer></script>

</body>
</html>
