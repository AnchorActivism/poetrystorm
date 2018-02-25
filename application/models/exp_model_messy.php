<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exp_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}

/*
	public function get_poem_tree($focus) {
		$top = $this->get_line_by_id($focus);


	}
	*/

	//get poem tree
		// get $focus line

		// get $focus parents

		// get $focus line reply thread as nested objects

		// for each $focus line reply

			// get alternatives to line as nested array (limit 25)

			// foreach alternative
				// get reply thread as nested objects





		// get first thread (up to 25 lines) from focus line

		// for each line in thread (including the first):
			// get alternatives

				// for each alternative
					//get first thread

	//example JSON object
/*

			{
			"id": 255, 
			"line": "This is line A1.", 
			"alternatives": 
				[
					{
					"id": 380, 
					"line": "This is line A2.", 
					"alternatives": 
						[
							null
						],
					"replies":
						[
							null
						]
					},
					{
					"id": 405, 
					"line": "This is line A3.", 
					"alternatives": 
						[
							null
						],
					"replies":
						[
							null
						]
					},
					{
					"id": 900, 
					"line": "This is line A4.", 
					"alternatives": 
						[
							
						],
					"replies":
						[
							{
							"id": 452, 
							"line": "This is line A4a.", 
							"alternatives": 
								[
									null
								],
							"replies":
								[
									null
								]
							},
							{
							"id": 455, 
							"line": "This is line A4b.", 
							"alternatives": 
								[
									null
								],
							"replies":
								[
									null
								]
							},
							{
							"id": 9000, 
							"line": "This is line A4c.", 
							"alternatives": 
								[
									null
								],
							"replies":
								[
									null
								]
							}
						]
					}
				],
			"replies":
				[
					{
					"id": 256, 
					"line": "This is line B1.", 
					"alternatives": 
						[
							null
						]
					},
					{
					"id": 257, 
					"line": "This is line C1.", 
					"alternatives": 
						[
							null
						]
					},
					{
					"id": 256, 
					"line": "This is line D1.", 
					"alternatives": 
						[

						]
					}
				]
			}

	@@ SIMPLIFIED @@
	{
	"poem_tree": 
		[
			{
			A1 
			"alternatives": 
				[
					{
					A2 
					"alternatives": 
						[
							null
						],
					"replies":
						[
							null
						]
					},
					{
					A3 
					"alternatives": 
						[
							null
						],
					"replies":
						[
							null
						]
					},
					{
					A4
					"alternatives": 
						[
							
						],
					"replies":
						[
							{
							A4a
							"alternatives": 
								[
									null
								],
							"replies":
								[
									null
								]
							},
							{
							A4b
							"alternatives": 
								[
									null
								],
							"replies":
								[
									null
								]
							},
							{
							A4c
							"alternatives": 
								[
									null
								],
							"replies":
								[
									null
								]
							}
						]
					}
				],
			"replies":
				[
					{
					B1 
						[
							null
						]
					},
					{
					C1 
					"alternatives": 
						[
							null
						]
					},
					{
					D1
					"alternatives": 
						[

						]
					}
				]
			}
		]
	}



*/

/*
	public function get_poem_block($top) {
		//returns non-hierarchical obj of poem, starting with first (top) line

		$top = $this->get_line_by_id($top);

		if ($top !== false) {
			log_message('debug', 'top: '.$top);

			$poem[0][0] = $top; //create $poem array, add $top line

			do {

				$i = count($poem) - 1;

				foreach ($poem[$i] as $key => $value) {
					return null; //CONTINUE THIS FUNCTION LATER
				}
			}
		}
	}
	

	public function get_poem_tree($top) {

		$top = $this->get_line_by_id($top);
	
		if ($top !== false)  {

			log_message('debug', 'top: '.$top);

			$poem[0][0] = $top; //create $poem array, add $top line

						//log_message('debug', 'LINE: '.print_r($poem[0][0],TRUE));

					//$replies = $this->get_line_by_reply_to($poem[0][0]->id); NOPE

						//log_message('debug', 'replies'.print_r($replies,TRUE));
			do {

				$i = count($poem) - 1;

				foreach ($poem[$i] as $key => $value) {

					//INDEX
						log_message('debug', 'INDEX: '.$i);					

					//id
						log_message('debug', '(b) id: '.$value->id);

					$replies = $this->get_line_by_reply_to($value->id);

					if ($replies !== FALSE) {
	
						if (!isset($poem[$i+1])) {

							$poem[$i+1] = array();

						}

						$row = $poem[$i+1];

						foreach ($replies as $key => $value) {

							array_unshift($row, $value);

						}

						$poem[$i+1] = $row;

			//log_message('debug', 'poem: '.print_r($poem, TRUE));
						
					}
			
				}


				if ($replies == FALSE) {
					log_message('debug', 'DONE');
				}
				else
				{
					log_message('debug', 'ON TO THE NEXT ONE...');
				}

			} while ($replies!==FALSE);


			log_message('debug', 'poem: '.print_r($poem, TRUE));

			return $poem;
			//return 'done';
		}
	}
/*
	
	public function get_poem_tree($op) {
		//Constructs a list of all FIRST replies and their alternatives.
		//Does not get replies OF alternatives.. as of now.

		$lines[0] = get_line_by_id($op);

	}/*
*/
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

	public function get_focus_parents($focus) {
		
		//Uses the focus id to return an array of objects 
		//which are parents to the focus line.

		//return array('hello');

																log_message('debug', 'EXP_MODEL>get_focus_parents> $focus: '.$focus);

		$parent_id = $focus;	// We don't actually put this in $lines.
		$lines = array(); 		// variable to be returned

		do {

			//obj of table row from $reply_to id (parent id)
			$r = $this->get_line_by_id($parent_id);

																log_message('debug', 'EXP_MODEL>get_focus_parents> $r: '.json_encode($r));

			if ($r !== FALSE) {
																log_message('debug', 'EXP_MODEL>get_focus_parents> $r->id: '.json_encode($r->id));
				if ($r->id !== $focus) {
					
					$new = array();
					$new[0] = $r;
					log_message('debug', 'EXP_MODEL>get_focus_parents> $new: '.json_encode($new));

				}												

				//set $parent_id for next "do"
				//based on parent_id (reply_to) from current line
				$parent_id = $r->reply_to;
																log_message('debug', 'EXP_MODEL>get_focus_parents> $parent_id: '.$parent_id);
				if (isset($new)) {
		
					//prepends to $lines
					array_unshift($lines, $new);
				}

			}
			else
			{
				$parent_id = 0;
				//return FALSE;
			}

		} while($parent_id>0);

																log_message('debug', 'EXP_MODEL>get_focus_parents> $lines: '.json_encode($lines));

		return $lines;
	}

public function get_focus_replies($focus) {

		$lines = array();
		$id = $focus;
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

		return $lines;

	}

	public function get_thread_alternatives($thread) {


		//Uses the focus id to return an array of objects 
		//which are parents to the focus line.

		//return array('hello');

																log_message('debug', 'EXP_MODEL>get_focus_parents> $focus: '.$focus);

		$parent_id = $focus;	// We don't actually put this in $lines.
		$lines = array(); 		// variable to be returned

		do {

			//obj of table row from $reply_to id (parent id)
			$r = $this->get_line_by_id($parent_id);

																log_message('debug', 'EXP_MODEL>get_focus_parents> $r: '.json_encode($r));

			if ($r !== FALSE) {
																log_message('debug', 'EXP_MODEL>get_focus_parents> $r->id: '.json_encode($r->id));
				if ($r->id !== $focus) {
					
					$new = array();
					$new[0] = $r;
					log_message('debug', 'EXP_MODEL>get_focus_parents> $new: '.json_encode($new));

				}												

				//set $parent_id for next "do"
				//based on parent_id (reply_to) from current line
				$parent_id = $r->reply_to;
																log_message('debug', 'EXP_MODEL>get_focus_parents> $parent_id: '.$parent_id);
				if (isset($new)) {
		
					//prepends to $lines
					array_unshift($lines, $new);
				}

			}
			else
			{
				$parent_id = 0;
				//return FALSE;
			}

		} while($parent_id>0);

																log_message('debug', 'EXP_MODEL>get_focus_parents> $lines: '.json_encode($lines));

		return $lines;

	}

/*
	public function get_focus_parents($focus_id) {
		//Organizes lines as nested objects,
		//starting with the last line and moving backwards.
		
		$lines = array();

		do {

			//returns obj of table row
			$r = $this->get_line_by_id($focus_id);

			if ($r !== false) {

				if (isset($last)) {
					//put last line obj inside parent obj replies array
				
					$r->replies = array();
					array_unshift($r->replies, $last);
				
				}

				//set $id and $last for next "do"
				$id = $r->reply_to;
				$last = $r;

			}

		} while($id>0);

		return $last;
	}*/
/*
	public function get_focus($id) {
		
		$lines = array();

		do {

			//returns obj of table row
			$r = $this->get_line_by_id($id);

			if ($r !== false) {

				//prepends to $lines
				array_unshift($lines, $r);

				//set id for next "do"
				$id = $r->reply_to;

			}

		} while($id>0);

		return $lines;
	}*/

	public function get_latest() {

		$q = $this->db
				->select('*')
				->from('lines')
				->where('reply_to', '0')
				->order_by('id', "desc")
				->limit(200)
				->get();

		

		/*$q = $this->
				db->
				get_where('lines', array('reply_to' => $zero), 100)->
				order_by('id', "asc");

*/
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

/*
	public function get_focus_replies($reply, $focus) {
		
		//current
		$q = $this->
				db->
				where('id', $reply)->
				limit(1)->
				get('lines');

		$lines[0] = $q->row_array();


		//next	
		$q = $this->
				db->
				where('reply_to', $focus)->
				where('id >', $reply)->
				limit(1)->
				get('lines');

		if ($q->num_rows()<1) {

			$q = $this->
					db->
					where('reply_to', $focus)->
					where('id !=', $reply)->
					limit(1)->
					order_by('id', 'asc')->
					get('lines');
		}
		
		$lines[1] = $q->row_array();
		
		
		//prev
		if (isset($lines[1])) {

			$q = $this->
					db->
					where('reply_to', $focus)->
					where('id <', $reply)->
					limit(1)->
					get('lines');

			if ($q->num_rows()<1) {

				$q = $this->
						db->
						where('reply_to', $focus)->
						where('id !=', $lines[1]['id'])->
						limit(1)->
						order_by('id', 'desc')->
						get('lines');
			}
			
			$lines[2] = $q->row_array();
		
		}

		return $lines;

	}
	*/

	public function get_latest_replies($focus) {
		$q = $this->
				db->
				where('reply_to', $focus)->
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

					log_message('debug', 'reply_to: '.$reply_to);


		if ( $q->num_rows() > 0 ) {
			return $q->result();
		}
		return FALSE; 
	}
}