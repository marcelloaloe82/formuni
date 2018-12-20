<?php 

class Screening extends CI_Controller{

	public function __construct(){
                
		parent::__construct();
		$this->load->model("screening_model");
		$this->load->model("utente");
		$this->load->helper('url');
		$this->load->library('session');
	}
	
	public function index(){
		
		
		
		if ($this->utente->auth_check() === false) {
			redirect(base_url() . 'index.php/pl/login/');
			return;
		}
		
		if ($this-> utente-> get_ruolo()== "lettore") {
			$this->output->set_status_header(403);
			return;
		}
		
		if(!empty( $this->input->post() )){

			$screening_mode = "edit";
		
			$lat 				= $this->input->post("latitudine");
			$lng 			= $this->input->post("longitudine");
			$angolo 		= $this->input->post("angolo");
			$zoom 		= $this->input->post("zoom");
			$indirizzo 	= $this->input->post("indirizzo");
			$lingua	 	= $this->input->post("lingua");
			$id_record 	= $this->input->post("id");
			
		}
		else{
			
			$screening_mode = "insert";
			
			$lat 				=    "";
			$lng 			=    "";
			$angolo 		=    "";
			$zoom 		=    "";
			$indirizzo 	=    "";
			$lingua	 	=    "";
			$id_record	=    "";
			
		}
		
		$csrf = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		
		$data = array(
			"screening_mode" => $screening_mode,
			"lat" 				=> $lat,
			"lng" 			=> $lng,
			"angolo" 		=> $angolo, 
			"zoom" 		=> $zoom,
			"indirizzo" 	=> $indirizzo, 
			"lingua"	 	=> $lingua, 
			"id_record"	=> $id_record, 
			"codice_op"	=> $this->session->userdata("codice_op"), 
			"titolo_pagina"=> "Screening street view",
			"csrf" => $csrf
		);
		
		$this->load->view("header", $data);
		$this->load->view('menu', array( "ruolo" =>$this->session->userdata('ruolo') ));
		$this->load->view("screening", $data);
		
	}
	
	public function salva_screening(){
		
		$save_result = $this->screening_model->salva_screening() ;
		
		if($save_result  === -1){
			echo "Coordinate geografiche già inserite, spostare lo street view";
			$this->output->set_status_header(500);
		}
		
		if($save_result === false){
			
			echo "C'è stato un problema tecnico, riprovare";
			$this->output->set_status_header(500);

		}
	}

	public function edit(){
		
		$id_record = $this->input->post("id");
		
		$save_result = $this->screening_model->edit($id_record) ;
		
		if($save_result === false){
			
			echo "C'è stato un problema tecnico, riprovare";
			$this->output->set_status_header(500);

		}
		
		
	}
}
?>