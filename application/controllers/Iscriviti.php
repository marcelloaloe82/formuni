<?php 

class Iscriviti extends CI_Controller {

	public function __construct(){
		
		parent::__construct();
		
		$this->load->helper('url');
		$this->load->model('iscrizione');
		$this->load->model('utente');
	
	}
	
	public function index(){
	
		
		$csrf = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		
		$data = array( 'titolo_pagina' => "Inserimento dati",
								'csrf' => $csrf	,
								"id_ruolo" => $this->iscrizione->get_id_ruolo("lettore")
								);
								
		$this->load->view("header", $data);

		$this->load->view("iscrizione", $data);
	
	}
	
	public function invia_iscrizione(){
		
		$response = $this->iscrizione->salva_dati() ;
		
		if($response != true){
			echo $response;
			$this->output->set_status_header(500);
			
		}	
	}

	public function success(){
		
		$data = array( 'titolo_pagina' => "Iscrizione riuscita!");
								
		$this->load->view("header", $data);

		$this->load->view("success");
		
	}
}

?>