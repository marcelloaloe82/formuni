

<div class="bottom">
<!--div id="map" style="width: 45%; height: 100%;float:left"></div-->
    <div id="pano" style="width: 60%; float:left; height: 100%;"></div>
    <div id="controlli" class="form-group" style="width: 35%; float:left; margin-left: 10px; margin-bottom:0;">
		
		<form id="form_sv" action="<?php echo base_url(); ?>index.php/inserimento" method="post">
			<div class="riga" style="width: 100%;">
				<div class="input-group">
					<label> Indirizzo </label>
					<input type="text" name="indirizzo" id="indirizzo" style="width: 400px;" value="<?php echo $indirizzo; ?>">
				</div>
			</div>
			<div class="riga" style="width: 100%;">
				<div class="input-group">
					
					<label>Latitudine</label>
					<input type="text" name="latitudine" id="latitudine" value="<?php echo $lat; ?>" readonly>
				</div>
				<div class="input-group">
					<label>Longitudine</label>
					<input type="text" name="longitudine" id="longitudine" value="<?php echo $lng; ?>" readonly>
				</div>
			</div>
			<div class="riga" style="width: 100%;">
				<div class="input-group">
					<label>Angolo</label>
					<input type="text" name="angolo" id="angolo" value="<?php echo $angolo; ?>" readonly>
					<input type="hidden" name="pitch" id="pitch" value="<?php echo $pitch; ?>" >
				</div>
				
				<div class="input-group">
					<label>Zoom</label>
					<input type="text" name="zoom" id="zoom" value="<?php echo $zoom; ?>"  readonly>
				</div>
			</div>
			<div class="riga" style="width: 100%;">	
				<div class="input-group">
					<label>Lingua</label>
					<label class="radio-label"><input type="radio" value="italiano" <?php if($lingua == "italiano"): echo "checked"; endif; ?> name="lingua">Solo Italiano</label>
					<label class="radio-label"><input type="radio" value="italiano etnico" <?php if($lingua == "italiano etnico"): echo "checked"; endif; ?> name="lingua">Italiano etnico</label>
					<label class="radio-label"><input type="radio" value="italiano e/o inglese internazionale" <?php if($lingua == "italiano e/o inglese internazionale"): echo "checked"; endif; ?> name="lingua">Italiano e/o inglese internazionale</label>
					<label class="radio-label"><input type="radio" value="altre lingue" <?php if($lingua == "altre lingue"): echo "checked"; endif; ?> name="lingua" >Altre lingue</label>
					<label class="radio-label"><input type="radio" value="misto" <?php if($lingua == "misto"): echo "checked"; endif; ?> name="lingua" >Misto italiano e altre lingue</label>
				</div>
			</div>
			
					<input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />
					<input type="hidden" id="screening_mode" value="<?php echo $screening_mode;?>" />
					<input type="hidden" name="id" value="<?php echo $id_record;?>" />
					<input type="hidden" name="codice_op" value="<?php echo $codice_op; ?>" />
			
	</div>
	<div style="float:left; margin-left: 20px;">
		<button id="salva" class="btn btn-primary" disabled>Salva</button>
	</div>
	<div style="float:right;">
		<button id="insert" class="btn btn-primary" disabled>Usa per l'inserimento</button>
	</div>
	</form>
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
				<p>Inserire un indirizzo per far apparire lo street view (se disponibile)</p>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
			  </div>
			</div>

		  </div>
		</div>
	<script src="<?php echo base_url(); ?>static/js/global.js"></script>	
	
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAk6IVwu8LAIUse4mj2E1zbpxNsS3iftaE&libraries=places&callback=initStuff"async defer></script>
    <script src="<?php echo base_url(); ?>static/js/screening.js"></script>	
  </body>
</html>