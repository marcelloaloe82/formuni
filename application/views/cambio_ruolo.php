<div class="container">
	<div class="bottom">
		<div id="form-block" >
			<div class="riga">
				<h3>Lista utenti e Cambio ruoli</h3>
			</div>
			<div class="riga">
				<form id="form_approva" name="form_approva"  >
				<div class="input-group">
					<label>Utenti in attesa di approvazione</label>
					<select name="utenti" id="utenti" required size="5" style="width: 550px;">
						<option value="" selected></option>
					<?php foreach ($utenti as $record): ?>
						<option value="<?php echo $record->id; ?>"><?php echo $record->nome. " " . $record->cognome . " - " . $record->email . " - " . $record->codice_op . "  |  " . $record->ruolo ; ?></option>
					<?php endforeach; ?>
					</select>
				</div>
				<div class="input-group">
					<label> ruolo  da assegnare</label>
					
						<label class="radio-label"><input type="radio" name="ruolo"  required  value="operatore">Operatore</label>
						<label class="radio-label"><input type="radio" name="ruolo"  required  value="ricercatore">Ricercatore</label>
					
				</div>
			</div>
			<div class="riga">
				<button class="btn btn-primary btn-large" id="cambia" disabled="disabled">Cambia ruolo</button>
			</div>
			<div class="riga">
				
				<button class="btn btn-danger btn-large" id="canc_utente" disabled="disabled"  style="float:right;">Cancella utente</button>
			</div>
			<input type="hidden" id="token_csrf" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
			</form>
		</div>
	</div>
</div>

<!-- Modal -->
		<div class="modal fade" id="msg_canc_confirm" role="dialog" data-backdrop="static">
			<div class="modal-dialog">
			
			  <!-- Modal content-->
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title" style="color: red;">ATTENZIONE!</h4>
				</div>
				<div class="modal-body">
				  <p>Se scegli OK questo utente sarà cancellato dall'elenco degli iscritti<br>Confermi la scelta?</p>
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-default" data-dismiss="modal" id="canc_cancel">Annulla</button>
				  <button type="button" class="btn btn-default" data-dismiss="modal" id="canc_ok" style="float: left;">OK</button>
				</div>
			  </div>
			  
			</div>
		 </div>
		 
		<!-- Modal -->
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
<script type="text/javascript" src="<?php echo base_url(); ?>static/js/global.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>static/js/admin.js"></script>
</body>
</html>