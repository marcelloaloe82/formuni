<?php


class Operatori extends CI_Controller{

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
								'csrf' => $csrf,
								'id_ruolo' => ""
								);
								
		$this->load->view("header", $data);

		$this->load->view("iscrizione", $data);
	
	}
	
	public function invia_iscrizione(){
		
		$nome_iscritto = $this->input->post("nome");
		$cognome_iscritto = $this->input->post("cognome");
		$email_iscritto = $this->input->post("email");
		$email_supervisore = "marcella.ubertibona@libero.it";
		
		$oggetto = "[Paesaggi e lingua] Richiesta di iscrizione";
		
		$mess = "Ciao,\nc'è una nuova richiesta di iscrizione a paesaggi linguistici da approvare.\n\n".
						"Dati di iscrizione\nNome: $nome_iscritto\nCognome: $cognome_iscritto\nEmail: $email_iscritto\n\n".
						"Per approvare l'iscrizione collegati all'indirizzo http://sites.unimi.it/paesaggielingua/formuni/index.php/pl/login e dopo esserti autenticato vai alla pagina \"Approvazione utenti\".";
		
		$oggetto_operatore = "[Paesaggi e lingua] La tua richiesta è stata inviata";
		$messaggio_operatore = "Ciao,\nla tua richiesta di iscrizione è stata inviata correttamente e sarà visionata a breve da un supervisore.\nRiceverai una mail non appena sarà fatto.\n\n\n".
												"Ti chiediamo di non inviare altre richieste di iscrizione prima dell'approvazione\n\n\nGrazie\n\n\n\n\nNon rispondere a questo messaggio, per altre comunicazioni ti chiediamo di scrivere all'indirizzo email del supervisore";
		
		$response = $this->iscrizione->salva_dati() ;
		
		if($response === true){
			
			$this->invia_mail($email_supervisore, $oggetto, $mess);
			$this->invia_mail($email_iscritto, $oggetto_operatore, $messaggio_operatore);
		}
		
		else {
			
			echo $response; 
			$this->output->set_status_header(500);
			
		}
	}
	
	private function invia_mail($dest, $oggetto, $msg){
		
		$to      		= $dest;
		$subject 	= $oggetto;
		$message = $msg;
		$headers = 'From: nonrispondere@sites.unimi.it' . "\r\n" .
		'Reply-To: nonrispondere@sites.unimi.it' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();

		mail($to, $subject, $message, $headers);
		
	}
	
	public function success(){
		
		$data = array( 'titolo_pagina' => "Iscrizione riuscita!");
								
		$this->load->view("header", $data);

		$this->load->view("success");
		
	}
}

?>