<?php

class Modifica extends CI_Controller {
	
	private $categorie ;
	private $generi;
	private $sottounita ;
	private $lingue ;
	private $mesi;
									
	public function __construct(){
                
		parent::__construct();
		$this->load->model("rilevazione");
		$this->load->model("utente");
		$this->load->library('session');
		$this->load->helper('url');
		
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

	public function index(){
		
		if($this->utente->auth_check() === false){
			redirect(base_url() . 'index.php/pl/login/');
			return;
		}
		
		$ruoli_ammessi = ["supervisore", "operatore"];
		
		$ruolo_utente = $this->session->userdata("ruolo");
		
		if(!in_array($ruolo_utente, $ruoli_ammessi)){
			
			$this->output->set_status_header(403);
			return;
		}
		
		$this->session->set_userdata("insert_mode", "edit");
		
		$cod_raccolta = $this->input->post("cod_raccolta");
		
		//recuperare dati unita o sottounita o segno
		
		$cod_raccolta_split = explode("-", $cod_raccolta);
		
		$dati_modifica = $this->rilevazione->dettaglio_unita( $cod_raccolta );
		$data_split = explode("-", $dati_modifica["data_rilevazione_unità"]);
		$dati_modifica["anno"] = $data_split[0];
		$dati_modifica["mese"] = $data_split[1];
		$dati_modifica["vecchio_cod_unita"] = $dati_modifica["codice_unità"];
		// var_dump($dati_modifica); die;
		
		if($cod_raccolta_split [3] != "00"){
			$dati_modifica = array_merge($dati_modifica, $this->rilevazione->dettaglio_sottounita( $cod_raccolta ) );
			$dati_modifica["val_cod_sottounita"] = $dati_modifica["codice_sottounità"];
			$dati_modifica["vecchio_cod_sottounita"] = $dati_modifica["codice_sottounità"];
			$data_split = explode("-", $dati_modifica["data_rilevazione_sottounità"]);
			$dati_modifica["anno_sottounita"] = $data_split[0];
			$dati_modifica["mese_sottounita"] = $data_split[1];
			unset($dati_modifica["allegati"]);
			
		}
		
		else{
			$dati_modifica["val_cod_sottounita"] = "00";
			$dati_modifica["vecchio_cod_sottounita"] = "00";
			$dati_modifica["anno_sottounita"] = "";
			$dati_modifica["mese_sottounita"] = "";
			$dati_modifica["tipo_altro"] = "";
			$dati_modifica["tipo"] = "";
		}
	
		if($cod_raccolta_split [4] != "00"){ 
		// var_dump($this->rilevazione->dettaglio_segno( $cod_raccolta ) ); die;
			$dati_modifica = array_merge($dati_modifica, $this->rilevazione->dettaglio_segno( $cod_raccolta ) );
			$dati_modifica["val_cod_segno"] = $dati_modifica["codice_segno"];
			$dati_modifica["vecchio_cod_segno"] = $dati_modifica["codice_segno"];
			$data_split = explode("-", $dati_modifica["data_rilevazione_segno"]);
			$dati_modifica["anno_segno"] = $data_split[0];
			$dati_modifica["mese_segno"] = $data_split[1];
			$dati_modifica["val_cod_sottounita"] = explode("-", $dati_modifica["codice_sottounità"])[0];
			unset($dati_modifica["allegati"]);
		}
		
		else {
			$dati_modifica["val_cod_segno"] = "00";
			$dati_modifica["vecchio_cod_segno"] = "00";
			$dati_modifica["anno_segno"] = "";
			$dati_modifica["mese_segno"] = "";
			$dati_modifica["genere_altro"] = "";
			$dati_modifica["genere"] = "";
			$dati_modifica["riferimento_spaziale"] = "";
		}
		
		$dati_modifica["cod_raccolta"] = $cod_raccolta_split[0];
		
		
		// var_dump($dati_modifica); die;
		
		$csrf = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		
		$indice_cronologia = $this->session->userdata("indice_cronologia");
		$cronologia_inserimenti = $this->session->userdata("cronologia_inserimenti");
		
		$data = array( 
			"dati_inserimento" => $dati_modifica,
			"cronologia_back" => ($indice_cronologia > 0) ? "" : "disabled" ,
			"cronologia_fwd" => ($indice_cronologia < (count($cronologia_inserimenti) -1)) ? "" : "disabled",
			"ultimo_inserimento" => !empty($cronologia_inserimenti) ? "" : "disabled",
			"insert_mode" => $this->session->userdata("insert_mode"),
			"mesi" => $this->mesi,
			"sottounita" => $this->sottounita,
			"categorie" => $this->categorie,
			"generi" => $this->generi,
			"lingue" => $this->lingue,
			'titolo_pagina' => "Inserimento dati",
			'csrf' => $csrf,
			"codice_op" => $cod_raccolta_split[1],
			"ruolo_utente" => $ruolo_utente
		);
			// var_dump($this->utente->get_ruolo()); die;
		$this->load->view('header', $data);
		$this->load->view('menu', array( "ruolo" => $this->session->userdata('ruolo') ));
		$this->load->view("inserimento", $data);
	
	}
	
	public function cancella_allegato(){
		
		$allegato 			= $this->input->post("allegato");
		$cod_raccolta 	= $this->input->post("cod_raccolta");
		$cod_unita 			= $this->input->post("cod_unita");
		$cod_operatore 	= $this->input->post("cod_operatore");
		
		if($this->rilevazione->cancella_allegato($allegato, $cod_raccolta, $cod_unita, $cod_operatore) === false)
			$this->output->set_status_header(500);
		
	}
}