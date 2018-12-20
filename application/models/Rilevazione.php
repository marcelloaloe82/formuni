<?php

class Rilevazione extends CI_Model{

	

	public function __construct(){
                
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
				
    }

	private function esiste_raccolta( $cod_raccolta, $cod_operatore ){
		
		$str_query = "SELECT count(*) as num_record FROM unita WHERE cod_raccolta = '$cod_raccolta' and cod_operatore = '$cod_operatore'";
		
		$result = intval($this->db->query($str_query)->result_array()[0]["num_record"]);
		
		
		if($result === 0)
			return false;
		
		else return true;
	}
	
	private function esiste( $cod_raccolta, $codice, $tabella, $cod_operatore, $cod_unita="" ){
		
		$check_unita = "";
		
		if($cod_unita !== "")
			$check_unita = " AND cod_unita = '$cod_unita' ";
		
		if($tabella != "unita")
			$campo_check = "sigla";
		else $campo_check = "codice";
		
		$str_query = "SELECT count(*) as num_record FROM $tabella WHERE cod_raccolta = '$cod_raccolta' AND $campo_check = '$codice'  AND cod_operatore = '$cod_operatore' $check_unita";
		
		// var_dump ($str_query); 
		$query_res = $this->db->query($str_query)->result_array()[0];
		
		if(intval( $query_res["num_record"] ) === 0)
			return false;
		
		else return true;
		
	}
	
	public function inserisci(){
		
		$param_raccolta = [];
		$param_sottounita = [];
		$param_unita= [];
		$param_segno = [];
		
		$nomi_file_allegati = [];
		
		$config_upload = [];
		$config_upload['upload_path']          = 'static/uploads/';
		$config_upload['allowed_types']        = 'gif|jpg|jpeg|png|mp3|doc|docx|pdf';
		//$config['max_size']             = 1024;
		// $config['max_width']            = 1024;
		// $config['max_height']           = 768;

		$this->load->library('upload', $config_upload);
		
		
		if($this->upload->do_upload("file_immagine") === false){
				$error_msg =  $this->upload->display_errors();
				return "Nessun file caricato, errore:  $error_msg";
		}
		
		$cod_raccolta 					= $this->input->post("sigla_raccolta");
		$codice_op 						= $this->input->post("codice_op");
		$sigla_unita 						= $this->input->post("sigla_unita");
		$cod_unita 							= $this->input->post("cod_unita");
		$cod_sottounita 					= $this->input->post("val_cod_sottounita");
		$sigla_sottounita 				= $this->input->post("sigla_sottounita");
		$sigla_segno 						= $this->input->post("sigla_segno");
		$cod_segno 						= $this->input->post("val_cod_segno");
		

		$this->load->library('image_lib');
		
		$config['image_library'] = 'gd2';
		
		$config['create_thumb'] = true;
		$config['maintain_ratio'] = true;
		$config['width']         = 150;		
		
		
		$nome_file = $this->input->post("nome_file");
		$nome_file_uploadato = $this->upload->data('file_name');
		$path_file = $this->upload->data('file_path');

		$est_file_uploadato = substr($nome_file_uploadato, strrpos($nome_file_uploadato, "."));
		$nuovo_nome_file = $nome_file . $est_file_uploadato;
		$nuovo_nome_file_thumb = $nome_file . "_thumb" . $est_file_uploadato;
		// rename( $path_file . $nome_file_uploadato, $path_file . $nuovo_nome_file);
		
		
		$config['source_image'] = $path_file .$nome_file_uploadato;
		$config['new_image'] = $nuovo_nome_file;
		$this->image_lib->initialize($config);
		
		if ( ! $this->image_lib->resize()){
			
			return $this->image_lib->display_errors();
		}
		
		$config['width']         = 600;		
		$config['create_thumb']         = false;		
		$this->image_lib->initialize($config);

		if ( ! $this->image_lib->resize()){
			
			return $this->image_lib->display_errors();
		}
		
		unlink($path_file . $nome_file_uploadato);
		
		$res = $this->upload->do_multi_upload("allegati") ;
		
		if($res !== false){
			$upload_data = $this->upload->get_multi_upload_data();
                
			for($i=0; $i < count($upload_data) ; $i++){
				$nomi_file_allegati[] = $upload_data[$i]["file_name"];
			}
		}
		
		// var_dump($nomi_file_allegati); die;
		
		if($cod_sottounita == "00" && $cod_segno == "00"){
	
			if($this->esiste( $cod_raccolta, $cod_unita, "unita", $codice_op ) === false){
					
					$param_unita["posizione"]					 = $this->input->post("posizione");
					$param_unita["cod_operatore"]			 = $codice_op;
					$param_unita["cod_raccolta"]			 = $cod_raccolta;
					$param_unita["codice"]				 		 = $cod_unita;
					$param_unita["categoria"]					 = implode(",", $this->input->post("categoria"));
					$param_unita["categoria_generale"]	 = $this->input->post("categoria_generale");
					$param_unita["latitudine"]					 =	$this->input->post("latitudine");
					$param_unita["longitudine"]				=  $this->input->post("longitudine");
					$param_unita["angolo"]						=  $this->input->post("angolo");
					$param_unita["zoom"]						=  $this->input->post("zoom");
					$param_unita["descrizione"]				=  $this->input->post("descrizione");
					$param_unita["numero_vetrine"]		=  $this->input->post("numero_vetrine");
					$param_unita["data"]							=  $this->input->post("data");
					$param_unita["file_immagine"]			=  $nuovo_nome_file;
					$param_unita["file_thumb"]				=  $nuovo_nome_file_thumb;
					$param_unita["allegati"]					    =   implode(",",$nomi_file_allegati);
					$param_unita["sigla"]							=  $this->input->post("sigla_unita");
					$param_unita["lingue"]					 	 = implode(",",$this->input->post("lingue"));
					$param_unita["lingua_dominante"]		 =	implode(",", $this->input->post("lingua_dominante"));
					$param_unita["lingua_prominente"]	= 	implode(",", $this->input->post("lingua_prominente"));
					
					if($param_unita["categoria"] == "Altro")
						$param_unita["categoria_altro"] = $this->input->post("categoria_altro");
					
					return $this->db->insert('unita', $param_unita);
			}
			
		}
		
		
		if($cod_sottounita != "00" && $cod_segno == "00"){
			
			if($this->esiste_raccolta($cod_raccolta, $codice_op)=== false){
				return "Codice rilevazione $cod_raccolta inesistente per il codice operatore $codice_op, inserire prima una unità con questo codice";
			}
			
			if($this->esiste($cod_raccolta, "$cod_sottounita-$cod_segno", "rilevazioni_sottounita",$codice_op, $cod_unita) === true){
				return "Una sottounità con questo codice è già stata inserita per questa unità!";
			}
			
			$param_sottounita["cod_operatore"]			 = $codice_op;
			$param_sottounita["codice"]			 			 = $cod_sottounita;
			$param_sottounita["sigla"]		 					 = $sigla_sottounita;
			$param_sottounita["cod_raccolta"]			 = $cod_raccolta;
			$param_sottounita["cod_unita"]			 		 = $cod_unita;
			$param_sottounita["tipo"] 							 = $this->input->post("sottounita");
			$param_sottounita["data"]							 = $this->input->post("data_sottounita");
			$param_sottounita["lingue"]					 	 = implode(",",$this->input->post("lingue"));
			$param_sottounita["lingua_dominante"]		 =	implode(",", $this->input->post("lingua_dominante"));
			$param_sottounita["lingua_prominente"]	 =	implode(",", $this->input->post("lingua_prominente"));
			$param_sottounita["file_immagine"]			 =  $nuovo_nome_file;
			$param_sottounita["file_thumb"]				 =  $nuovo_nome_file_thumb;
			$param_sottounita["descrizione"]				 =  $this->input->post("descrizione");
			
			if($param_sottounita["tipo"]  == "Altro")
				$param_sottounita["tipo_altro"]= $this->input->post("sottounita_altro");
			
			return $this->db->insert('rilevazioni_sottounita', $param_sottounita);
		}
		
		
		
		if($cod_segno != "00"){
			
			if(!$this->esiste_raccolta($cod_raccolta,  $codice_op)){
				return "Codice rilevazione $cod_raccolta inesistente per il codice operatore $codice_op, inserire prima una unità con questo codice";
			}
			
			if($this->esiste($cod_raccolta, "$cod_sottounita-$cod_segno", "segni",  $codice_op, $cod_unita) === true){
				return "Un segno con questo codice è già stato inserito per questa unità!";
			}
			
			$param_segno["lingue"]					 	 	= implode(",",$this->input->post("lingue"));
			$param_segno["cod_operatore"]			= $codice_op;
			$param_segno["cod_raccolta"]			 	= $cod_raccolta;
			$param_segno["codice"]				 		= $cod_segno;
			$param_segno["sigla"]							= $sigla_segno;
			$param_segno["genere"]					 	= implode(",",$this->input->post("genere"));
			$param_segno["cod_unita"]			 		= $cod_unita;
			$param_segno["lingua_dominante"]		=	implode(",", $this->input->post("lingua_dominante"));
			$param_segno["lingua_prominente"]		= 	implode(",", $this->input->post("lingua_prominente"));
			$param_segno["file_immagine"]			=  $nuovo_nome_file;
			$param_segno["file_thumb"]					=  $nuovo_nome_file_thumb;
			$param_segno["data"]							=  $this->input->post("data_segno");
			$param_segno["riferimento_spaziale"]  = implode(",", $this->input->post("riferimento_spaziale"));
			$param_segno["descrizione"]				=  $this->input->post("descrizione");
			
			if($param_segno["genere"]  == "Altro")
				$param_segno["genere_altro"] = $this->input->post("genere_altro");
			
			return $this->db->insert('segni', $param_segno);
		}
		
		return "Codice unità già inserito per questo codice rilevazione";
	}
	
	public function get_filename($thumb, $cod_raccolta, $cod_operatore, $cod_unita, $cod_sottounita="", $cod_segno=""){
		
		$campo = $thumb === true ? "file_thumb" : "file_immagine";
		
		if ($cod_sottounita == "" && $cod_segno == ""){
			$tabella = "unita";
			$str_query = "SELECT $campo  FROM  $tabella WHERE codice = '$cod_unita' and cod_raccolta = '$cod_raccolta' and cod_operatore = '$cod_operatore'";
		}
		
		if ($cod_sottounita != "" && $cod_segno == ""){
			$tabella = "rilevazioni_sottounita";
			$str_query = "SELECT $campo FROM  $tabella WHERE codice = '$cod_sottounita' and cod_raccolta = '$cod_raccolta' and cod_operatore = '$cod_operatore'  and cod_unita = '$cod_unita'";
			
		}
		
		if ($cod_sottounita != "" && $cod_segno != ""){
			$tabella = "segni";
			$str_query = "SELECT $campo FROM  $tabella WHERE sigla = '$cod_sottounita-$cod_segno' and cod_raccolta = '$cod_raccolta' and cod_operatore = '$cod_operatore'  and cod_unita = '$cod_unita'"; 
		}
	
		return $this->db->query($str_query)->result_array()[0]["$campo"];
	}
	
	public function modifica(){
		
		$param_sottounita = [];
		$param_unita= [];
		$param_segno = [];
		$nomi_file_allegati = [];
		
		$cod_raccolta 					= $this->input->post("cod_raccolta");
		$codice_op 						= $this->input->post("codice_op");
		$cod_unita 							= $this->input->post("vecchio_cod_unita");
		$cod_sottounita 					= $this->input->post("vecchio_cod_sottounita");
		$cod_segno 						= $this->input->post("vecchio_cod_segno");
		
		$vecchio_cod_unita			= $this->input->post("vecchio_cod_unita");
		$vecchio_cod_sottounita 	= $this->input->post("vecchio_cod_sottounita");
		$vecchio_cod_segno 		= $this->input->post("vecchio_cod_segno");
		
		$config_upload = [];
		$config_upload['upload_path']          = 'static/uploads/';
		$config_upload['allowed_types']        = 'gif|jpg|jpeg|png|mp3|doc|docx|pdf';
		
		$this->load->library('upload', $config_upload);
		
		if($this->upload->do_upload("file_immagine") !== false){
				
			$nome_file = $this->input->post("nome_file");
			$nome_file_uploadato = $this->upload->data('file_name');
			$path_file = $this->upload->data('file_path');
			// var_dump($path_file);
			

			$est_file_uploadato = substr($nome_file_uploadato, strrpos($nome_file_uploadato, "."));
			$nuovo_nome_file = $nome_file . $est_file_uploadato;
			$nuovo_nome_file_thumb = $nome_file . "_thumb" . $est_file_uploadato;
			
			if($cod_sottounita == "00" && $cod_segno == "00"){
				
				unlink(DIRECTORY_BASE . "static/uploads/" . $this->get_filename(false, $cod_raccolta, $codice_op, $cod_unita));
				unlink(DIRECTORY_BASE . "static/uploads/" . $this->get_filename(true, $cod_raccolta, $codice_op, $cod_unita));
				
			}
			
			if($cod_sottounita != "00" && $cod_segno == "00"){
				
				unlink(DIRECTORY_BASE . "static/uploads/" . $this->get_filename(false, $cod_raccolta, $codice_op, $cod_unita, $cod_sottounita));
				unlink(DIRECTORY_BASE . "static/uploads/" . $this->get_filename(true, $cod_raccolta, $codice_op, $cod_unita, $cod_sottounita));
				
			}
			
			if($cod_segno != "00"){
				
				unlink(DIRECTORY_BASE . "static/uploads/" . $this->get_filename(false, $cod_raccolta, $codice_op, $cod_unita, $cod_sottounita, $cod_segno));
				unlink(DIRECTORY_BASE . "static/uploads/" . $this->get_filename(true, $cod_raccolta, $codice_op, $cod_unita, $cod_sottounita, $cod_segno));
				
			}
			
			$this->load->library('image_lib');
			
			$config['image_library'] = 'gd2';
			
			$config['create_thumb'] = true;
			$config['maintain_ratio'] = true;
			$config['width']         = 150;		
			$config['source_image'] = $path_file . $nome_file_uploadato;
			$config['new_image'] = $nuovo_nome_file;
			$this->image_lib->initialize($config);
			
			if ( ! $this->image_lib->resize()){
				
				return $this->image_lib->display_errors();
			}
			
			$config['width']         = 600;		
			$config['create_thumb']         = false;		
			$this->image_lib->initialize($config);
			
			if ( ! $this->image_lib->resize()){
			
				return $this->image_lib->display_errors();
			}	
		
			unlink($path_file . $nome_file_uploadato);
		}
		
		else{
			$nuovo_nome_file = "";
		    $nuovo_nome_file_thumb = "";
		}
		
		$res = $this->upload->do_multi_upload("allegati") ;
		
		if($res !== false){
			
			$upload_data = $this->upload->get_multi_upload_data();
                
			for($i=0; $i < count($upload_data) ; $i++){
				$nomi_file_allegati[] = $upload_data[$i]["file_name"];
			}
		}
		// var_dump($nomi_file_allegati); die;
		
		if($cod_sottounita == "00" && $cod_segno  == "00"){
			
			
			$param_unita["posizione"]					  = $this->input->post("posizione");
			$param_unita["categoria"]					  = implode(",", $this->input->post("categoria"));
			$param_unita["categoria_generale"]	 = $this->input->post("categoria_generale");
			$param_unita["latitudine"]					 =	$this->input->post("latitudine");
			$param_unita["longitudine"]				 = $this->input->post("longitudine");
			$param_unita["descrizione"]				 = $this->input->post("descrizione");
			$param_unita["numero_vetrine"]		 = $this->input->post("numero_vetrine");
			$param_unita["lingue"]					 	 = implode(",",$this->input->post("lingue"));
			$param_unita["lingua_dominante"]		 =	implode(",", $this->input->post("lingua_dominante"));
			$param_unita["lingua_prominente"]	 =	implode(",", $this->input->post("lingua_prominente"));
			$param_unita["data"]							 = $this->input->post("data");
			
			
			
			if(!empty($nuovo_nome_file)){
			
				$param_unita["file_immagine"]			=  $nuovo_nome_file;
				$param_unita["file_thumb"]				=  $nuovo_nome_file_thumb;
				
				
			}
			
		
			
			
			if(!empty($nomi_file_allegati)){
				
				$arr_allegati_attuali = $this->get_allegati($cod_unita, $codice_op, $cod_raccolta);
				$arr_allegati = array_merge($arr_allegati_attuali, $nomi_file_allegati);
				// var_dump($arr_allegati); die;
				$str_nuovi_allegati = implode(",", $arr_allegati);
				$param_unita["allegati"] = $str_nuovi_allegati;
			}
			
			if($param_unita["categoria"] == "Altro")
					$param_unita["categoria_altro"] = $this->input->post("categoria_altro");
			
						
			
			$this->db->where("cod_operatore", $codice_op);
			$this->db->where("codice", $vecchio_cod_unita);
			$this->db->where("cod_raccolta", $cod_raccolta);
			
			return $this->db->update('unita', $param_unita);
			 // $this->db->update('unita', $param_unita);
			// var_dump($this->db->last_query()); die;
		}
		
		
		if($cod_sottounita != "00" && $cod_segno == "00"){
			
			
			$param_sottounita["tipo"] 							 = $this->input->post("sottounita");
			$param_sottounita["lingue"]					 	 = implode(",",$this->input->post("lingue"));
			$param_sottounita["lingua_dominante"]		 =	implode(",", $this->input->post("lingua_dominante"));
			$param_sottounita["lingua_prominente"]	 =	implode(",", $this->input->post("lingua_prominente"));
			$param_sottounita["descrizione"]				 = $this->input->post("descrizione");
			$param_sottounita["data"]							 = $this->input->post("data_sottounita");
						
			if(!empty($nuovo_nome_file)){
				
				$param_sottounita["file_immagine"]			=  $nuovo_nome_file;
				$param_sottounita["file_thumb"]				=  $nuovo_nome_file_thumb;
				
			}
			
			
			if($param_sottounita["tipo"]  == "Altro")
				$param_sottounita["tipo_altro"] = $this->input->post("sottounita_altro");
			
			
			$this->db->where("cod_operatore", $codice_op);
			$this->db->where("codice", $vecchio_cod_sottounita);
			$this->db->where("cod_raccolta", $cod_raccolta);
			$this->db->where("cod_unita", $vecchio_cod_unita);
			
			return $this->db->update('rilevazioni_sottounita', $param_sottounita);
			// $this->db->update('rilevazioni_sottounita', $param_sottounita);
			// var_dump($this->db->last_query());
			
		}
		
		
		if($cod_segno != "00"){
			
			$param_segno["genere"]					 	=  implode(",",$this->input->post("genere"));
			$param_segno["lingue"]					 	 	=  implode(",",$this->input->post("lingue"));
			$param_segno["lingua_dominante"]		=	implode(",", $this->input->post("lingua_dominante"));
			$param_segno["lingua_prominente"]		= 	implode(",", $this->input->post("lingua_prominente"));
			$param_segno["descrizione"]				=  $this->input->post("descrizione");
			$param_segno["data"]							=  $this->input->post("data_segno");
			$param_segno["riferimento_spaziale"]  = implode(",", $this->input->post("riferimento_spaziale"));
			
			
			if(!empty($nuovo_nome_file)){
				
				$param_segno["file_immagine"]			=  $nuovo_nome_file;
				$param_segno["file_thumb"]					=  $nuovo_nome_file_thumb;
				
			}
			
			
			
			if($param_segno["genere"]  == "Altro")
				$param_segno["genere_altro"] = $this->input->post("genere_altro");
			
			
			
						
			$this->db->where("cod_operatore", $codice_op);
			$this->db->where("codice", $vecchio_cod_segno);
			$this->db->where("cod_raccolta", $cod_raccolta);
			$this->db->where("cod_unita", $vecchio_cod_unita);
			$this->db->where("sigla", $this->input->post("vecchio_cod_sottounita") . "-" . $vecchio_cod_segno);
			
			
			return $this->db->update("segni", $param_segno);
			// var_dump($this->db->last_query());
			
			
		}
		
		
	}
	
	private function get_allegati($cod_unita, $cod_operatore, $cod_raccolta){
		
		$str_query = "SELECT allegati FROM unita WHERE cod_operatore = '$cod_operatore' AND cod_raccolta = '$cod_raccolta' AND codice= '$cod_unita'";
									  
		$result = $this->db->query($str_query)->result_array()[0]["allegati"];
		
		if(!empty($result))
			return explode(",", $result);
		else return [];
	}
	
	public function zip_immagini_allegati($codice_rilevazione){
		
		$cod_rilevazione_split = explode("-", $codice_rilevazione);
		$query_file_immagine = "SELECT file_immagine FROM %s WHERE cod_raccolta = '%s' AND cod_operatore = '%s' AND codice = '%s' %s";
		$query_file_immagine_sottoschede = "SELECT file_immagine FROM %s WHERE cod_raccolta = '%s' AND cod_operatore = '%s' %s";
		$query_allegati = "SELECT allegati FROM unita WHERE cod_raccolta = '%s' AND cod_operatore = '%s' AND codice = '%s' ";
		
		$codice_raccolta 	= $cod_rilevazione_split[0];
		$codice_op				= $cod_rilevazione_split[1];
		$codice_unita 		= $cod_rilevazione_split[2];
		$codice_sottounita  = $cod_rilevazione_split[3];
		$codice_segno 		= $cod_rilevazione_split[4];
		
		

		$zip_filename 		=  DIRECTORY_BASE . "temp/$codice_rilevazione.zip";
		
		if(file_exists($zip_filename))
			unlink($zip_filename);
		// file_put_contents($base_dir . "temp/PIPPO.zip", "ciao");
		// die;
		
		if($codice_sottounita == "00" && $codice_segno == "00"){
			
			$query_unita 	= sprintf($query_file_immagine, "unita",  $codice_raccolta, $codice_op, $codice_unita, "");
			$query_allegati			 	= sprintf($query_allegati, $codice_raccolta, $codice_op, $codice_unita);
				
			$result = $this->db->query($query_unita)->result_array();
			$file_immagine = $result[0]["file_immagine"];
						
			$zip = new ZipArchive();
			

			if ($zip->open($zip_filename, ZipArchive::CREATE)!==true) {
				return false;
			}
			// echo $base_dir . "static/uploads/" . $file_immagine;
			if(is_readable(DIRECTORY_BASE. "static/uploads/" . $file_immagine))
				$zip->addFile(DIRECTORY_BASE . "static/uploads/" . $file_immagine , $file_immagine);
			else return false;
			
						
			$query_sottounita 	= sprintf($query_file_immagine_sottoschede, "rilevazioni_sottounita",  $codice_raccolta, $codice_op, " AND cod_unita = '$codice_unita'");
			$result_sottounita = $this->db->query($query_sottounita)->result_array();
			// var_dump($query_sottounita);
			foreach($result_sottounita as $record){
				
				$file_immagine = $record["file_immagine"];
				// echo $base_dir . "static/uploads/" . $file_immagine;
				if(is_readable(DIRECTORY_BASE . "static/uploads/" . $file_immagine))
					$zip->addFile( DIRECTORY_BASE . "static/uploads/" . $file_immagine , $file_immagine);
				else return false;
				
			}
			
			$query_segni	= sprintf($query_file_immagine_sottoschede, "segni",  $codice_raccolta, $codice_op, " AND cod_unita = '$codice_unita'");
			$result_segni = $this->db->query($query_segni)->result_array();
			// var_dump($query_segni);
			foreach($result_segni as $record){
				
				$file_immagine = $record["file_immagine"];
				// echo $base_dir . "static/uploads/" . $file_immagine;
				if(is_readable(DIRECTORY_BASE . "static/uploads/" . $file_immagine))
					$zip->addFile(DIRECTORY_BASE . "static/uploads/" . $file_immagine , $file_immagine);
				else return false;
				
			}
			
			$query_allegati = sprintf($query_allegati, $codice_raccolta, $codice_op, $codice_unita);
			$result_allegati = $this->db->query($query_allegati)->result_array()[0];
			
			$allegati = explode("," , $result_allegati["allegati"]);
			
			foreach($allegati as $allegato){
				if(!empty($allegato))
					$zip->addFile(DIRECTORY_BASE. "static/uploads/" . $allegato, $allegato);
				// else return false;
				
			}
						
			$zip->close();
			
		}
		
		
		
		if($codice_sottounita != "00" && $codice_segno == "00"){
			
			$query_file_immagine = sprintf($query_file_immagine, "rilevazioni_sottounita",  $codice_raccolta, $codice_op, $codice_sottounita, " AND cod_unita = '$codice_unita'");
			
			$result = $this->db->query($query_file_immagine)->result_array();
			$file_immagine = $result[0]["file_immagine"];
			
			$zip = new ZipArchive();

			if ($zip->open($zip_filename, ZipArchive::CREATE)!==TRUE) {
				return false;
			}

			$zip->addFile(DIRECTORY_BASE . "static/uploads/" . $file_immagine , $file_immagine);

			$zip->close();
		}
		
		
		if($codice_sottounita != "00" && $codice_segno != "00"){
			
			$query_file_immagine = sprintf($query_file_immagine, "segni",  $codice_raccolta, $codice_op, $codice_segno, " AND cod_unita = '$codice_unita'");
			
			$result = $this->db->query($query_file_immagine)->result_array();
			$file_immagine	= $result[0]["file_immagine"];

			
			$zip = new ZipArchive();

			if ($zip->open($zip_filename, ZipArchive::CREATE)!==TRUE) {
				return false;
			}

			$zip->addFile(DIRECTORY_BASE . "static/uploads/" . $file_immagine , $file_immagine);

			$zip->close();
			
		}
		
		return $zip_filename;
	}
	
	public function get_immagini_sottoschede($cod_raccolta, $cod_operatore, $cod_unita, $thumb=false){
		
		if($thumb === true)
			$campo_immagine = "file_thumb";
		
		else $campo_immagine = "file_immagine";
		
		$str_query = "SELECT $campo_immagine FROM unita u WHERE codice = '$cod_unita' and cod_operatore = '$cod_operatore' and cod_raccolta = '$cod_raccolta'

							UNION

							SELECT rs.$campo_immagine 
							FROM unita u JOIN rilevazioni_sottounita rs on (u.cod_operatore = rs.cod_operatore and u.cod_raccolta = rs.cod_raccolta and u.codice = rs.cod_unita) 
							WHERE cod_unita = '$cod_unita' and rs.cod_operatore = '$cod_operatore' and rs.cod_raccolta = '$cod_raccolta'

							UNION 

							SELECT s.$campo_immagine 

							FROM unita u

							JOIN segni s on (u.cod_operatore = s.cod_operatore and u.cod_raccolta = s.cod_raccolta and u.codice = s.cod_unita)
							WHERE cod_unita = '$cod_unita' and s.cod_operatore = '$cod_operatore' and s.cod_raccolta = '$cod_raccolta' ";
		
		// var_dump($str_query); die;
		return $this->db->query($str_query)->result_array();
		
	}
	
	public function elimina($codice_rilevazione ){
		
		
		$cod_rilevazione_split = explode("-", $codice_rilevazione);
		
		$str_query = "DELETE FROM %s WHERE cod_raccolta = '%s' AND cod_operatore = '%s' AND codice = '%s' %s";
		$str_query_sottoschede = "DELETE FROM %s WHERE cod_raccolta = '%s' AND cod_operatore = '%s' AND cod_unita = '%s'";
		$str_query_sottoschede_sottounita = "DELETE FROM %s WHERE cod_raccolta = '%s' AND cod_operatore = '%s' AND cod_unita = '%s' AND sigla LIKE '%s-%%'";
		
		$codice_raccolta 	= $cod_rilevazione_split[0];
		$codice_op				= $cod_rilevazione_split[1];
		$codice_unita 		= $cod_rilevazione_split[2];
		$codice_sottounita  = $cod_rilevazione_split[3];
		$codice_segno 		= $cod_rilevazione_split[4];
				
		$base_dir = "/var/www/clients/client13/web36/web/paesaggielingua/formuni/";
		$zip_filename = $base_dir . "$codice_rilevazione.zip";
		
		if(file_exists($zip_filename))
			unlink($zip_filename);
		
		if($codice_sottounita == "00" && $codice_segno == "00"){
			
			$str_query = sprintf($str_query, "unita",  $codice_raccolta, $codice_op, $codice_unita, "");
			// var_dump($str_query); die;
			
			$arr_file_immagine = $this->get_immagini_sottoschede($codice_raccolta, $codice_op, $codice_unita);
			$arr_file_thumb = $this->get_immagini_sottoschede($codice_raccolta, $codice_op, $codice_unita, true);
			// var_dump($arr_file_immagine); die;
			foreach($arr_file_immagine as $file_immagine)
				unlink(DIRECTORY_BASE . "static/uploads/" . $file_immagine["file_immagine"]);
			
			foreach($arr_file_thumb as $file_thumb)
				unlink(DIRECTORY_BASE . "static/uploads/" . $file_thumb["file_thumb"]);
			
			$this->db->query($str_query);
			$this->db->query(	sprintf($str_query_sottoschede, "rilevazioni_sottounita", $codice_raccolta, $codice_op, $codice_unita) );
			$this->db->query(	sprintf($str_query_sottoschede, "segni", $codice_raccolta, $codice_op, $codice_unita) );

			return true;	
		}
		
		
		
		
		
		if($codice_sottounita != "00" && $codice_segno == "00"){
			
			
			$str_query = sprintf($str_query, "rilevazioni_sottounita", $codice_raccolta, $codice_op, $codice_sottounita, " AND cod_unita = '$codice_unita'");
			$this->db->query($str_query);// var_dump($str_query); die;
			unlink(DIRECTORY_BASE . "static/uploads/" . $this->get_filename(true, $codice_raccolta, $codice_op, $codice_unita, $codice_sottounita));
			unlink(DIRECTORY_BASE . "static/uploads/" . $this->get_filename(false, $codice_raccolta, $codice_op, $codice_unita, $codice_sottounita));
			// $this->db->query( sprintf($str_query_sottoschede_sottounita, "segni", $codice_raccolta, $codice_op, $codice_unita, $codice_sottounita));
			
			return true;
		}
		
		
		
		
		
		if($codice_sottounita != "00" && $codice_segno != "00"){
			
			unlink(DIRECTORY_BASE . "static/uploads/" . $this->get_filename(true, $codice_raccolta, $codice_op, $codice_unita, $codice_sottounita, $codice_segno));
			unlink(DIRECTORY_BASE . "static/uploads/" . $this->get_filename(false, $codice_raccolta, $codice_op, $codice_unita, $codice_sottounita, $codice_segno));
			$str_query = sprintf($str_query, "segni", $codice_raccolta, $codice_op, $codice_segno, " AND cod_unita = '$codice_unita'");
			return $this->db->query($str_query);
			 
			
		}
		 
		 
		
	}
	
	public function cancella_allegato($allegato, $cod_raccolta, $cod_unita, $cod_operatore){
		
		$query_allegati = "SELECT allegati FROM unita WHERE cod_raccolta = '$cod_raccolta' AND codice = '$cod_unita' AND cod_operatore = '$cod_operatore'";
		
		$res =$this->db->query($query_allegati)->result_array()[0];
		
		$arr_allegati = explode("," , $res["allegati"]);
		// var_dump($allegato_corrente); 
		foreach($arr_allegati as $indice => $allegato_corrente){
			
			if($allegato == $allegato_corrente){
				unset($arr_allegati[$indice]);
				unlink(DIRECTORY_BASE . $allegato);
				break;
			}
			
		}
		
		$this->db->set("allegati", implode("," , $arr_allegati));
		
		$this->db->where("cod_raccolta", $cod_raccolta);
		$this->db->where("cod_operatore", $cod_operatore);
		$this->db->where("codice", $cod_unita);
		
		return $this->db->update("unita");
		
	}
	
	
	public function get_rilevazione_from_coords(){
		
				 
		//$this->db->select($select);
		$lat = $this->input->post("latitudine");
		$lng = $this->input->post("longitudine");
		
		$str_query = "SELECT concat(r.cod_raccolta, '-', u.cod_operatore, '-', u.sigla) as cod_raccolta \n"
								. "FROM  unita u   \n"
								."WHERE latitudine = $lat AND longitudine = $lng ";
		
		// $this->db->where("latitudine", $lat);
		// $this->db->where("longitudine", $lng);
		
		$query_res = $this->db->query($str_query);
		
		return json_encode($query_res->result(), JSON_UNESCAPED_UNICODE);
		
	}
	
	private function sql_expr ($array, $param){
			
			if(strpos($param, "lingue") < 0){
				
				$arr_valori = explode(",", $array[0]);
			
				foreach($arr_valori as $key=>$item)
					$arr_valori[$key] = "$param LIKE '%$item%'";
				
				return "(" . implode(" OR ", $arr_valori) . ")" ;
			}
			
			else{
				if( $this->input->post("tuttelingue") )
					return "( $param LIKE '%${array[0]}%' )";
				else{
					$arr_valori = explode(",", $array[0]);
					foreach($arr_valori as $key=>$item)
						$arr_valori[$key] = "$param LIKE '%$item%'";
				
					return "(" . implode(" OR ", $arr_valori) . ")" ;
				}
			}
	}
		
	public function ricerca(){
		
		$data_da;
		$data_a;
		$filtri_generali = [];
		$filtri_particolari = [ "unita" => [], "sottounita" => [], "segni" => []];
		$str_filtri_particolari = "";
		$str_filtri_generali = "";
		$post = $this->input->post();
		$query_filtri = "";
		$str_query = "";
		
		
				
		$arr_query = [
									
					
			"unita" => "SELECT latitudine, longitudine, u.data as data, u.posizione, concat( u.cod_raccolta, '-', u.cod_operatore, '-', u.sigla ) as cod_raccolta, u.file_immagine, u.file_thumb \n".
										"FROM unita u  ",
			
			
			"sottounita" => "SELECT latitudine, longitudine, rs.data as data, u.posizione, concat(u.cod_raccolta, '-', u.cod_operatore, '-', rs.cod_unita, '-', rs.sigla) as cod_raccolta, rs.file_immagine, rs.file_thumb \n".
									"FROM rilevazioni_sottounita rs JOIN unita u on (rs.cod_unita = u.codice and rs.cod_operatore = u.cod_operatore and rs.cod_raccolta = u.cod_raccolta) ",
												
			 
			 "segni" => 			"SELECT latitudine, longitudine, s.data  as data, u.posizione, concat( u.cod_raccolta, '-', u.cod_operatore, '-', s.cod_unita, '-' , s.sigla) as cod_raccolta, s.file_immagine, s.file_thumb \n".
										"FROM segni s JOIN unita u on (u.codice = s.cod_unita and s.cod_operatore = u.cod_operatore and s.cod_raccolta = u.cod_raccolta) "
										];
		
		
		$arr_param_comuni= [
									
									"sottounita" => "rs",
									"unita" => "u",
									"segni" => "s"	];
		
		$tipo_rilevazione = $this->input->post("tipo_rilevazione");
		
		foreach ($post as $param => $valore){
			// echo $param . " " . $valore;
			if($param == "tipo_rilevazione") continue;
			
			if($param == "tuttelingue") continue;
			
			if(empty($valore)) continue;
			
			if($param == "cod_raccolta") {
				array_push($filtri_particolari["unita"], sprintf( "u.$param = '$valore'"));
				array_push($filtri_particolari["sottounita"], sprintf( "rs.$param = '$valore'"));
				array_push($filtri_particolari["segni"], sprintf( "s.$param = '$valore'"));
			
				continue;
			}
				
				
				
			
			if($param == "data_da"){
				
				if($tipo_rilevazione == "tutte"){
					array_push($filtri_particolari["unita"], sprintf( "u.data >= '%s'", $valore));
					array_push($filtri_particolari["sottounita"], sprintf( "rs.data >= '%s'", $valore));
					array_push($filtri_particolari["segni"], sprintf( "s.data >= '%s'", $valore));
				}
				else{
					array_push($filtri_particolari[ $tipo_rilevazione ], sprintf( $arr_param_comuni[ $tipo_rilevazione ] .".data >= '%s'", $valore));
				}
					
				continue;
			}
			
			if($param == "data_a"){
				
				// array_push($filtri_generali, sprintf( "data <= '%s'", $valore));
				if($tipo_rilevazione == "tutte"){
					array_push($filtri_particolari["unita"], sprintf( "u.data <= '%s'", $valore));
					array_push($filtri_particolari["sottounita"], sprintf( "rs.data <= '%s'", $valore));
					array_push($filtri_particolari["segni"], sprintf( "s.data <= '%s'", $valore));
				}
				else{
					array_push($filtri_particolari[ $tipo_rilevazione ], sprintf( $arr_param_comuni[ $tipo_rilevazione ] .".data <= '%s'", $valore));
				}
				continue;
			}
			
		
			
			if(in_array($param, ["genere", "riferimento_spaziale"])){
				
				array_push($filtri_particolari ["segni"], $this->sql_expr($valore, $param));
				continue;
			}
			
			if($param == "tipo"){
					
				array_push($filtri_particolari ["sottounita"], $this->sql_expr($valore, $param));
				continue;
				
			}
			
			
			if(in_array($param, [ "descrizione", "cod_operatore", "sigla"]) === true){
					
					if($param == "sigla"){
						
						array_push($filtri_particolari[ "sottounita"], sprintf( "%s = '%s'", "rs.cod_unita", $valore));
						array_push($filtri_particolari["segni"] , sprintf( "%s = '%s'", "s.cod_unita", $valore));
						array_push($filtri_particolari["unita"] , sprintf( "%s = '%s'", "u.codice", $valore));
					}
					
					else{
						
						array_push($filtri_particolari["unita"], sprintf( "%s LIKE '%s'", "u.$param", "%$valore%"));
						array_push($filtri_particolari["segni"], sprintf( "%s LIKE '%s'", "s.$param", "%$valore%"));
						array_push($filtri_particolari["sottounita"], sprintf( "%s LIKE '%s'", "rs.$param", "%$valore%"));
					}
					
					continue;
			}
			
			if(in_array($param, ["lingue", "lingua_dominante", "lingua_prominente"])){
				// var_dump($valore); die;
				array_push($filtri_particolari["unita"], $this->sql_expr($valore, "u.$param"));
				array_push($filtri_particolari["segni"], $this->sql_expr($valore, "s.$param"));
				array_push($filtri_particolari["sottounita"], $this->sql_expr($valore, "rs.$param"));
				
				continue;
			}
			
			
			if(in_array($param, [ "categoria", "numero_vetrine"])){
					// var_dump($valore);
					
					array_push($filtri_generali, $this->sql_expr($valore, $param));
					
					continue;
			}
			
			if($param == "allegati"){
				
				array_push($filtri_generali, "$param IS NOT NULL");
				continue;
				
			}
			
			array_push($filtri_generali, sprintf( "%s LIKE '%s'", $param, "%$valore%"));
				
			
			
		}//fine ciclo su $post
		
		if(!empty($filtri_generali))
				$str_filtri_generali = implode(" AND ", $filtri_generali);
			
			
		
		if($tipo_rilevazione == "tutte"){ //se ci sono, aggiungere filtri a ogni query
			
			
			foreach($arr_query as $key => $query){
				
				$str_filtri_particolari = implode(" AND ", $filtri_particolari[$key]);
				$filtri = [ $str_filtri_particolari , $str_filtri_generali];
				$arr_query[ $key ] .= !empty($str_filtri_generali) || !empty ($filtri_particolari[ $key ]) ? " WHERE " . implode( " AND ", array_filter($filtri, function($v){ return $v !== ""; })) : "";
				
			}
			
			$str_query = implode(" UNION ", $arr_query);
			
		}else{
				
			$str_filtri_particolari = implode(" AND ", $filtri_particolari[$tipo_rilevazione]);
			$filtri = [ $str_filtri_particolari , $str_filtri_generali];
			//se filtri_generali non è vuoto gli metto "WHERE" all'inizio e se ci sono filtri particolari aggiungo un AND
			$arr_query[ $tipo_rilevazione ] .= !empty($str_filtri_generali) || !empty ($filtri_particolari[ $tipo_rilevazione ]) ? " WHERE " . implode( " AND ", array_filter($filtri, function($v){ return $v !== ""; })) : "";
			
		
			$str_query = $arr_query[ $tipo_rilevazione ];
		}
		
		
		// var_dump($str_filtri_generali ); die;
		// var_dump($str_query ); die;
		$query = $this->db->query( $str_query );
		
		$result["data"] = $query->result();
		return json_encode($result, JSON_UNESCAPED_UNICODE);
	}

	public function get_images_from_coords(){

		$lat = $this->input->post("latitudine");
		$lng = $this->input->post("longitudine");
		// $filtro_unita ="";
		
		// if( $this->input->post("solo_unita") == "unita") $filtro_unita = " and cod_raccolta LIKE '%-00-00'";
		
		$str_query = "SELECT u.file_immagine, u.file_thumb, concat(u.cod_raccolta, '-', u.cod_operatore, '-', u.sigla) as cod_raccolta , u.lingue
							   FROM unita u 
							   WHERE latitudine = $lat and longitudine = $lng
							   
							   UNION 
							   
							   SELECT  rs.file_immagine, rs.file_thumb, concat(rs.cod_raccolta, '-', rs.cod_operatore, '-', u.codice, '-', rs.sigla) as cod_raccolta, rs.lingue 
							   FROM unita u
							   JOIN rilevazioni_sottounita rs on (rs.cod_unita = u.codice and u.cod_raccolta  = rs.cod_raccolta and u.cod_operatore = rs.cod_operatore)
							   
							   WHERE latitudine = $lat and longitudine = $lng 
							   
							   UNION 
							   
							   SELECT  s.file_immagine, s.file_thumb, concat(s.cod_raccolta, '-', s.cod_operatore, '-', u.codice, '-', s.sigla)  as cod_raccolta, s.lingue 
							   FROM unita u
							   JOIN segni s on (s.cod_unita = u.codice and u.cod_raccolta  = s.cod_raccolta and u.cod_operatore = s.cod_operatore)
							   WHERE latitudine = $lat and longitudine = $lng ";
		// var_dump($str_query); die;					   
		$query_res = $this->db->query($str_query);
		
		return json_encode($query_res->result(), JSON_UNESCAPED_UNICODE);
		
	}
	
	public function get_img_unita_from_coords(){

		$lat = $this->input->post("latitudine");
		$lng = $this->input->post("longitudine");
		// $filtro_unita ="";
		
		// if( $this->input->post("solo_unita") == "unita") $filtro_unita = " and cod_raccolta LIKE '%-00-00'";
		
		$str_query = "SELECT  u.file_thumb, concat(u.cod_raccolta, '-', u.cod_operatore, '-', u.sigla) as cod_raccolta 
							   FROM  unita u 
							   WHERE latitudine = $lat and longitudine = $lng ";
							   
		$query_res = $this->db->query($str_query);
		
		return json_encode($query_res->result(), JSON_UNESCAPED_UNICODE);
		
	}
	
	
	public function lista_punti(){
		
		$str_query = "SELECT latitudine, longitudine, data, u.posizione, concat( u.cod_raccolta, '-', u.cod_operatore, '-', u.sigla ) as cod_raccolta, u.file_immagine, u.file_thumb \n".
							 "FROM  unita u  ";
							 
		$query_res = $this->db->query ($str_query);
		
		
		return json_encode($query_res->result(), JSON_UNESCAPED_UNICODE);
		
	}
	
	public function get_opzioni($nome_tabella){
		
		
		$this->db->order_by('valore', 'ASC');

		$query = $this->db->get($nome_tabella);

		return $query->result_array();

	}
		
	public function get_opzioni_altro($param){
		
		$arr_chiavi_campi = ["unita" => "categoria_altro", 
							 "rilevazioni_sottounita" => "tipo_altro",
							 "segni" => "genere_altro"
							];
							
		$this->db->select( $arr_chiavi_campi[$param] . " as valore" );
		
		
		$this->db->where("LENGTH({$arr_chiavi_campi[$param]}) > 0");

		$query = $this->db->get($param);
		
		

		return $query->result_array();

	}
		
	public function  dettaglio_unita($param_codice_raccolta=""){
		
		
		$cod_raccolta = !empty($param_codice_raccolta) ? explode("-", $param_codice_raccolta)[0]: $this->input->post("codice_raccolta");
		$codice =  !empty($param_codice_raccolta) ? explode("-", $param_codice_raccolta)[2]  : $this->input->post("codice");
		$codice_op =  !empty($param_codice_raccolta) ? explode("-", $param_codice_raccolta)[1]  : $this->input->post("codice_operatore");
		
		$str_query = "SELECT u.cod_raccolta as codice_rilevazione, 
											u.cod_operatore as codice_operatore,
											latitudine,
											longitudine,
											angolo,
											zoom,
											codice as codice_unità,
											categoria,  
											categoria_generale,  
											categoria_altro,  
											posizione as indirizzo,
											u.data as data_rilevazione_unità,
											numero_vetrine, 
											lingue, 
											lingua_dominante, 
											lingua_prominente, 
											descrizione,
											allegati,
											file_immagine \n".
							  "FROM unita u   \n" .
							  "WHERE u.cod_raccolta = '$cod_raccolta' and codice = '$codice' and cod_operatore = '$codice_op'";
		// var_dump($str_query); die;
		$query_res = $this->db->query($str_query);
		// var_dump($query_res->result_array()); die;
		if(empty($param_codice_raccolta ))
			return json_encode($query_res->result(), JSON_UNESCAPED_UNICODE);
		
		else return $query_res->result_array()[0];
		
	}
	
	public function  dettaglio_sottounita($param_codice_raccolta=""){
		// var_dump($param_codice_raccolta); die;
		$cod_raccolta = !empty($param_codice_raccolta) ? explode("-", $param_codice_raccolta)[0] : $this->input->post("codice_raccolta");
		$codice =  !empty($param_codice_raccolta) ? explode("-", $param_codice_raccolta)[3] . "-00" : $this->input->post("codice");
		$codice_op =  !empty($param_codice_raccolta) ? explode("-", $param_codice_raccolta)[1]  : $this->input->post("codice_operatore");
		$codice_unita = !empty($param_codice_raccolta) ? explode("-", $param_codice_raccolta)[2] :  $this->input->post("codice_unita");
		
		$str_query = "SELECT rs.cod_raccolta as codice_rilevazione, 
											u.posizione as indirizzo,
											u.categoria_generale,
											u.categoria,
											u.categoria_altro,
											u.numero_vetrine,
											rs.cod_operatore as codice_operatore,
											cod_unita as codice_unità, 
											rs.codice as codice_sottounità, 
											rs.data as data_rilevazione_sottounità, 
											tipo, 
											tipo_altro,
											rs.lingue, 
											rs.lingua_dominante, 
											rs.lingua_prominente, 
											rs.descrizione, 
											u.allegati,
											rs.file_immagine \n".
							  "FROM rilevazioni_sottounita rs JOIN unita u on (rs.cod_unita = u.codice  and rs.cod_operatore = u.cod_operatore and rs.cod_raccolta = u.cod_raccolta)  \n".
							  "WHERE  rs.cod_raccolta = '$cod_raccolta' and rs.sigla = '$codice' AND  cod_unita = '$codice_unita' and rs.cod_operatore = '$codice_op'";
							  
							  
		// var_dump($str_query); die;
		$query_res = $this->db->query($str_query);
		
		if(empty($param_codice_raccolta ))
			return json_encode($query_res->result(), JSON_UNESCAPED_UNICODE);
		
		else return $query_res->result_array()[0];
		
	}
	
	public function  dettaglio_segno($param_codice_raccolta=""){
		
		$cod_raccolta = !empty($param_codice_raccolta) ? explode("-", $param_codice_raccolta)[0] : $this->input->post("codice_raccolta");
		$codice =  !empty($param_codice_raccolta) ? explode("-", $param_codice_raccolta)[3] . "-" . explode("-", $param_codice_raccolta)[4] : $this->input->post("codice");
		$codice_op =  !empty($param_codice_raccolta) ? explode("-", $param_codice_raccolta)[1]  : $this->input->post("codice_operatore");
		$codice_unita = !empty($param_codice_raccolta) ? explode("-", $param_codice_raccolta)[2] :  $this->input->post("codice_unita");
		
		
		$str_query = "SELECT s.cod_raccolta as codice_rilevazione, 
											  s.cod_operatore as codice_operatore,
		                                      s.cod_unita as codice_unità, 
											  u.posizione as indirizzo,
											  u.categoria_generale,
											  u.categoria,
											  u.categoria_altro,
											  u.numero_vetrine as numero_vetrine,
											  s.sigla as codice_sottounità,
											  s.codice as codice_segno,
											  s.data as data_rilevazione_segno, 
											  genere, 
											  genere_altro,
											  s.lingue, 
											  s.lingua_dominante, 
											  s.lingua_prominente, 
											  s.riferimento_spaziale,
											  s.descrizione,
											  u.allegati,
											  s.file_immagine \n".
							"FROM segni s JOIN unita u on (u.codice = s.cod_unita and u.cod_operatore = s.cod_operatore and u.cod_raccolta = s.cod_raccolta) \n".
							"WHERE s.cod_raccolta = '$cod_raccolta' and s.sigla = '$codice' AND  s.cod_unita = '$codice_unita' and s.cod_operatore = '$codice_op'";
		// var_dump($str_query); die;
		$query_res = $this->db->query($str_query);
		
		if(empty($param_codice_raccolta ))
			return json_encode($query_res->result(), JSON_UNESCAPED_UNICODE);
		
		else return $query_res->result_array()[0];		
	}
	
}
