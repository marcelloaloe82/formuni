<?php 

class Admin extends CI_Controller {

	public function __construct(){
		
		parent::__construct();
		
		$this->load->helper('url');
		$this->load->model('iscrizione');
		$this->load->model('utente');
	
	}
	
	public function index(){
	
		if ($this->utente->auth_check() === false) {
			redirect(base_url() . 'index.php/pl/login/');
			return;
		}
		
		$ruolo_utente = $this->session->userdata('ruolo');
		// var_dump( $ruolo_utente); die;
		
		if($ruolo_utente != "supervisore" && $ruolo_utente != "admin" ){
		
			$this->output->set_status_header(403);
			return;
		
		}
		
		$csrf = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		
		$iscrizioni = $this->iscrizione->get_iscrizioni();
		$tipi_utente = $this->utente->tipi_utente();
		
		$data = array (
			"titolo_pagina" => "Pannello amministrazione",
			"csrf" => $csrf,
			"ruolo" => $ruolo_utente,
			"richieste" => $iscrizioni,
			"tipi_utente" => $tipi_utente
		);
		
		$this->load->view("header", $data);
		$this->load->view('menu', $data);
		$this->load->view("pannello_admin", $data);
	
	}
	
	private function invia_mail($dest, $oggetto, $msg){
		
		$to      		= $dest;
		$subject 	= $oggetto;
		$message = $msg;
		$headers = 'From: webmaster@sites.unimi.it' . "\r\n" .
							'Reply-To: webmaster@sites.unimi.it' . "\r\n" .
							'X-Mailer: PHP/' . phpversion();

		mail($to, $subject, $message, $headers);
		
	}
	
	public function approva(){
		
		$email_utente = $this->iscrizione->get_email( $this->input->post("utenti"));
		$messaggio = "ciao,\nla tua richiesta di iscrizione è stata approvata";
		$oggetto = "[Paesaggi e lingua] Iscrizione a Paesaggi e lingua approvata!";
		
		if( $this->iscrizione->approva() === true)
			$this->invia_mail($email_utente, $oggetto, $messaggio);
		else {
			echo "Approvazione non riuscita, c'è stato un problema tecnico";
			$this->output->set_status_header(500);
		}
		
	}
	
	public function non_approvare(){
		
		$email_utente = $this->iscrizione->get_email( $this->input->post("utenti"));
		$messaggio = "ciao,\nla tua richiesta di iscrizione non è stata approvata. Per poter iscriverti invia prima una mail a ";
		$oggetto = "[Paesaggi e lingua] Iscrizione a Paesaggi linguistici NON approvata";
		
		if( $this->iscrizione->non_approvare() === true)
			$this->invia_mail($email_utente, $oggetto, $messaggio);
		
	}
	
	
	public function ban(){
		
		$email_utente = $this->iscrizione->get_email( $this->input->post("utenti"));
		$messaggio = "ciao,\n a causa delle tue ripetute richieste non approvate non potrai più iscriverti";
		$oggetto = "Spiacente, sei stato bandito!";
		
		if( $this->iscrizione->ban() === true)
			$this->invia_mail($email_utente, $oggetto, $messaggio);
		
	}
	
}

?>