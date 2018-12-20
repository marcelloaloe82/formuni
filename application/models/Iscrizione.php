<?php


class Iscrizione extends CI_Model{

	public function __construct(){
		
		parent::__construct();
		$this->load->database();
	}
	
	public function get_iscrizioni(){
		
		$query = $this->db->get("iscrizioni");
		return $query->result();
	}
	
	public function get_utenti($json=false){
		
		$str_query = "SELECT utenti.id, nome, cognome, email, codice_op, ruolo " .
							  "FROM utenti JOIN ruoli on ruoli.id = utenti.id_ruolo ".
							  "WHERE ruolo not in ('supervisore', 'lettore', 'admin') " .
							  "ORDER BY cognome asc";
		
		
		$query = $this->db->query($str_query);
		
		if($json === false)
			return $query->result();
	
		else return $query->result_array();
	}
	
	public function get_id_ruolo($ruolo){
		
		$id_ruolo;
		$this->db->where("ruolo", $ruolo);
		$query = $this->db->get("ruoli");
		
		$id_ruolo =  $query->result_array()[0]["id"];
		
		return $id_ruolo;
	}
	
	public function get_email($id_richiesta){
		
		$query_richiesta = "SELECT email FROM iscrizioni WHERE id = ?";
		
		$query_result = $this->db->query($query_richiesta, array( $id_richiesta));
		
		$email = $query_result->result_array()[0]["email"];
		
		return $email;
	}
	
	private function e_utente_iscritto($email){
		
		$query_iscrizioni = "SELECT count(*) as num_record FROM iscrizioni WHERE email = '$email'";
		$res_iscrizioni = $this->db->query( $query_iscrizioni );
		
		if($res_iscrizioni->result_array()[0]["num_record"] > 0)
			return true;
		
		else return false;
		
	}
	
	private function e_utente_approvato($email){
		
		$query_utenti = "SELECT count(*) as num_record FROM utenti WHERE email = '$email'";
		$res_utenti = $this->db->query( $query_utenti );
		
		if( $res_utenti->result_array()[0]["num_record"] > 0)
			return true;
			
		else return false;
	}
	
	private function esiste_codice_op( $codice ){
		
		$query = "SELECT count(*) as num_record FROM utenti WHERE codice_op = '$codice'";
		$res = $this->db->query( $query );
		
		if( $res->result_array()[0]["num_record"] > 0 )
			return true;
		
		else return false;
		
	}
	
	private function e_bannato($email){
		
		$query_check = "SELECT count(email) as num_record FROM banned_email WHERE email = '$email'";
		
		$result = $this->db->query($query_check)->result_array()[0];
		
		if($result["num_record"] > 0){
			
			return true;
		}
		
		else return false;
	}
	
	public function approva(){
		
		$id_richiesta = $this->input->post("utenti");
		
		$email = $this->get_email($id_richiesta);
		
		$cod_operatore ="";
		
		
		$query_richiesta = "SELECT id, nome, cognome, email, password FROM iscrizioni WHERE id = $id_richiesta";
		
		$query_result = $this->db->query($query_richiesta);
		
		$record = $query_result->result_array()[0];
		
		$record["id_ruolo"] = $this->get_id_ruolo ( $this->input->post("ruolo") ); 
		
		do $cod_operatore = chr( rand( 65, 90) ) . chr( rand(65,90) );
		
		while( $this->esiste_codice_op( $cod_operatore ) );
		
		$record["codice_op"] = $cod_operatore;
		
		$this->db->where("id", $record["id"]);
		$this->db->delete("iscrizioni");
		unset($record["id"]);
		
		return $this->db->insert ('utenti', $record);
		
	
	}
	
	public function cambia_ruolo(){
		
		$id_utente = $this->input->post("utenti");
		$dati_update["id_ruolo "]= $this->get_id_ruolo ( $this->input->post("ruolo") ); 
		
		$this->db->where("id", $id_utente);
		return $this->db->update("utenti", $dati_update);
	}
	
	
	public function non_approvare(){
		
		$id_richiesta = $this->input->post("utenti");
		
		$query_cancella = "DELETE FROM iscrizioni  WHERE id = $id_richiesta";
		
		$query_result = $this->db->query($query_cancella);
		
		return $query_result;
	}
	
	public function ban(){
		
		$banned_email = [];
		
		$id_richiesta = $this->input->post("utenti");
		
		$query_richiesta = "SELECT nome, cognome, email, password FROM iscrizioni WHERE id = ?";
		
		$query_result = $this->db->query($query_richiesta, array( $id_richiesta ));
		
		$banned_email["email"] = $query_result->result_array()[0]["email"];
		
		$query_cancella = "DELETE FROM iscrizioni  WHERE id = ?";
		
		$query_result = $this->db->query($query_cancella, array( $id_richiesta ));
		
		$this->db->insert("banned_email", $banned_email);
		
		return $query_result;
	}
	
	public function cancella_utente(){
		
		$banned_email = [];
		
		$id_richiesta = $this->input->post("utenti");
		
		$this->db->where("id", $id_richiesta);
		
		return $this->db->delete("utenti");
	}
	
	
	
	public function salva_dati(){
		
		if( $this->e_utente_iscritto( $this->input->post("email") ) )
			return "C'è già una richiesta di iscrizione con questa email, attendere l'approvazione";
		
		if( $this->e_utente_approvato( $this->input->post("email") ) )
			return "C'è già un utente iscritto con questa email!";
		
		if( $this->e_bannato($this->input->post("email")) ){
			
			return "Spiacente, a causa delle tue continue richieste respinte sei stato bandito!";
			
		}
		
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$secret = "6LekgxcUAAAAALMWgr6vTPZyBw5DSF6Pboyf_901";
		$data = array('response' => $this->input->post("g-recaptcha-response") , 'secret' => $secret);
		$id_ruolo = $this->input->post("id_ruolo");
		
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data)
			)
		);
		
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result === FALSE)  return $result; 
		
		$query_params = [];
		$skip_keys = ["g-recaptcha-response", "conf_password", "id_ruolo"];
		
		foreach( $this->input->post() as $key => $value){
			
			if(in_array($key, $skip_keys) === true) continue;
				
			if($key == "password") $value = hash("sha256", $value);	
			
			$query_params[$key] = $value;
		}
		
		if( !empty($id_ruolo) ){
			
			$query_params["id_ruolo"] = $id_ruolo;
			return $this->db->insert("utenti", $query_params);
			
		}
		
		else return $this->db->insert("iscrizioni", $query_params );
		
	}
}

?>
