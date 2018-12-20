<body  >

	<div class="container">
		
		
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
						
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
			
		<!-- Modal -->
		<div id="img_fullsize" class="modal" role="dialog">
			<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
				  
					<div class="modal-body"></div>
					
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
		</div>
		<div id="msg_alert" class="modal fade" role="dialog">
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
		<?php echo $form_ricerca; ?>
		</div>
	</div>
	
    <script src="<?php echo base_url(); ?>static/js/datatables/jquery.dataTables.min.js"></script>	
    <script src="<?php echo base_url(); ?>static/js/datatables/dataTables.responsive.min.js"></script>	
    <script src="<?php echo base_url(); ?>static/js/datatables/dataTables.bootstrap.min.js"></script>	
    <script src="<?php echo base_url(); ?>static/js/datatables/responsive.bootstrap.min.js"></script>	

    <script src="<?php echo base_url(); ?>static/js/global.js"></script>	
    <script src="<?php echo base_url(); ?>static/js/ricerca.js"></script>	
	
</body>
</html>