<?php

class Utente extends CI_Model {
	
	private $email;
	private $password;
	private $ruolo;
	private $codice_op;
	
	
	public function __construct(){
                
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		
    }
	
	
	
	public function auth(){
		
		$result;
		$this->password = $this->input->post('password');
		$pw_hash = hash( "sha256", $this->password);
		$this->email =  $this->input->post("email");
		$sql = "SELECT * FROM utenti JOIN ruoli on utenti.id_ruolo = ruoli.id WHERE email = ? AND password = ?";
		// var_dump($this->db->query($sql, array( $this->email, $pw_hash))->result_array()); die;
		
		if( empty($this->password) || empty($this->email) ) return "KO";
		
		if( !$result = $this->db->query($sql, array( $this->email, $pw_hash)) -> result() )
			return "KO";
		
		else {
			// var_dump($result[0]->ruolo); die;
			$this->ruolo = $result[0]->ruolo;
			$this->codice_op = $result[0]->codice_op;
			$this->session->set_userdata('autenticato', true);
			$this->session->set_userdata('codice_op', $this->codice_op);
			$this->session->set_userdata('ruolo', $this->ruolo);
			
			
			return "OK";
		}
		
	}
	
	public function tipi_utente(){
		
		$result = $this->db->get("ruoli");
		return $result->result();
		
	}
	
	public function get_ruolo(){
		
		return $this->ruolo;
		
	}
	
	public function logout(){
		
		$this->session->sess_destroy();
		
	}
	
	
	public function auth_check(){
		
		if( $this->session->userdata('autenticato') !== true	) {
			
			return false;
		}
		
		else return true;
		
	}

}