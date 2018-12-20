<?php 

class Ricerca extends CI_Controller{
	
	private $categorie ;
	private $generi;
	private $sottounita ;
	private $lingue ;
	
	
	public function __construct(){
                
		parent::__construct();
		$this->load->model("rilevazione");
		$this->load->model("utente");
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->helper('download');

		
		$this->categorie =  $this->rilevazione->get_opzioni("categorie");
		$this->generi =  $this->rilevazione->get_opzioni("generi");
		$this->sottounita =  $this->rilevazione->get_opzioni("sottounita");
		$this->lingue =  $this->rilevazione->get_opzioni("lingue");
	}

	
	public function index(){
		
		if($this->utente->auth_check() === false){
			redirect(base_url() . 'index.php/pl/login/');
			return;
		}
		
		$ruolo_utente = $this->session->userdata("ruolo");
		
		$csrf = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		
		$categorie_altro = $this->rilevazione->get_opzioni_altro("unita");
		$sottounita_altro = $this->rilevazione->get_opzioni_altro("rilevazioni_sottounita");
		$segni_altro = $this->rilevazione->get_opzioni_altro("segni");
		
		$data = array( 
			"sottounita" => array_merge($this->sottounita, $sottounita_altro),
			"categorie" => array_merge($this->categorie, $categorie_altro),
			"generi" => array_merge($this->generi, $segni_altro),
			"lingue" => $this->lingue,
			'titolo_pagina' => "Cerca tra le rilevazioni",
			'csrf' => $csrf,
			"ruolo"=> $ruolo_utente,
			"codice_op" => $this->session->userdata("codice_op")
		);
		
		
		
		$form_ricerca = $this->load->view("form_ricerca", $data, true);
		
		
		$this->load->view('header', $data);
		$this->load->view('menu',  $data);
		
		if( $ruolo_utente == "lettore")
			$this->load->view("ricerca", ["form_ricerca" => $form_ricerca]);
		
		else 	
			$this->load->view("ricerca_mappa", ["form_ricerca" => $form_ricerca]);
		
	}
		
	public function filtra_ricerca(){
		
		echo $this->rilevazione->ricerca();
	}

	public function mappa(){
		
		$data = array("titolo_pagina" => "Mappa delle rilevazioni");
		$this->load->view("header", $data);
		$this->load->view("mappa");
		
	}
	
	public function lista_punti(){
		
		echo $this->rilevazione->lista_punti();
		
	}
	
	public function get_rilevazione_from_coords(){
		
		if ($this->utente->auth_check() === false) {
				$this->output->set_status_header(403);
				return;
		}

		echo $this->rilevazione->get_rilevazione_from_coords();
		
	}

	public function get_images_from_coords(){
		
		if ($this->utente->auth_check() === false) {
				$this->output->set_status_header(403);
				return;
		}

		echo $this->rilevazione->get_images_from_coords();
		
	}
	
	public function dettaglio_unita(){
		
		if ($this->utente->auth_check() === false) {
				$this->output->set_status_header(403);
				return;
		}
			
		echo $this->rilevazione->dettaglio_unita();
	}
	
	public function  dettaglio_sottounita(){
		
		if ($this->utente->auth_check() === false) {
				$this->output->set_status_header(403);
				return;
		}
			
		echo $this->rilevazione->dettaglio_sottounita();
		
	}
	
	public function  dettaglio_segno(){
		
		if ($this->utente->auth_check() === false) {
				$this->output->set_status_header(403);
				return;
		}
			
		echo $this->rilevazione->dettaglio_segno();
	}

	public function scarica_immagini_allegati(){
		
		$filename = $this->rilevazione->zip_immagini_allegati($this->input->post("codice_rilevazione")) ;
		// echo $filename; die;
		if($filename !== false){		
			
			force_download($filename, null);
			
		}

		else{
			
			$this->output->set_status_header(500);
			
		}
		
	}
	
	public function elimina(){
		
		if($this->rilevazione->elimina( $this->input->post("codice_rilevazione")) === false){
			
			echo "C'è stato un problema tecnico, riprovare";
			$this->output->set_status_header(500);
			
		}
		
	}
}

?>
