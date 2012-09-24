<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_user_id() {
		$this->db->where('username', $this->session->userdata('username'));
		$this->db->select('id');		
		$query = $this->db->get('tbluser');
		foreach ($query->result() as $result) {
			return $result->id;
		}
	}

	public function get_user_info($user_id)
	{
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('tbldescription');
		
		return $query->result();	
	}

	public function get_feed_descriptions($user_id)
	{
		$sql = 'SELECT DISTINCT * FROM tblsenders_receivers, tbldocument, tbldescription WHERE (tblsenders_receivers.sender = '.$user_id.' OR tblsenders_receivers.receiver = '.$user_id.') AND tbldocument.tracking_id = tblsenders_receivers.tracking_id AND (tbldescription.user_id = tblsenders_receivers.receiver OR tbldescription.user_id = tblsenders_receivers.sender) AND tbldescription.user_id != '.$user_id.' ORDER BY tbldocument.tracking_id DESC';
		$query = $this->db->query($sql);
		if($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	public function get_relations($user_id)
	{
		$this->db->where('sender', $user_id);
		$this->db->or_where('receiver', $user_id);
		$query = $this->db->get('tblsenders_receivers');
		if($query->num_rows() > 0) {
			return $query->result();
		}
	}
	
	public function get_feed_descriptions_as_sender($user_id)
	{
		$sql = 'SELECT * FROM tblsenders_receivers, tbldocument, tbldescription WHERE tblsenders_receivers.sender = '.$user_id.' AND tbldocument.tracking_id = tblsenders_receivers.tracking_id AND tbldescription.user_id = tblsenders_receivers.receiver ORDER BY tbldocument.tracking_id DESC';
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			return $query->result();
		} else {
			return null;
		}
		
	}
	
	public function get_feed_descriptions_as_receiver($user_id)
	{
		$sql = 'SELECT * FROM tblsenders_receivers, tbldocument, tbldescription WHERE tblsenders_receivers.receiver = '.$user_id.' AND tbldocument.tracking_id = tblsenders_receivers.tracking_id AND tbldescription.user_id = tblsenders_receivers.sender AND tblsenders_receivers.verified = 1 ORDER BY tbldocument.tracking_id DESC';
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			return $query->result();
		} else {
			return null;
		}
	}
	
	public function get_non_verified_feeds($user_id)
	{
		$sql = 'SELECT * FROM tblsenders_receivers, tbldocument, tbldescription WHERE tblsenders_receivers.receiver = '.$user_id.' AND tbldocument.tracking_id = tblsenders_receivers.tracking_id AND tbldescription.user_id = tblsenders_receivers.sender AND tblsenders_receivers.verified = 0 ORDER BY tbldocument.tracking_id DESC';
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
		{
			return $query->result();
		} else {
			return null;
		}
	}
	
	public function get_descriptions_of_id($user_id)
	{
		$this->db->where('id', $user_id);
		$query = $this->db->get('tbluser');
		return $query->result();
	}
	
	public function get_id_of_sender($tracking_id, $user_id)
	{
		$this->db->where('receiver', $user_id);
		$this->db->where('tracking_id', $tracking_id);
		$query = $this->db->get('tblsenders_receivers');
		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				return $row->sender;
			}
		} else {
			return null;
		}
	}
	
	public function get_list_of_receivers()
	{
		//$this->db->select('id', 'first_name', 'last_name', 'profession', 'location');
		$query = $this->db->get('tbluser');
		if($query->num_rows() > 0)
		{
			return $query->result();
		} else {
			return null;
		}
	}
	
	public function get_relations_as_sender($user_id)
	{
		$this->db->where('sender', $user_id);
		$query = $this->db->get('tblsenders_receivers');
		if($query->num_rows() > 0) {
			return $query->result();
		}
	}
	
	public function get_relations_as_receiver($user_id)
	{
		$this->db->where('receiver', $user_id);
		$query = $this->db->get('tblsenders_receivers');
		if($query->num_rows() > 0) {
			return $query->result();
		}
	}
	
	public function get_document_info($tracking_id) {
		$this->db->where('tbldocument.tracking_id', $tracking_id);
		$this->db->where('tblsenders_receivers.tracking_id', $tracking_id);
		$this->db->from('tbldocument');
		$this->db->from('tblsenders_receivers');
		$query = $this->db->get('', 1, 1);
		if($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	public function get_sender_description($tracking_id, $user_id) {
		$this->db->where('tracking_id', $tracking_id);
		//$this->db->where('receiver', $user_id);
		$this->db->where('user_id', $user_id);
		$this->db->from('tblsenders_receivers');
		$this->db->from('tbldescription');
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	public function get_receiver_description($tracking_id, $user_id) {
		$this->db->where('tracking_id', $tracking_id);
		//$this->db->where('sender', $user_id);
		$this->db->where('user_id', $user_id);
		$this->db->from('tblsenders_receivers');
		$this->db->from('tbldescription');
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	public function get_receivers_names($tracking_id) {
		$sql = 'SELECT * FROM tblsenders_receivers, tbldescription WHERE tracking_id = '.$tracking_id.' AND receiver = user_id';
		// $this->db->where('tracking_id', $tracking_id);
		// $this->db->where('receiver', 'user_id');
		// $this->db->from('tblsenders_receivers');
		// $this->db->from('tbldescription');
		// $query = $this->db->get();
		$query = $this->db->query($sql);
		if($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	
	public function update_verification($tracking_id, $receiver) {
		$data = array(
			'verified' => 1,
			'date_time_received' => date('F d, Y g:ia', time())
			);
		$this->db->where('tracking_id', $tracking_id);
		$this->db->where('receiver', $receiver);
		$this->db->update('tblsenders_receivers', $data);
	}
	
}

?>
