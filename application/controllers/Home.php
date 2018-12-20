<?php 

class Home extends CI_Controller {

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
		
		$data = array( 'titolo_pagina' => "Benvenuti a Paesaggi e lingua",
								'csrf' => $csrf	);
								
		$this->load->view("header", $data);

		$this->load->view("home");
	
	}
	
	
}

?>