
<div class="bottom">
	<div  class="map_container">
		<div id="map"></div>
	</div>
	<div style="float: right; width: 50%;">
		<div id="dati_rilevazione">
			<div class="title">
				<h3>Dati rilevazione<h3>
			</div>
			<div>
				<span class="didascalia">Codice:</span><span id="cod_raccolta"></span>
			</div>
			
			<div>
				<span class="didascalia">Immagine</span><span id="immagini"></span>
			</div>
			
			
		</div>
	</div>
	<!-- Modal -->
		<div id="caricamento" class="modal fade" role="dialog">
		  <div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			  
			  <div class="modal-body">
				<img src="<?php echo base_url(); ?>static/img/loading.gif" style="display: block; margin: auto;" />
			  </div>
			  
			</div>

		  </div>
		</div>
	<input type="hidden" id="token_csrf" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
	<script src="<?php echo base_url(); ?>static/js/fancybox/jquery.fancybox.pack.js"></script>	
	<script src="<?php echo base_url(); ?>static/js/global.js"></script>	
    <script src="<?php echo base_url(); ?>static/js/mappa_rilevazioni.js"></script>	
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAk6IVwu8LAIUse4mj2E1zbpxNsS3iftaE&libraries=places&callback=initMap"async defer></script>
</div>
</body>
</html>