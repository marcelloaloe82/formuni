<?php 

class Mappa extends CI_Controller{

	public function __construct(){
	
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('rilevazione');
		$this->load->model('utente');
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
		
		$ruolo_utente = $this->session->userdata("ruolo");
		
		$data = array (
			"titolo_pagina" => "Rilevazioni sul campo",
			"csrf" => $csrf,
			"ruolo" => $ruolo_utente
		);
		
		$this->load->view("header", $data);
		$this->load->view('menu', array( "ruolo" =>$this->session->userdata('ruolo') ));
		$this->load->view("mappa_rilevazioni", $data);
	}
	
	public function get_img_unita_from_coords(){
		
		if ($this->utente->auth_check() === false) {
				$this->output->set_status_header(403);
				return;
		}
 
		echo $this->rilevazione->get_img_unita_from_coords();
	}
	
	public function get_rilevazione_from_coords(){
		
		if (empty($this->input->cookie( $this->config->item('csrf_cookie_name')))) {
			$this->output->set_status_header(403);
			return;
		}
		
		echo $this->rilevazione->get_rilevazione_from_coords();
		
	}
	
	public function lista_punti(){
		
		if (empty($this->input->cookie( $this->config->item('csrf_cookie_name')))) {
			$this->output->set_status_header(403);
			return;
		}
		
		echo $this->rilevazione->lista_punti();
	}
	
	public function get_map_bounds(){
		
		if (empty($this->input->cookie( $this->config->item('csrf_cookie_name')))) {
			$this->output->set_status_header(403);
			return;
		}
		
		echo $this->rilevazione->get_map_bounds();
		
	}
	
	public function get_images_from_coords(){
		
		if (empty($this->input->cookie( $this->config->item('csrf_cookie_name')))) {
			$this->output->set_status_header(403);
			return;
		}
		
		echo $this->rilevazione->get_images_from_coords();
	}
}

?>