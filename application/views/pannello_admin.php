<div class="container">
	<div class="bottom">
		<div id="form-block" >
			<div class="riga">
				<h3>Pannello Amministrazione</h3>
			</div>
			<div class="riga">
				<form id="form_approva" name="form_approva"  >
				<div class="input-group">
					<label>Utenti in attesa di approvazione</label>
					<select name="utenti" id="utenti" required size="5" style="width: 400px;">
						<option value="" selected></option>
					<?php foreach ($richieste as $record): ?>
						<option value="<?php echo $record->id; ?>"><?php echo $record->nome. " " . $record->cognome . " - " . $record->email ; ?></option>
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
				<button class="btn btn-primary btn-large" id="approva" disabled="disabled">Approva iscrizione</button>
			</div>
			<div class="riga">
				<button class="btn btn-primary btn-large" id="non_approva" disabled="disabled" style="float:right;">NON approvare</button>
				<button class="btn btn-danger btn-large" id="ban" disabled="disabled"  style="float:right;">Bandisci</button>
			</div>
			<input type="hidden" id="token_csrf" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
			</form>
		</div>
	</div>
</div>

<!-- Modal -->
		<div class="modal fade" id="msg_confirm" role="dialog" data-backdrop="static">
			<div class="modal-dialog">
			
			  <!-- Modal content-->
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title" style="color: red;">ATTENZIONE!</h4>
				</div>
				<div class="modal-body">
				  <p>Se scegli OK questo utente non potrà più iscriversi.<br>Confermi la scelta?</p>
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-default" data-dismiss="modal" id="ban_cancel">Annulla</button>
				  <button type="button" class="btn btn-default" data-dismiss="modal" id="ban_ok" style="float: left;">OK</button>
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