<?php 

class Admin extends CI_Controller {

	public function __construct(){
		
		parent::__construct();
		
		$this->load->helper('url');
		$this->load->model('iscrizione');
		$this->load->model('utente');
		$this->ruolo_utente = $this->session->userdata('ruolo');
	}
	
	public function approvazione_utenti(){
	
		if ($this->utente->auth_check() === false) {
			redirect(base_url() . 'index.php/pl/login/');
			return;
		}
		
		
		// var_dump( $ruolo_utente); die;
		
		if($this->ruolo_utente != "supervisore" ){
		
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
			"ruolo" => $this->ruolo_utente,
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
		
		if($this->ruolo_utente != "supervisore" ){
		
			$this->output->set_status_header(403);
			return;
		
		}
		
		$email_utente = $this->iscrizione->get_email( $this->input->post("utenti"));
		$messaggio = "Ciao,\nla tua richiesta di iscrizione è stata approvata.\nPer accedere alla banca dati collegati al sito
								 http://sites.unimi.it/paesaggielingua/formuni/index.php/pl/login e fai il login con l'indirizzo email e la password indicate in sede di registrazione";
		$oggetto = "[Paesaggi e lingua] Iscrizione a Paesaggi e lingua approvata!";
		
		if( $this->iscrizione->approva() === true)
			$this->invia_mail($email_utente, $oggetto, $messaggio);
		
	}
	
	public function non_approvare(){
		
		if($this->ruolo_utente != "supervisore" ){
		
			$this->output->set_status_header(403);
			return;
		
		}
		
		$email_utente = $this->iscrizione->get_email( $this->input->post("utenti"));
		$messaggio = "Ciao,\nla tua richiesta di iscrizione non è stata approvata. Per poter iscriverti invia prima una mail a ";
		$oggetto = "[Paesaggi e lingua] Iscrizione a Paesaggi linguistici NON approvata";
		
		if( $this->iscrizione->non_approvare() === true)
			$this->invia_mail($email_utente, $oggetto, $messaggio);
		
	}
	
	
	public function ban(){
		
		if($this->uolo_utente != "supervisore"  ){
		
			$this->output->set_status_header(403);
			return;
		
		}
		
		$email_utente = $this->iscrizione->get_email( $this->input->post("utenti"));
		$messaggio = "Ciao,\n a causa delle tue ripetute richieste non approvate non potrai più iscriverti";
		$oggetto = "Spiacente, sei stato bandito!";
		
		if( $this->iscrizione->ban() === true)
			$this->invia_mail($email_utente, $oggetto, $messaggio);
		
	}
	
	public function lista_utenti_json(){
		
		if ($this->utente->auth_check() === false) {
			redirect(base_url() . 'index.php/pl/login/');
			return;
		}
		
		if($this->ruolo_utente != "supervisore" ){
		
			$this->output->set_status_header(403);
			return;
		
		}
		
		$utenti = $this->iscrizione->get_utenti(true);
		
		echo json_encode($utenti, JSON_UNESCAPED_UNICODE);
	}
	
	
	public function lista_utenti(){
		
		if ($this->utente->auth_check() === false) {
			redirect(base_url() . 'index.php/pl/login/');
			return;
		}
		
		if($this->ruolo_utente != "supervisore" ){
		
			$this->output->set_status_header(403);
			return;
		
		}
		
		$csrf = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		
		$utenti = $this->iscrizione->get_utenti();
		$tipi_utente = $this->utente->tipi_utente();
		
		$data = array (
			"titolo_pagina" => "Pannello amministrazione",
			"csrf" => $csrf,
			"ruolo" => $this->ruolo_utente,
			"utenti" => $utenti,
			"tipi_utente" => $tipi_utente
		);
		
		$this->load->view("header", $data);
		$this->load->view('menu', $data);
		$this->load->view("cambio_ruolo", $data);
		
	}
	
	public function cambia_ruolo(){
		
		if($this->ruolo_utente != "supervisore" ){
		
			$this->output->set_status_header(403);
			return;
		
		}
		
		return $this->iscrizione->cambia_ruolo();
		
	}
	public function cancella_utente(){
		
		if($this->ruolo_utente != "supervisore"){
		
			$this->output->set_status_header(403);
			return;
		
		}
		
		return $this->iscrizione->cancella_utente();
		
	}
	
}

?>