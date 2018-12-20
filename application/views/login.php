
    <div class="container">
		<div class="pl-header">
			<h3>Paesaggi e lingua</h3>
		</div>
      <form class="form-signin" id="form_login">
        <h2 class="form-signin-heading">Autenticazione</h2>
        <label for="codice_op" class="sr-only">Email</label>
        <input type="text" id="email" name="email" class="form-control" placeholder="Email" required autofocus>
        <label for="password" class="sr-only">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
        <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
        <button class="btn btn-lg btn-primary btn-block" type="submit" id="entra">Entra</button>
      </form>

    </div> <!-- /container -->
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
				<h4 class="modal-title">Verifica in corso...</h4>
			  </div>
			  <div class="modal-body">
				<img src="<?php echo base_url(); ?>static/img/loading.gif" id="img_caricamento" style="display: block; margin: auto;" />
			  </div>
			</div>

		  </div>
		</div>
	 <link href="<?php echo base_url(); ?>static/css/signin.css" rel="stylesheet">
	 <script type="text/javascript" src="<?php echo base_url(); ?>static/js/global.js"></script>
	 <script type="text/javascript" src="<?php echo base_url(); ?>static/js/login.js"></script>
  </body>
</html>
