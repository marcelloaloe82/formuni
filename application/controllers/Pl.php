<?php


class Pl extends CI_controller{
	
	public function __construct(){
                
		parent::__construct();
		
		$this->load->helper('url');
		$this->load->model('utente');
		$this->load->library('session');

	}
	
	public function index(){
		
		if ($this->utente->auth_check() === false) {
			redirect(base_url() . 'index.php/pl/login');
			return;
		}
		
		if( $this->session->userdata('autenticato') === true	) {
			
			if( $this->session->userdata("ruolo") != "lettore" && $this->session->userdata("ruolo") != "ricercatore")
				redirect(base_url() . 'index.php/screening');
			
			elseif( $this->session->userdata("ruolo") == "lettore" )
				redirect(base_url() . 'index.php/mappa');
			
			else redirect(base_url() . 'index.php/ricerca');
			
			
			return;
		}
		
	}
	
	public function login(){
	
		if( $this->session->userdata('autenticato') === true	) {
			
			if( $this->session->userdata("ruolo") != "lettore" && $this->session->userdata("ruolo") != "ricercatore")
				redirect(base_url() . 'index.php/screening');
			
			elseif( $this->session->userdata("ruolo") == "lettore" )
				redirect(base_url() . 'index.php/mappa');
			
			else redirect(base_url() . 'index.php/ricerca');
			
			return;
		}
		
		$csrf = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		
		$data = array( 
			'titolo_pagina' => "Login",
			'csrf' => $csrf
		);
		
		
		$this->load->view('header', $data);
		$this->load->view('login');
	
	}
	
	public function logout(){
		
		$this->utente->logout();
		redirect(base_url() . 'index.php/pl/login/');
		
	}
		
	public function auth(){
		// var_dump($this->input->post("email"));
		// var_dump($this->input->post("password"));
		echo $this->utente->auth();
	}
	
		
}

?>