

	<div class="container">
			
		<div class="bottom" style="height: inherit;">
			<div id="form-block" style="display:none;">
				<h3><?php if($insert_mode == "edit"): ?>Modifica <?php else: ?>Inserimento <?php endif; ?>dati rilevazione</h3>
				<!--h4>Riempire tutti i campi per inviare i dati</h4-->
				<form  id="form_rilevazione" name="form_rilevazione" method="post" >
					<input type="hidden" id="csrf" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
					<input type="hidden" name="latitudine" id="latitudine" value="<?php echo $dati_inserimento["latitudine"]; ?>"  />
					<input type="hidden" name="longitudine" id="longitudine" value="<?php echo $dati_inserimento["longitudine"]; ?>"  />
					<input type="hidden" name="angolo" id="angolo" value="<?php echo $dati_inserimento["angolo"]; ?>"  />
					<input type="hidden" name="pitch" id="pitch" value="<?php echo (isset($dati_inserimento["pitch"])) ? $dati_inserimento["pitch"] : ""; ?>"  />
					<input type="hidden" name="zoom" id="zoom" value="<?php echo $dati_inserimento["zoom"]; ?>"  />
					<input type="hidden" id="insert_mode" value="<?php echo $insert_mode; ?>"  />
					<div class="riga">
						
						<div class="input-group">
							<label for="unita">codice rilevazione</label>
							<span class="spiega">Inserire sigla assegnata a raccolta</span><br>
							<input type="text" name="sigla_raccolta" id="sigla_raccolta"  placeholder=""  value="<?php echo $dati_inserimento["cod_raccolta"]; ?>" <?php echo $insert_mode == "edit" ? "disabled" : ""; ?> autofocus>
							<?php if($insert_mode == "edit"): ?>
							<input type="hidden" name="cod_raccolta" value="<?php echo $dati_inserimento["cod_raccolta"]; ?>">
							<?php endif; ?>
						</div>
						<div class="input-group">
							<label for="unita">Unità</label>
							<span class="spiega">Inserire tre cifre: da 001 a 999</span><br>
							<input type="text" name="cod_unita" id="unita"   placeholder="001 - 999" <?php if($insert_mode == "edit") echo "disabled"; ?> value="<?php echo $dati_inserimento["codice_unità"]; ?>" >
							<input type="text" id="sigla_unita"  name="sigla_unita" style="display:none;" value="">
							<?php if($insert_mode == "edit"): ?>
							<input type="hidden" name="vecchio_cod_unita" value="<?php echo $dati_inserimento["vecchio_cod_unita"]; ?>">
							<?php endif; ?>
						</div>
						<div class="input-group">
							<label for="unita">Categoria generale</label>
							<span class="spiega"></span><br>
							<select name="categoria_generale" id="categoria_generale" style="height:inherit;">
								<option value="">Selezionare un valore</option>
								<option value="Commerciale" <?php if($dati_inserimento["categoria_generale"] == "Commerciale") echo "selected"; ?>>Commerciale</option>
								<option value="Informale" <?php if($dati_inserimento["categoria_generale"] == "Informale") echo "selected"; ?>>Informale</option>
								<option value="Istituzionale" <?php if($dati_inserimento["categoria_generale"] == "Istituzionale") echo "selected"; ?>>Istituzionale</option>
								<option value="Altro" <?php if($dati_inserimento["categoria_generale"] == "Altro") echo "selected"; ?>>Altro</option>
							</select>
						</div>
					</div>
					
					<div class="riga">
						<div class="input-group">
							<label>DATA rilevazione </label>
							<span class="spiega">(mese e anno)</span><br>
							<input type="text"   name="data" id="data"  placeholder="Data rilevazione" style="display: none;"  >
							<select name="mese" id="mese"   style="height: initial;">
								<option value="" > Selezionare il mese</option>
							<?php foreach($mesi as  $num_mese => $mese): 
								$selected = ($dati_inserimento[ "mese" ] == $num_mese) ? "selected" : "";
							?>
								<option value="<?php echo $num_mese; ?>" <?php echo $selected; ?> ><?php echo $mese; ?></option>
							<?php endforeach; ?>
							</select>
							<input type="text" name="anno" id="anno"  placeholder="AAAA"  value="<?php echo $dati_inserimento["anno"] ; ?>" />
						 </div>
						 <?php if($ruolo_utente == "supervisore" && $insert_mode == "edit"): ?>
						  <input type="hidden"  value="<?php echo $codice_op; ?>" name="codice_op" id="codice_op">
						  <?php endif; ?>
						 <?php if($ruolo_utente != "supervisore"): ?>
						 <input type="hidden"  value="<?php echo $codice_op; ?>" name="codice_op" id="codice_op">
						 <?php else: ?>
						 <div class="input-group">
							<label>Codice operatore</label>
							<span class="spiega"></span><br>
							<input type="text"  size="2" name="codice_op" <?php if($insert_mode != "edit") : ?> id="codice_op"  <?php endif; ?> placeholder="Data rilevazione" <?php if($insert_mode == "edit") echo "disabled" ?> value="<?php echo $codice_op; ?>"  >
						</div>
						<?php endif; ?>
					</div>
					
					
					<div class="riga">
						<div class="input-group">
							<label>Indirizzo</label>
							<span class="spiega">Inserire indirizzo per visualizzare street view<br>(solo per rilevazioni inserite direttamente qui, altrimenti lasciare invariato)</span><br>
							<input type="text"   name="posizione" id="posizione"  style="width: 400px;" placeholder="Indirizzo rilevazione" value="<?php echo $dati_inserimento["indirizzo"] ; ?>"  >
						 
						</div>

					</div>

					
					
					
					
					<div  class="riga" id="sv">
						<div class="input-group" style="height: 300px; ">
							<label>Street view </label>
							<span class="spiega">Aggiustare l’immagine solo in rilevazioni inserite direttamente qui, altrimenti lasciare invariato</span>
							<div id="sv_panorama" style="width: 600px; height: 100%;"></div>
						</div>
					</div>
					
					<div class="riga" style="margin-top: 50px;">
						<div class="input-group">
							<label>CATEGORIA/CONTESTO</label>
							<span class="spiega">Questo campo corrisponde al tipo di unità: compilazione obbligatoria. <br>Spuntare opzione/i appropriate oppure “Altro” e specificare<br> (CTRL + click per sceglierne più di una)</span><br>
							<select multiple     name="categoria[]" id="box_categoria" >
							<?php foreach($categorie as  $categoria ): 
								$selected = (strpos($dati_inserimento[ "categoria" ], $categoria["valore"]) !== false) ? "selected" : "";
							?>
								<option value="<?php echo $categoria["valore"]; ?>" <?php echo $selected; ?> ><?php echo $categoria["valore"]; ?></option>
							<?php endforeach; ?>
							<option value="Altro" <?php echo $dati_inserimento["categoria"] == "Altro" ? "selected" : ""; ?> >Altro</option>
							</select><br>
						   <span class="spiega">Se si è selezionato "Altro" specificare qui sotto</span><br>
							<input type="text" name="categoria_altro" id="categoria_altro"  placeholder="Altro: digitare"  value="<?php echo $dati_inserimento["categoria_altro"]; ?>"	>
						 </div>
						 
						 <div class="input-group">
							
							<label>numero vetrine</label>
							<span class="spiega">Numero di vetrine dell'unità</span><br>
							<select name="numero_vetrine" id="numero_vetrine"   style="height: initial;">
							<?php $sel = ($dati_inserimento[ "numero_vetrine" ] === "") ? "selected" : ""; ?>
								<option value=""  <?php echo $sel; ?>>Selezionare il numero</option>
								<?php for($num = 0; $num <= 9; $num++): 
												$selected = $dati_inserimento[ "numero_vetrine" ] === strval($num) ? "selected" : ""; ?>
									<option value="<?php echo $num; ?>" <?php echo $selected; ?> ><?php echo $num; ?></option>
								<?php endfor; ?>
								
							</select>
							
						 </div>
						 
					 </div>
					 
					<hr />
					
					<div class="riga">
						<div class="input-group">
							<label for="cod_sottounita">Codice SOTTOUNITÀ</label>
							<span class="spiega">Inserire due cifre: da 01 a 99 (se non è sottounità inserire 00)</span><br>
							<input type="text"  name ="val_cod_sottounita" id="cod_sottounita"  placeholder="01 - 99"  <?php if($insert_mode == "edit") echo "disabled"; ?> value="<?php echo $dati_inserimento["val_cod_sottounita"]; ?>"  <?php if($dati_inserimento["val_cod_sottounita"] == "00") echo "readonly"; ?>>
							<input type="text" style="display:none;" name="sigla_sottounita" id="sigla_sottounita"  value=""  >
							<?php if($insert_mode == "edit"): ?>
							<input type="hidden" name="vecchio_cod_sottounita" value="<?php echo $dati_inserimento["vecchio_cod_sottounita"]; ?>">
							<?php endif; ?>
						</div>
						<div class="input-group">
							<label>Tipo di sottounità</label>
							<span class="spiega">Questo campo corrisponde al tipo di sottounità: <br> scegliere un'opzione appropriata, solo una,  <br>oppure “Altro” e specificare. Se non è sottounità selezionare niente</span><br>
						   <select size="4"  name="sottounita" id="box_sottounita" <?php if($dati_inserimento["val_cod_sottounita"] == "00") echo "disabled"; ?>>
							<?php foreach($sottounita as  $tipo ): 
								$selected = (strpos($dati_inserimento[ "tipo" ], $tipo["valore"]) !== false ) ? "selected" : "";
								?>
								<option value="<?php echo $tipo["valore"]; ?>" <?php echo $selected; ?> ><?php echo $tipo["valore"]; ?></option>
							<?php endforeach; ?>
							<option value="Altro" <?php echo $dati_inserimento["tipo"] == "Altro" ? "selected" : ""; ?> >Altro</option>
						   </select><br>
						   <span class="spiega">Se si è selezionato "Altro" specificare qui sotto</span><br>
							<input type="text" name="sottounita_altro" id="sottounita_altro"  placeholder="Altro: digitare" value="<?php echo $dati_inserimento["tipo_altro"]; ?>"  <?php if($dati_inserimento["val_cod_sottounita"] == "00") echo "disabled"; ?>>
						</div>
						
						<div class="input-group">
							<label>DATA rilevazione sottounità </label>
							<span class="spiega">(mese e anno)</span><br>
							<input type="hidden"   name="data_sottounita" id="data_sottounita"  >
							<select name="mese_sottounita" id="mese_sottounita"   style="height: initial;" <?php if($dati_inserimento["val_cod_sottounita"] == "00") echo "disabled"; ?>>
								<option value="" > Selezionare il mese</option>
							<?php foreach($mesi as  $num_mese => $mese): 
								$selected = ($dati_inserimento[ "mese_sottounita" ] == $num_mese) ? "selected" : "";
							?>
								<option value="<?php echo $num_mese; ?>" <?php echo $selected; ?> ><?php echo $mese; ?></option>
							<?php endforeach; ?>
							</select>
							<input type="text" name="anno_sottounita" id="anno_sottounita"  placeholder="AAAA"  value="<?php echo $dati_inserimento["anno_sottounita"] ; ?>" <?php if($dati_inserimento["val_cod_sottounita"] == "00") echo "disabled"; ?> />
						 </div>
					</div>
					
					<hr />
					
					<div class="riga">
						<div class="input-group">
							<label for="cod_segno">Codice segno</label>
							<span class="spiega">Inserire due cifre: da 01 a 99 (se non è segno inserire 00)</span><br>
							<input type="text"  name="val_cod_segno" id="cod_segno"  placeholder="01 - 99" <?php if($insert_mode == "edit") echo "disabled"; ?> value="<?php echo $dati_inserimento["val_cod_segno"]; ?>"   >
							<input type="text" style="display:none;" id="sigla_segno"  name="sigla_segno" value="" >
							<?php if($insert_mode == "edit"): ?>
							<input type="hidden" name="vecchio_cod_segno" value="<?php echo $dati_inserimento["vecchio_cod_segno"]; ?>">
							<?php endif; ?>
						</div>
						<div class="input-group">
							<label>GENERE TESTUALE</label>
							<span class="spiega">Questo campo corrisponde al tipo di segno: spuntare opzione/i appropriate oppure “Altro” e specificare. Se non  è segno (unità o sottounità) selezionare niente<br> (CTRL + click per sceglierne più di una)</span><br>
						   <select multiple  name="genere[]" id="box_genere"  style="width: 650px;" <?php if($dati_inserimento["val_cod_segno"] == "00") echo "disabled"; ?>>
							<?php foreach($generi as  $genere ): 
								$selected = (strpos($dati_inserimento[ "genere" ] , $genere["valore"]) !== false ) ? "selected" : "";
								?>
								<option value="<?php echo $genere["valore"]; ?>" <?php echo $selected; ?> ><?php echo $genere["valore"]; ?></option>
							<?php endforeach; ?>
							<option value="Altro" <?php echo $dati_inserimento["genere"] == "Altro" ? "selected" : ""; ?> >Altro</option>
						   </select>
						   <br>
						   <span class="spiega">Se si è selezionato "Altro" specificare qui sotto</span><br>
						   <input type="text" name="genere_altro" id="genere_altro"  placeholder="Altro: digitare"  value="<?php echo $dati_inserimento["genere_altro"]; ?>"  <?php if($dati_inserimento["val_cod_segno"] == "00") echo "disabled"; ?>>
						</div>
						<div class="input-group">
							<label>DATA rilevazione segno </label>
							<span class="spiega">(mese e anno)</span><br>
							<input type="hidden"   name="data_segno" id="data_segno" >
							<select name="mese_segno" id="mese_segno"   style="height: initial;"  <?php if($dati_inserimento["val_cod_segno"] == "00") echo "disabled"; ?>>
								<option value="" > Selezionare il mese</option>
							<?php foreach($mesi as  $num_mese => $mese): 
								$selected = ($dati_inserimento[ "mese_segno" ] == $num_mese) ? "selected" : "";
							?>
								<option value="<?php echo $num_mese; ?>" <?php echo $selected; ?> ><?php echo $mese; ?></option>
							<?php endforeach; ?>
							</select>
							<input type="text" name="anno_segno" id="anno_segno"   <?php if($dati_inserimento["val_cod_segno"] == "00") echo "disabled"; ?> placeholder="AAAA"  value="<?php echo $dati_inserimento["anno_segno"] ; ?>" />
						 </div>
						<div class="input-group">
							<label>Riferimenti spaziali</label>
							<span class="spiega">Scegliere il tipo di riferimenti presenti, possibili scelte multiple<br> (CTRL + click per selezione multipla)</span><br>
							<select multiple   name="riferimento_spaziale[]" id="riferimento_spaziale"  <?php if($dati_inserimento["val_cod_segno"] == "00") echo "disabled"; ?>>
								<option <?php if($dati_inserimento["riferimento_spaziale"] == "Nessuno") echo "selected"; ?> value="Nessuno">Nessuno</option>
								<option <?php if($dati_inserimento["riferimento_spaziale"] == "Specifico geografico/spaziale") echo "selected"; ?> value="Specifico geografico/spaziale">Specifico geografico/spaziale (es. padano, Monte Rosa)</option>
								<option <?php if($dati_inserimento["riferimento_spaziale"] == "Generico di luogo") echo "selected"; ?> value="Generico di luogo">Generico di luogo (es. angolino, casetta)</option>
								<option <?php if($dati_inserimento["riferimento_spaziale"] == "Immaginario") echo "selected"; ?> value="Immaginario">Immaginario (es. Mercatopoli, paradiso)</option>
							</select>
						</div>
					</div>
					
					<hr />
					
					<div class="riga">
						
						<div class="input-group">
							<label>LINGUE PRESENTI</label>
							<span class="spiega">Scegliere la/le lingue presenti<br> (CTRL + click per sceglierne più di una)</span><br>
						   <select multiple   name="lingue[]" id="box_lingue" >
							<?php foreach($lingue as  $lingua ): 
								$selected = (strpos($dati_inserimento[ "lingue" ] , $lingua["valore"]) !== false) ? "selected" : "";
								?>
								<option value="<?php echo $lingua["valore"]; ?>" <?php echo $selected; ?> ><?php echo $lingua["valore"]; ?></option>
							<?php endforeach; ?>
							<option value="Altro" <?php echo $dati_inserimento["lingue"] == "Altro" ? "selected" : ""; ?> >Altro</option>
						   </select>
						</div>
						
						<div class="input-group">
							<label>LINGUA DOMINANTE </label>
						  <span class="spiega">Scegliere la/le lingue con il maggior numero di parole<br> (CTRL + click per sceglierne più di una)</span><br>
						   <select name="lingua_dominante[]" id="box_lingua_dominante"  multiple >
								<?php foreach($lingue as  $lingua ): 
								$selected = (strpos($dati_inserimento[ "lingua_dominante" ] , $lingua["valore"]) !== false) ? "selected" : "";
								?>
								<option value="<?php echo $lingua["valore"]; ?>" <?php echo $selected; ?> ><?php echo $lingua["valore"]; ?></option>
							<?php endforeach; ?>
							<option value="Altro" <?php echo $dati_inserimento["lingua_dominante"] == "Altro" ? "selected" : ""; ?> >Altro</option>
						   </select>
						   
						</div>
						
						<div class="input-group">
							<label>LINGUA PROMINENTE</label>
							<span class="spiega">Scegliere la/le lingue più significative nel testo<br> (CTRL + click per sceglierne più di una)</span><br>
						   <label class="radio-label">
						   <select name="lingua_prominente[]" id="box_lingua_prominente"  multiple >
								<?php foreach($lingue as  $lingua ): 
								$selected = (strpos($dati_inserimento[ "lingua_prominente" ] , $lingua["valore"]) !== false) ? "selected" : "";
								?>
								<option value="<?php echo $lingua["valore"]; ?>" <?php echo $selected; ?> ><?php echo $lingua["valore"]; ?></option>
							<?php endforeach; ?>
							<option value="Altro" <?php echo $dati_inserimento["lingua_prominente"] == "Altro" ? "selected" : ""; ?> >Altro</option>
						   </select>
						</div>
					</div>
					
					
					<div class="riga">
						<div class="input-group">
							<label>DESCRIZIONE</label>
							<span class="spiega"></span><br>
						   <textarea  name="descrizione" id="descrizione" ><?php echo $dati_inserimento["descrizione"]; ?></textarea>
						</div>
					</div>
					
					<div class="riga">
						
						<div class="input-group">
							<label for="file_immagine">Immagine</label>
							<span class="spiega">(foto della rilevazione: max 5 MB)</span>
							<input type="file" name="file_immagine" id="file_immagine"  >
							
						</div>
						
						<?php if($insert_mode == "edit"): ?>
						<div class="input-group" id="img_preview">
							<label>Anteprima Immagine</label>
							<img src="<?php echo base_url(); ?>static/uploads/<?php echo $dati_inserimento["file_immagine"]; ?>" class="img_preview">
						</div>
						<input type="hidden" name="nome_file_completo" value="<?php echo $dati_inserimento["file_immagine"]; ?>">
						<?php endif; ?>
					</div>
					<input type="hidden"  name="nome_file" id="nome_file"  value="">
					
					
					<div class="riga">
						<div class="input-group">
								<label for="file_immagine">ALTRI FILE ALLEGATI</label>
								<span class="spiega"><strong>Caricare questi file solo nelle schede unità, altrimenti non vengono salvati</strong><br>File audio (max 2 MB) e/o documenti (.doc o .pdf)</span><br>
								<input type="file" multiple name="allegati[]"  id="allegati" >
						</div>
					</form>
						<?php if($insert_mode == "edit" ):  ?>
						<div class="input-group">
							<label>file già allegati</label>
						<?php	if(!empty($dati_inserimento["allegati"])): ?>
										<?php foreach(explode(",", $dati_inserimento["allegati"]) as $allegato): ?>
							<form action="<?php echo base_url(); ?>index.php/modifica/cancella_allegato">
								<a href="<?php echo base_url(); ?>static/uploads/<?php echo $allegato; ?>"><?php echo $allegato; ?></a>
								<input type="hidden" name="allegato" value="<?php echo $allegato; ?>">
								<span class="glyphicon glyphicon-remove" title="cancella allegato" style="cursor:pointer;"></span>
							</form>
							<?php 
							
									endforeach; 
								endif;
							
							?>
						</div>
						<?php endif; ?>
					</div>
						
					<div class="riga">
						 <button class="btn btn-lg btn-primary btn-block" id="invia" >
							Invia
						</button>
						<?php if($insert_mode != "edit"): ?>
						<button type="button" class="btn btn-lg btn-primary btn-block" id="nuova_rilevazione"  style="margin-top:0;">Nuova rilevazione</button>
						<?php endif; ?>
					</div>
					
				
			</div>
			<!--div class="cronologia_ins" style="display:none;">
				<button class="btn btn-default" <?php echo $cronologia_back; ?> id="cronologia_back">&larr; Inserimento Prec.</button>
				<button class="btn btn-default" <?php echo $ultimo_inserimento; ?> id="cronologia_last">Ultimo Inserimento </button>
				<button class="btn btn-default" <?php echo $cronologia_fwd ; ?> id="cronologia_fwd">Inserimento Succ. &rarr;</button>
			</div--> 
		</div>
		<div id="conferma_elimina" class="modal" role="dialog">
			<div class="modal-dialog">
				 
				<!-- Modal content-->
				<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Conferma cancellazione</h4>
					</div>
					<div class="modal-body">
						<p>L'allegato sarà eliminato.<br>Cliccare su OK per procedere</p>
					</div>
					
					 <div class="modal-footer">
						<button type="button" style="float: left;" class="btn btn-default" id="canc_ok" data-dismiss="modal">OK</button>
						<button type="button" style="float: right;" class="btn btn-default" data-dismiss="modal">Annulla</button>
					</div>
					
				</div>
			</div>
		</div>
		<!-- Modal -->
		<div id="inserimento_modal" class="modal fade" role="dialog">
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
				<img src="<?php echo base_url(); ?>static/img/loading.gif" id="img_caricamento" style="margin: auto; display: block;"/>
			  </div>
			</div>

		  </div>
		</div>
	</div>	
    <script src="<?php echo base_url(); ?>static/js/global.js"></script>	
    <script src="<?php echo base_url(); ?>static/js/inserimento.js"></script>	
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAk6IVwu8LAIUse4mj2E1zbpxNsS3iftaE&libraries=places&callback=initGoogleStuff" async defer></script>
	 <!--script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAk6IVwu8LAIUse4mj2E1zbpxNsS3iftaE&libraries=places&callback=initSV"async defer></script-->	
	
	
</body>
</html>