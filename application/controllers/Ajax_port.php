<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax_port extends MY_Controller {

	function __construct()
	{
		log_message('debug', 'So-far-so-good: 0');
		parent::__construct();
	}

	public function test_and_encode($r) 
	{
		if ($r) {
			return json_encode($r);
		}
		else
		{
			return FALSE;
		}
	}

	public function get_top_entries()
	{
		log_message('debug', 'So-far-so-good: 1');
		$this->load->model('exp_model');
		log_message('debug', 'So-far-so-good: 2');
		echo json_encode($this->exp_model->get_latest());
	}

	public function get_origin($id) 
	{
		$this->load->model('exp_model');

		if ($line = $this->exp_model->get_line_by_id($id)) {

			$line = array($line);

			echo json_encode($line);

		}

	}

	public function get_origin_parents($origin)
	{
		$this->load->model('exp_model');

		$parents = $this->exp_model->get_origin_parents($origin);
/*
		$v = $this->test_and_encode($parents);

		log_message('debug', 'AJAX_PORT>get_origin_parents> $parents: '.$v);

		echo $this->test_and_encode($parents);
*/

		if ($parents!==FALSE) {
			log_message('debug', 'AJAX_PORT>get_origin_parents> $parents: '.json_encode($parents));
			echo json_encode($parents);
		}
		else
		{
			echo 'false';
		}

	}

	public function get_origin_replies($origin)
	{
		$this->load->model('exp_model');
		echo json_encode($this->exp_model->get_origin_replies($origin));
	}

	public function get_thread_alternatives($thread=null) 
	{
		$this->load->model('exp_model');

		echo $this->test_and_encode(
			$this->exp_model->get_thread_alternatives($this->input->post('json'))
			);
	}

	public function get_poem_tree($top) {
		$this->load->model('exp_model');
		$POEM = $this->exp_model->get_poem_tree($top);
		log_message('debug', 'mindborn'.print_r($POEM), TRUE);
		//echo json_encode($POEM);
	}

	public function insert_line() {
							

		if ($this->input->post('json')) {

			$json = $this->input->post('json');	

			$data = array('line' => $json[0], 'reply_to' => $json[1]);

			$this->load->model('send_model');
			$new_id = $this->send_model->insert($data);

			if ($new_id) {
																	log_message('debug', 'Ajax_port > $new_id 															= '.$new_id);
				echo json_encode( $new_id );
			}

																	//log_message('debug', 'Ajax_port > post DATA EXISTS');
																	//log_message('debug', 'Ajax_port > $json 														='.$json);
		}
																	//log_message('debug', 'Ajax_port > $data 														='.$data);		
	}

}