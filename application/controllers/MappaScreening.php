<?php 

class MappaScreening extends CI_Controller{
	
	
	public function __construct(){
					
		parent::__construct();
		$this->load->model("screening_model");
		$this->load->model("utente");
		$this->load->library("session");
		$this->load->helper('url');
			
	}	
		
	public function index(){
		
		if ($this->utente->auth_check() === false) {
			redirect(base_url() . 'index.php/pl/login/');
			return;
		}
		
		$csrf = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		
		$data = array (
			"titolo_pagina" => "Screening preliminare",
			"csrf" => $csrf,
			"codice_op"	=> $this->session->userdata("codice_op"), 
			"ruolo" =>$this->session->userdata('ruolo')
		);
		
		$this->load->view("header", $data);
		$this->load->view('menu', array( "ruolo" =>$this->session->userdata('ruolo') ));
		$this->load->view("mappascreening", $data);
	}
	
	public function get_map_bounds() {
		
		if (empty($this->input->cookie( $this->config->item('csrf_cookie_name')))) {
			$this->output->set_status_header(403);
			return;
		}
		echo $this->screening_model->get_map_bounds();
		
	}
	
	public function get_screenings(){
		
		if ($this->utente->auth_check() === false) {
			
			$this->output->set_status_header(403);
			
			return;
		}
		
		echo $this->screening_model->get_screenings();
		
	}
	
	public function get_screening_from_coords(){
		
		if ($this->utente->auth_check() === false) {
			
			$this->output->set_status_header(403);
			
			return;
		}
		
		echo $this->screening_model->get_screening_from_coords();
	}

	public function elimina(){
		
		$id_record = $this->input->post("id");
		
		if($this->screening_model->elimina($id_record) === false)
			$this->output->set_status_header(500);
		
	}
}

?>