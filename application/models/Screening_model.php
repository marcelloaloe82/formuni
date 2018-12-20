<?php

class Screening_model extends CI_Model{

	public $lingua 					;
	public $latitudine 				;
	public $longitudine 			;
	public $angolo		 			;
	public $zoom			 			;
	public $pitch			 			;
	public $indirizzo			 		;
	public $codice_op			 	;


	public function __construct(){
                
		parent::__construct();
		$this->load->database();
				
    }

	public function get_screenings(){
		
		$query = $this->db->get("screening");
		return json_encode ($query->result(), JSON_UNESCAPED_UNICODE);
	}

	
	
	public function get_screening_from_coords(){
		
		$lat = $this->input->post("latitudine");
		$lng = $this->input->post("longitudine");
		
		$str_query = "SELECT * FROM screening WHERE latitudine = $lat and longitudine = $lng";
		
		$query = $this->db->query($str_query);
		
		// var_dump($this->db->last_query()); die;
		return json_encode ($query->result(), JSON_UNESCAPED_UNICODE);
		
	}
	
	private function esiste_gia($lat, $lng){
		
		$str_query = "SELECT count(*) as num_record FROM screening WHERE latitudine = $lat and longitudine = $lng";
		$query_res = $this->db->query($str_query) ->result();
		$conteggio = intval($query_res[0]->num_record);
		
		if($conteggio === 0)
			return false;
		else return true;
		
	}
	
	public function salva_screening(){
		
		$this->lingua 						= $this->input->post("lingua");
		$this->angolo 						= $this->input->post("angolo");
		$this->latitudine	 				= $this->input->post("latitudine");
		$this->longitudine 				= $this->input->post("longitudine");
		$this->zoom		 				= $this->input->post("zoom");
		$this->pitch		 					= $this->input->post("pitch");
		$this->indirizzo	 				= $this->input->post("indirizzo");
		$this->codice_op	 			= $this->input->post("codice_op");
		
		if($this->esiste_gia( $this->latitudine, $this->longitudine) === false)
			return $this->db->insert('screening', $this);	
		
		else return -1;
	}
	
	public function edit($id_record){
		
		$this->lingua 						= $this->input->post("lingua");
		$this->angolo 						= $this->input->post("angolo");
		$this->latitudine	 				= $this->input->post("latitudine");
		$this->longitudine 				= $this->input->post("longitudine");
		$this->zoom		 				= $this->input->post("zoom");
		$this->pitch		 					= $this->input->post("pitch");
		$this->indirizzo	 				= $this->input->post("indirizzo");
		$this->codice_op	 			= $this->input->post("codice_op");
		
		$this->db->where("id", $id_record);
		return	 $this->db->update('screening', $this);	
		
		
	}
	
	public function elimina($id_record){
		
		$this->db->where("id", $id_record);
		return $this->db->delete('screening');	
		
	}
	
}
