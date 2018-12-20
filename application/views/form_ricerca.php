	<div id="modale" class="modal fade" role="dialog">
		  <div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			  
			  <div class="modal-body">
				<!-- form ricerca -->
					<div class="form-group"  id="form-ricerca-block" ng-app="" ng-init="tipo_rilevazione='tutte'">
						<form class="form-inline" id="form_ricerca" name="form_ricerca" method="post" novalidate>
							
							<div class="input-group">
								<label for="basic-url">codice Rilevazione</label>
							   <input type="text" class="form-control"   size="3" name="cod_raccolta" id="sigla_raccolta" placeholder="codice raccolta" ><br>
							 
							</div>
							
							<div class="input-group">
								<label for="basic-url">codice operatore</label>
							   <input type="text" class="form-control" size="2"    name="cod_operatore" id="cod_operatore" placeholder="codice operatore" ><br>
							 
							</div>
							
							<div class="input-group">
								<label for="basic-url">codice unità</label>
							   <input type="text" class="form-control"    name="sigla" id="sigla_unita" placeholder="001 - 999" >
							</div>
							
							<div class="input-group">
								<label>Tipo di rilevazione</label>
								<span class="spiega">Selezionare Unità, Sottounità o Segni per limitare la ricerca a un solo tipo di rilevazioni</span><br>
							   <select name="tipo_rilevazione" id="tipo_rilevazione" ng-model="tipo_rilevazione"  style="height: initial;">
									<option value="tutte" selected>Tutte</option>
									<option value="unita">Unità</option>
									<option value="sottounita">Sottounità</option>
									<option value="segni">Segni</option>
							   </select>
							</div>
							
							<br>
							<div class="input-group">
								<label for="basic-url">Indirizzo</label>
								<input type="text" class="form-control"  name="posizione" id="posizione" placeholder="Indirizzo rilevazione"  >
							 
							</div>
							
							<div class="input-group">
								<label for="allegati">Ha allegati
								<input type="checkbox"  name="allegati" id="allegati" value="false"></label>
							 
							</div>
							
							
							
							<div class="input-group">
							
								<label>numero vetrine</label>
								<select name="numero_vetrine[]" id="numero_vetrine" ng-model="numero_vetrine"  multiple size="3">
								
								<?php for($num = 0; $num <= 9; $num++):  ?>
									<option value="<?php echo $num; ?>" ><?php echo $num; ?></option>
								<?php endfor; ?>
								</select>
							
							</div>
							<br>
							<div class="input-group">
								<label for="basic-url">DATA DA</label>
								<input type="text" class="form-control"   name="data_da" id="data_da"  placeholder="AAAA-MM"  >
							 </div>

							<div class="input-group">
								<label for="basic-url">DATA a</label>
								<input type="text" class="form-control"   name="data_a" id="data_a"  placeholder="AAAA-MM"  >
							 </div>

							<div class="input-group">
								<label for="unita">Categoria generale</label>
								<span class="spiega"></span><br>
								<select name="categoria_generale" id="categoria_generale"  style="height: initial;">
									<option value="">---</option>
									<option value="Commerciale">Commerciale</option>
									<option value="Informale">Informale</option>
									<option value="Istituzionale">Istituzionale</option>
									<option value="Altro">Altro</option>
								</select>
							</div>
					

							<div class="input-group">
								<label for="basic-url">CATEGORIA/CONTESTO</label>
								<select size="5" class="form-control"   multiple name="categoria[]" id="box_categoria">
									
								<?php foreach($categorie as  $categoria ): 	?>
										<option value="<?php echo $categoria["valore"]; ?>" ><?php echo $categoria["valore"]; ?></option>
								<?php endforeach; ?>
								<option value="Altro">Altro</option>
								</select>
							 </div>
							
							<div class="input-group">
								<label>Riferimento spaziale</label>
								<span class="spiega">Selezionare "segni" per abilitare</span><br>
								<select  size="5" multiple  name="riferimento_spaziale[]" id="box_riferimento_spaziale" ng-disabled="tipo_rilevazione != 'segni'">
									
									<option value="Nessuno">Nessuno</option>
									<option value="Specifico geografico/spaziale">Specifico geografico/spaziale</option>
									<option value="Generico di luogo">Generico di luogo</option>
									<option value="Immaginario">Immaginario</option>
								</select>
							</div>
							
							<div class="input-group">
								<label for="basic-url">tipo di SOTTOUNITÀ</label>
								<span class="spiega">Selezionare "sottounità" per abilitare</span>
							   <select size="5" class="form-control" multiple  name="tipo[]" id="box_sottounita"  ng-disabled="tipo_rilevazione != 'sottounita'">
									
								<?php foreach($sottounita as  $tipo ):  ?>
									<option value="<?php echo $tipo["valore"]; ?>"><?php echo $tipo["valore"]; ?></option>
								<?php endforeach; ?>
								<option value="Altro">Altro</option>
							   </select>
							</div>
							
							<div class="input-group">
								<label for="basic-url">GENERE TESTUALE</label>
								<span class="spiega">Selezionare "segni" per abilitare</span>
							   <select size="5" class="form-control"  multiple name="genere[]" id="box_genere" ng-disabled="tipo_rilevazione != 'segni'" >
									
								<?php foreach($generi as  $genere ): 	?>
									<option value="<?php echo $genere["valore"]; ?>"><?php echo $genere["valore"]; ?></option>
								<?php endforeach; ?>
								<option value="Altro">Altro</option>
							   </select>
							</div>
							
							<div class="input-group">
								
									<label for="basic-url">LINGUE PRESENTI</label>
								<div>	
									<input type="checkbox"  name="tuttelingue" id="tuttelingue" value="false"></label>
									<label style="display:inline; text-transform:none;">Cerca rilevazioni con tutte le lingue selezionate
								</div>
							 <select size="5" multiple class="form-control"  name="lingue[]" id="box_lingue" >
									
								<?php foreach($lingue as  $lingua ): ?>
									<option value="<?php echo $lingua["valore"]; ?>"><?php echo $lingua["valore"]; ?></option>
								<?php endforeach; ?>
								<option value="Altro">Altro</option>
							   </select>
							</div>
							
							<div class="input-group">
								
							</div>
							
							<div class="input-group">
								<label for="basic-url">LINGUA DOMINANTE </label>
							   <select size="5" multiple class="form-control"   name="lingua_dominante[]" id="box_lingua_dominante" >
								
								<?php foreach($lingue as  $lingua ): ?>
									<option value="<?php echo $lingua["valore"]; ?>"><?php echo $lingua["valore"]; ?></option>
								<?php endforeach; ?>	
								<option value="Altro">Altro</option>
							   </select>
							</div>
							
							<div class="input-group">
								<label for="basic-url">LINGUA PROMINENTE</label>
							   <select size="5" multiple class="form-control"    name="lingua_prominente[]" id="box_lingua_prominente">
								<?php foreach($lingue as  $lingua ): ?>
									<option value="<?php echo $lingua["valore"]; ?>"><?php echo $lingua["valore"]; ?></option>
								<?php endforeach; ?>	
								<option value="Altro">Altro</option>
							   </select>
							</div>
							
							<div class="input-group">
								<label for="basic-url">descrizione contiene la parola</label>
								<input type="text" class="form-control"   name="descrizione" id="descrizione" placeholder="scrivi parola chiave">
							 </div>
							
							
							<input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" id="token_csrf" />
							<input type="text" style="display: none;"  name="latitudine" id="latitudine" value="" />
							<input type="text" style="display: none;" name="longitudine" id="longitudine" value="" />
						</form>
						<div style="display: inline-block;">
							 <button class="btn btn-primary" id="invia" >
								Invia
							</button>
						</div>
							
						<div style="display: inline-block; margin-left: 430px;">
							 <button class="btn btn-primary" id="reset" ng-click="tipo_rilevazione = 'tutte'">
								Reset
							</button>
						</div>
					</div>
					
					
			  </div>
			  
			</div>

		  </div>
		</div>