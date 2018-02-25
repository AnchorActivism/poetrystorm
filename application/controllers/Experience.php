<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Experience extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
	}

	public function index($origin=null, $reply=null) {


		log_message('debug', 'TestDebug1');

		$this->load->library('form_validation');
		$this->form_validation->set_rules('line', 'Line', 'required');

		if ($this->form_validation->run() !== FALSE)
		{
			$this->load->model('send_model');

			$data = array('line' => $this->input->post('line'), 'reply_to' => $this->input->post('reply_to'));

			$insert_id = $this->send_model->insert($data);

			if($insert_id!==false)
			{
				
				if(isset($origin)&&$origin!==0) 
				{
					redirect('experience/'.$origin.'/'.$insert_id);
				}
				else
				{
					redirect('experience/'.$insert_id);
				}
			}
			else
			{
				echo "Fail";
			}

			$origin = $this->input->post('id');
		}

		$data['origin'] = $origin;
		$data['reply'] = $reply;

		if (isset($reply)) {
			$data['reply_to'] = $reply;
		}
		else if (isset($origin)) {
			$data['reply_to'] = $origin;
		}
		else
		{
			//new poem
			$data['reply_to'] = 0;
		}		

		$this->load->model('exp_model');

		if ($origin !== null && $origin !== 0 && $this->exp_model->get_line_by_id($origin))
		{//$origin line specified and exists

			//get origin line and all parent lines for view (non-AJAX... NEEDS UPDATING)
			//$data['lines'] = $this->exp_model->get_origin($origin);

			//specify submit cta for reply
			$data['submit_placeholder'] = 'Add a line...';
			$data['submit_cta'] = 'PASS IT ON';


			if ($reply !== null && $reply!=='0' && $this->exp_model->get_line_by_id($reply))
			{//$reply line has been selected AND exists

				//add replies to view data
				$data['replies'] = $this->exp_model->get_origin_replies($reply, $origin);

			}
			else
			{
				$data['replies'] = $this->exp_model->get_latest_replies($origin);
			}

		}
		else
		{
			//no origin is specified, retrieve and accomodate first lines
			$data['lines'] = $this->exp_model->get_latest();
			$data['first_lines'] = true;
			$data['submit_placeholder'] = 'Start a poem...';
			$data['submit_cta'] = 'POST FIRST LINE';
		}

		$data['css'] = array('experience');

		$data['js'] = array('jquery-2.1.1.min', 'js.cookie', 'controller');

		if (!isset($origin)) {$origin = '\'null\'';}

		//$data['js_scripts'][1] = '';

		$data['js_vars'] = array(
			'origin_id' => $origin,
			'base_url' => '\''.base_url().'\'',
			);

		$this->load->view('header', $data);

		$this->load->view('experience', $data);

		$this->load->view('footer', $data);


	}

}