<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exp_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}

	public function get_poem_tree($top) {
		//Constructs a hierarchical object of the entire poem
		//underneath the top line including replies and alternatives.

		//get top line obj
		$r = $this->get_line_by_id($top);

		if ($r !== false) {
			
			$replies;

			$new[0] = $r;

			if ($r->reply_to !== 0) {
				//if this isn't the top line, find alternatives

				do {

					get_line_by_reply_to($top);

				}
				while(get_line_by_reply_to($last) !== FALSE);
			}

		}
	}

	public function get_origin_parents($origin) {
		
		//Uses the origin id to return an array of objects 
		//which are parents to the origin line.

																		log_message('debug', 'EXP_MODEL>get_origin_parents> $origin 										='.$origin);

		$parent_id = $origin;	// We don't actually put this in $lines.
		$lines = array(); 		// variable to be returned

		do {

			//obj of table row from $reply_to id (parent id)
			$r = $this->get_line_by_id($parent_id);

																		//log_message('debug', 'EXP_MODEL>get_origin_parents> $r 										= '.json_encode($r));

			if ($r !== FALSE) {
																		log_message('debug', 'EXP_MODEL>get_origin_parents> $r->id 										= '.json_encode($r->id));
				if ($r->id !== $origin) {
					
					array_unshift($lines, array($r));
					//prepends to $lines

				}												

				//set $parent_id for next "do"
				//based on parent_id (reply_to) from current line
				$parent_id = $r->reply_to;
																		log_message('debug', 'EXP_MODEL>get_origin_parents> $parent_id 									= '.$parent_id);


			}
			else
			{
				$parent_id = 0;
				//return FALSE;
			}

		} while($parent_id>0);

																		log_message('debug', 'EXP_MODEL>get_origin_parents> $lines 										= '.json_encode($lines));

		return $lines;
	}

public function get_origin_replies($origin) {

		$lines = array();
		$id = $origin;
		$c = 0;

		do {

			//get lines where $reply_to = $id
			$q = $this->
					db->
					where('reply_to', $id)->
					limit(1)->
					get('lines');

			if ($q->num_rows()>0) {

				$r = $q->result();

				//append to $lines
				array_push($lines, $r);

				//set id for next "do"
				$id = $r[0]->id;
				$c++;
			}
			else
			{
				$c=100000000;
			}

		} while ( $c < 150);

																		log_message('debug', 'EXP_MODEL > get_origin_replies > $lines 										= '.json_encode($lines));
		return $lines;

	}

	public function get_thread_alternatives($thread) {


		//Uses the origin id to return an array of objects 
		//which are parents to the origin line.

		//return array('hello');


																		log_message('debug', 'EXP_MODEL > get_thread_alternatives > $thread:							= '.json_encode($thread));
															
		//$lines = array(); 		// variable to be returned

		foreach ($thread as $key => $value) {
						

			$thread_line_id = $value[0]['reply_to'];

																		log_message('debug', 'EXP_MODEL > get_thread_alternatives > $thread_line_id:					= '.json_encode($thread_line_id));

			if ($thread_line_id !== '0') {

				$r = $this->get_line_by_reply_to($thread_line_id);

				if ($r !== FALSE) {
					$value = $r;
																		log_message('debug', 'EXP_MODEL > get_thread_alternatives > $value: 		 					= '.json_encode($value));
					
					$thread[$key] = $value;

				}
			}
		}

																		log_message('debug', 'EXP_MODEL > get_thread_alternatives > $thread 							='.json_encode($thread));

		return $thread; //now more than just a 'thread'

	}

	public function get_origin($id) {
		
		$lines = array();

		do {

			//returns obj of table row
			$r = $this->get_line_by_id($id);

			if ($r !== false) {

				//prepends to $lines
				array_unshift($lines, array(array($r)));

				//set id for next "do"
				$id = $r->reply_to;

			}

		} while($id>0);

																		log_message('debug', 'EXP_MODEL > get_origin > $lines 											= '.json_encode($lines));

		return $lines;
	}

	public function get_latest() {

		$q = $this->db
				->select('*')
				->from('lines')
				->where('reply_to', '0')
				->order_by('id', "desc")
				->limit(200)
				->get();

		
		if ($q->num_rows > 0)
		{	

			$lines = array();

			foreach ($q->result() as $key => $value) {
			
				$new[0] = $value;
				array_push($lines, $new);
			
			}

			return $lines;
		}
		
		return false;
	}

	public function get_latest_replies($origin) {
		$q = $this->
				db->
				where('reply_to', $origin)->
				limit(3)->
				get('lines');

		if ($q->num_rows() > 0 ) 
		{
			return $q->result_array();

		}
		return false;
	}

	public function get_line_by_id($id) {

		if($q = $this->
				db->
				get_where('lines', array('id' => $id), 1))
		{	

			if ( $q->num_rows() > 0 ) {
				return $q->row();
			}
			return false; 
		}
	}
	
	public function get_line_by_reply_to($reply_to) {

		$q = $this->
				db->
				get_where('lines', array('reply_to' => $reply_to), 100);

																		log_message('debug', 'get_line_by_reply_to > $reply_to 											= '.$reply_to);


		if ( $q->num_rows() > 0 ) {
			return $q->result();
		}
		return FALSE; 
	}
}