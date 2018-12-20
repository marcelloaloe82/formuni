<body ng-app="" style="background-color: white;">
<div class="container">
	<div class="bottom">
	<form id="form_iscrizione" name="form_iscrizione"  >
		
		<!--div id="form-block" -->
			
			<fieldset>
			<div class="iscrizione-titolo">
				<h3>Iscriviti per accedere alla banca dati di Paesaggi Linguistici</h3>
			</div>
			<div class="riga-iscrizione">
				
				<div class="input-group">
					<label>Nome</label>
					<input type="text" name="nome" id="nome" ng-model="nome" required>
				</div>
				<div class="input-group">
					<label> Cognome </label>
					<input type="text" name="cognome" id="cognome" ng-model="cognome" required>
				</div>
			</div>
			<div class="riga-iscrizione">
				
				<div class="input-group">
					<label>Email</label>
					<input type="email" name="email" id="email" ng-model="email" required><br>
					<span style="color:red" ng-show="form_iscrizione.email.$touched && form_iscrizione.email.$invalid">
					  	<span ng-show="form_iscrizione.email.$error.email">Indirizzo mail non valido</span>
					</span>
				</div>
			</div>
			<div class="riga-iscrizione">
				<div class="input-group">
					<label> Password </label>
					<input type="password" name="password" id="password" ng-model="passw" required>
				</div>
				<div class="input-group">
					<label> Conferma Password </label>
					<input type="password" name="conf_password" id="conf_password" ng-model="conf_password" required>
				</div>
			</div>
			<div class="riga-iscrizione">
					<span style="color:red; font-weight: bold;" ng-show="(passw !== conf_password) && (form_iscrizione.password.$touched && form_iscrizione.conf_password.$touched)">
						Le password non coincidono
					</span>
					<span style="color:green; font-weight: bold;" ng-show="(passw === conf_password) && (form_iscrizione.password.$touched && form_iscrizione.conf_password.$touched)">
						Le password coincidono
					</span>
			</div>
			<div class="riga-iscrizione">
				<div class="g-recaptcha" data-sitekey="6LekgxcUAAAAABc_Z-49OOl8JXQvumx7_e7EG8fY"></div>
			</div>	
			
			<div class="riga-iscrizione">
				<button class="btn btn-primary btn-large" id="iscriviti" ng-disabled="form_iscrizione.$invalid">Iscriviti</button>
			</div>
			
			<input type="hidden" id="id_ruolo" name="id_ruolo" value="<?=$id_ruolo; ?>" />
			<input type="hidden" id="token_csrf" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
			</fieldset>

			</form>
		<!--/div-->
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

<script type="text/javascript" src="<?php echo base_url(); ?>static/js/global.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>static/js/iscriviti.js"></script>
</body>
</html>