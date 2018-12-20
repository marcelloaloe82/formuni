<?php  $active = ""; ?>
<nav class="navbar navbar-inverse">
  
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Paesaggi e lingua</a>
    </div>
    <ul class="nav navbar-nav">
	<?php if(in_array($ruolo, ["operatore", "supervisore", "admin"]) === true): ?>
      <li <?php uri_string() == "inserimento" ? $active = "class=\"active\"" :  $active =  ""; echo $active; ?>>
		<a href="<?php echo base_url(); ?>index.php/inserimento">Ins. rilevazione</a></li>
	
      <li <?php uri_string() ==  "screening" ? $active =  "class=\"active\"" :  $active =  ""; echo $active; ?>><a href="<?php echo base_url(); ?>index.php/screening">Ins. screening </a></li>
	  
      <li <?php uri_string() == "MappaScreening" ? $active =  "class=\"active\"" : $active =   ""; echo $active; ?>><a href="<?php echo base_url(); ?>index.php/MappaScreening">Mappa screening</a></li>
      <li <?php uri_string() == "ricerca" ? $active =  "class=\"active\"" :  $active =  "";  echo $active; ?>><a href="<?php echo base_url(); ?>index.php/ricerca">Ricerca rilevazioni</a></li>
    <?php endif; ?>
	
	<?php if($ruolo == "lettore"): ?>
		 <li <?php uri_string() == "MappaScreening" ? $active =  "class=\"active\"" : $active =   ""; echo $active; ?>><a href="<?php echo base_url(); ?>index.php/MappaScreening">Mappa screening</a></li>
		<li <?php uri_string() == "ricerca" ? $active =  "class=\"active\"" :  $active =  "";  echo $active; ?>><a href="<?php echo base_url(); ?>index.php/ricerca">Ricerca rilevazioni</a></li>
		<li <?php uri_string() == "mappa" ? $active =  "class=\"active\"" :  $active =  "";  echo $active; ?>><a href="<?php echo base_url(); ?>index.php/mappa">Mappa  rilevazioni</a></li>
	<?php endif; ?>
	
	
	 <?php if($ruolo == "ricercatore"): ?>
		<li <?php uri_string() == "MappaScreening" ? $active =  "class=\"active\"" : $active =   ""; echo $active; ?>><a href="<?php echo base_url(); ?>index.php/MappaScreening">Mappa screening</a></li>
		<li <?php uri_string() == "ricerca" ? $active =  "class=\"active\"" :  $active =  "";  echo $active; ?>><a href="<?php echo base_url(); ?>index.php/ricerca">Ricerca rilevazioni</a></li>
	<?php endif; ?>
	
	<?php if($ruolo == "supervisore" || $ruolo == "admin"): ?>
		<li <?php uri_string() == "admin/approvazione_utenti" ? $active =  "class=\"active\"" :  $active =  "";  echo $active; ?>><a href="<?php echo base_url(); ?>index.php/admin/approvazione_utenti">Approvazione utenti</a></li>
		<li <?php uri_string() == "admin/lista_utenti" ? $active =  "class=\"active\"" :  $active =  "";  echo $active; ?>><a href="<?php echo base_url(); ?>index.php/admin/lista_utenti">Lista utenti approvati</a></li>
	<?php endif; ?>
    </ul>
	
	<ul class="nav navbar-nav navbar-right" style="margin-right: 0;">
      <li><a href="<?php echo base_url(); ?>index.php/pl/logout"><span class="glyphicon glyphicon-log-out" id="logout"></span>Logout </a></li>
	</ul>
</nav>