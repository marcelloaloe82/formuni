<?php

class Inserimento extends CI_Controller{
	
	private $categorie ;
	private $generi;
	private $sottounita ;
	private $lingue ;
	private $mesi;
	
	public function __construct(){
                
		parent::__construct();
		$this->load->model("utente");
		$this->load->model("rilevazione");
		$this->load->helper('url');
		$this->load->library('session');
		
		$this->categorie =  $this->rilevazione->get_opzioni("categorie");
		$this->generi =  $this->rilevazione->get_opzioni("generi");
		$this->sottounita =  $this->rilevazione->get_opzioni("sottounita");
		$this->lingue =  $this->rilevazione->get_opzioni("lingue");
		$this->mesi = [ 	"01" => "Gennaio",
									"02" => "Febbraio",
									"03" => "Marzo",
									"04" => "Aprile",
									"05" => "Maggio",
									"06" => "Giugno",
									"07" => "Luglio",
									"08" => "Agosto",
									"09" => "Settembre",
									"10" => "Ottobre",
									"11" => "Novembre",
									"12" => "Dicembre"];
						
	}
	
	public function salva_rilevazione(){
		
		if($this->session->userdata("insert_mode") == "insert")
			$result = $this->rilevazione->inserisci() ;
		
		if($this->session->userdata("insert_mode") == "edit")
			$result = $this->rilevazione->modifica() ;
		
		
		
		
		if ($result !== true){
			echo $result;
			$this->output->set_status_header(500);
		}
			
	}
	
	public function index(){
		
		$this->session->set_userdata("insert_mode", "insert");
		
		$dati_inserimento = [];
		
		if ($this->utente->auth_check() === false) {
			redirect(base_url() . 'index.php/pl/login/');
			return;
		}
		
		if ($this->session->userdata("ruolo") == "lettore") {
			$this->output->set_status_header(403);
			return;
		}
		
		$csrf = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		
		$cronologia_inserimenti = $this->session->userdata("cronologia_inserimenti");
		// var_dump($cronologia_inserimenti); die;
		$indice_cronologia = count($cronologia_inserimenti) > 0 ? count($cronologia_inserimenti) - 1 : 0;
	
				
		$dati_inserimento["latitudine"]= !empty($this->input->post("latitudine")) ? $this->input->post("latitudine") : "";
		$dati_inserimento["longitudine"] = !empty($this->input->post("longitudine")) ? $this->input->post("longitudine") : "";
		$dati_inserimento["angolo"] = !empty($this->input->post("angolo")) ? $this->input->post("angolo") : "";
		$dati_inserimento["zoom"] = !empty($this->input->post("zoom")) ? $this->input->post("zoom") : "";
		$dati_inserimento["pitch"] = !empty($this->input->post("pitch")) ? $this->input->post("pitch") : "";
		$dati_inserimento["indirizzo"] = !empty($this->input->post("indirizzo")) ? $this->input->post("indirizzo") : "";
		$dati_inserimento["mese"] = "";
		$dati_inserimento["cod_raccolta"] = "";
		$dati_inserimento["anno"] = "";
		$dati_inserimento["numero_vetrine"] = "";
		$dati_inserimento["categoria"] = "";
		$dati_inserimento["categoria_generale"] = "";
		$dati_inserimento["categoria_altro"] = "";
		$dati_inserimento["riferimento_spaziale"] = "";
		$dati_inserimento["descrizione"] = "";
		$dati_inserimento["genere"] = "";
		$dati_inserimento["sottounita"] = "";
		$dati_inserimento["genere_altro"] = "";
		$dati_inserimento["tipo"] = "";
		$dati_inserimento["tipo_altro"] = "";
		$dati_inserimento["lingue"] = "";
		$dati_inserimento["lingua_dominante"] = "";
		$dati_inserimento["lingua_prominente"] = "";
		$dati_inserimento["codice_unità"] = "";
		$dati_inserimento["val_cod_sottounita"] = "";
		$dati_inserimento["val_cod_segno"] = "";
		$dati_inserimento["anno_sottounita"] = "";
		$dati_inserimento["mese_sottounita"] = "";
		$dati_inserimento["anno_segno"] = "";
		$dati_inserimento["mese_segno"] = "";
		
		
		
		$data = array( 
			"dati_inserimento" => $dati_inserimento,
			"cronologia_back" => "disabled" ,
			"cronologia_fwd" => "disabled",
			"ultimo_inserimento" => !empty($cronologia_inserimenti) ? "" : "disabled",
			"insert_mode" => $this->session->userdata("insert_mode"),
			"mesi" => $this->mesi,
			"sottounita" => $this->sottounita,
			"categorie" => $this->categorie,
			"generi" => $this->generi,
			"lingue" => $this->lingue,
			'titolo_pagina' => "Inserimento dati",
			'csrf' => $csrf,
			'ruolo_utente' => $this->session->userdata("ruolo") ,
			"codice_op" => $this->session->userdata('codice_op')
		);
			// var_dump($this->utente->get_ruolo()); die;
		$this->load->view('header', $data);
		$this->load->view('menu', array( "ruolo" => $this->session->userdata('ruolo') ));
		$this->load->view("inserimento", $data);
	}
	
	
	
	public function ultimo_inserimento(){
		
		if ($this->utente->auth_check() === false) {
			redirect(base_url() . 'index.php/pl/login/');
			return;
		}
		
		if ($this->session->userdata("ruolo") == "lettore") {
			$this->output->set_status_header(403);
			return;
		}
		
		$this->session->set_userdata("insert_mode", "edit");
		
		$cronologia_inserimenti = $this->session->userdata("cronologia_inserimenti");
		$indice_cronologia = count($cronologia_inserimenti ) - 1;
		$this->session->set_userdata("indice_cronologia", $indice_cronologia);
		$dati_inserimento = $cronologia_inserimenti[ $indice_cronologia ];
		
		$csrf = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		
		$data = [ "dati_inserimento" => $dati_inserimento,
						"cronologia_back" => $indice_cronologia > 0 ? "" : "disabled" ,
						"cronologia_fwd" => $indice_cronologia < (count($cronologia_inserimenti) -1) ? "" : "disabled",
						"ultimo_inserimento" =>  $indice_cronologia < (count($cronologia_inserimenti) -1) ? "" : "disabled",
						"insert_mode" => $this->session->userdata("insert_mode"),
						"sottounita" => $this->sottounita,
						"categorie" => $this->categorie,
						"generi" => $this->generi,
						"lingue" => $this->lingue,
						"mesi" => $this->mesi,
						"csrf" => $csrf,
						'ruolo_utente' => $this->session->userdata("ruolo") ,
						"titolo_pagina" => "Inserimento dati",
						"codice_op" => $this->session->userdata('codice_op')
					];
					
		$this->load->view('header', $data);
		$this->load->view('menu', array( "ruolo" => $this->session->userdata('ruolo') ));
		$this->load->view("inserimento", $data);
					   
	}
	
	public function cronologia_fwd(){
		
		
		if ($this->utente->auth_check() === false) {
			redirect(base_url() . 'index.php/pl/login/');
			return;
		}
		
		if ($this->session->userdata("ruolo") == "lettore") {
			$this->output->set_status_header(403);
			return;
		}
		
		
		$this->session->set_userdata("insert_mode", "edit");
		$cronologia_inserimenti = $this->session->userdata("cronologia_inserimenti");
		$indice_cronologia = $this->session->userdata("indice_cronologia");
		
		$indice_cronologia ++;
		
		$dati_inserimento = $cronologia_inserimenti[ $indice_cronologia ];
		
		$this->session->set_userdata("indice_cronologia", $indice_cronologia);
		
		$csrf = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		
		$data = [ "dati_inserimento" => $dati_inserimento,
						"cronologia_back" => $indice_cronologia > 0 ? "" : "disabled" ,
						"cronologia_fwd" => $indice_cronologia < (count($cronologia_inserimenti) -1) ? "" : "disabled",
						"ultimo_inserimento" =>  $indice_cronologia < (count($cronologia_inserimenti) -1) ? "" : "disabled",
						"insert_mode" => $this->session->userdata("insert_mode"),
						"sottounita" => $this->sottounita,
						"categorie" => $this->categorie,
						"generi" => $this->generi,
						"lingue" => $this->lingue,
						"mesi" => $this->mesi,
						"csrf" => $csrf,
						'ruolo_utente' => $this->session->userdata("ruolo") ,
						"titolo_pagina" => "Inserimento dati",
						"codice_op" => $this->session->userdata('codice_op')
					];
					
		$this->load->view('header', $data);
		$this->load->view('menu', array( "ruolo" => $this->session->userdata('ruolo') ));
		$this->load->view("inserimento", $data);
					   
	}
	
	public function cronologia_back(){

		if ($this->utente->auth_check() === false) {
			redirect(base_url() . 'index.php/pl/login/');
			return;
		}
		
		if ($this->session->userdata("ruolo") == "lettore") {
			$this->output->set_status_header(403);
			return;
		}
	
		$this->session->set_userdata("insert_mode", "edit");
		$cronologia_inserimenti = $this->session->userdata("cronologia_inserimenti");
		$indice_cronologia = $this->session->userdata("indice_cronologia");
		
		$indice_cronologia --;
		
		$dati_inserimento = $cronologia_inserimenti[ $indice_cronologia ];
		
		$this->session->set_userdata("indice_cronologia", $indice_cronologia);
		
		$csrf = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		
		$data = [ "dati_inserimento" => $dati_inserimento,
						"cronologia_back" => $indice_cronologia > 0 ? "" : "disabled" ,
						"cronologia_fwd" => $indice_cronologia < (count($cronologia_inserimenti) -1) ? "" : "disabled",
						"ultimo_inserimento" => !empty($this->session->userdata("cronologia_inserimenti") ? "" : "disabled"),
						"insert_mode" => $this->session->userdata("insert_mode"),
						"sottounita" => $this->sottounita,
						"categorie" => $this->categorie,
						"generi" => $this->generi,
						"lingue" => $this->lingue,
						"mesi" => $this->mesi,
						"csrf" => $csrf,
						'ruolo_utente' => $this->session->userdata("ruolo") ,
						"titolo_pagina" => "Inserimento dati",
						"codice_op" => $this->session->userdata('codice_op')
					];
					
		$this->load->view('header', $data);
		$this->load->view('menu', array( "ruolo" => $this->session->userdata('ruolo') ));
		$this->load->view("inserimento", $data);
					   
	}
	

}

?>