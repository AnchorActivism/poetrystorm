<?php 

class Main_model extends CI_Model {
	
	public function __construct()
	{
		$this->load->database();
	}

	public function get_top_entries()
	{

		log_message('debug', 'So-far-so-good: 3');
		$q = $this->db
				->select('*')
				->from('lines')
				->where('reply_to' => '0')
//				->join('sources', 'entries.source_id = sources.id')
				->order_by('id', "asc")
				->limit(200)
				->get();

		log_message('debug', 'So-far-so-good: 4');
		
		$entry_ids = array_column($q->result_array(), 'id');

		log_message('debug', 'So-far-so-good: 5');

/*
		$entries = array();
		$entries["full"] = $q->result_array();
		$entries['ids'] = $entry_ids;

		log_message('debug', 'So-far-so-good: 6');*/

		return $q->result_array();
	}

	public function get_entries($slug = FALSE)
	{
		if ($slug === FALSE)
		{
			$query = $this->db->get('entries');
			return $query->result_array();
		}

		$query = $this->db->get_where('entries', array('slug' => $slug));
		return $query->row_array();
	}

	public function gather_records() 
	{
	
	$ids 	   = $this->input->post('ids');
	$fetch_col = $this->input->post('fetch_col');
	$inp_col   = $this->input->post('inp_col');


		$q = $this->db
				->select(''.$inp_col.', '.$fetch_col.'')
				->where_in($inp_col, $ids)
				->get('tag_entry');
	
		$r = json_encode($q->result_array());

		return $r;
	}

	public function uri_tags($offset = 0) {

		if ($this->uri->segment(1)==='home') {
			$offset += 2;
		}

		$tmp = $this->uri->segment_array();
		$tags = array();

		for ($i=0; $i<count($tmp)-$offset+1;$i++) {
			$tags[$i] = $tmp[$i+$offset];
		}

		return $tags;
	
	}
}