<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller 
{
    
    public function __construct()
    {
        parent::__construct();

		$this->load->model('exp_model');

    }

	public function index($focus=null, $reply=null) {

redirect('experience');

/*
		$this->load->library('form_validation');
		$this->form_validation->set_rules('line', 'Line', 'required');

		if ($this->form_validation->run() !== FALSE)
		{
			$this->load->model('send_model');

			$data = array('line' => $this->input->post('line'), 'reply_to' => $this->input->post('reply_to'));

			$insert_id = $this->send_model->insert($data);

			if($insert_id!==false)
			{
				
				if(isset($focus)&&$focus!==0) 
				{
					redirect('experience/'.$focus.'/'.$insert_id);
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

			$focus = $this->input->post('id');
		}

		$data['focus'] = $focus;
		$data['reply'] = $reply;

		if (isset($reply)) {
			$data['reply_to'] = $reply;
		}
		else if (isset($focus)) {
			$data['reply_to'] = $focus;
		}
		else
		{
			//new poem
			$data['reply_to'] = 0;
		}		


		if ($focus !== null && $focus !== 0 )
		{//line specified

			if ($this->exp_model->get_line_by_id($focus)) {
			//line exists
				
				//get focused line and all parent lines for view
				$data['lines'] = $this->exp_model->get_focus($focus);


				if ($reply !== null && $reply!=='0')
				{//A specific reply has been selected

					if ($this->exp_model->get_line_by_id($reply)) 
					{//reply exists
					
						//add replies to view data
						$data['replies'] = $this->exp_model->get_replies($reply, $focus);

					}
					else
					{
						$data['replies'] = $this->exp_model->get_latest_replies($focus);
					}
				}
				else
				{
					$data['replies'] = $this->exp_model->get_latest_replies($focus);
				}
			}
			else
			{
				$data['lines'] = $this->exp_model->get_latest();
			}
		}
		else
		{
			$data['lines'] = $this->exp_model->get_latest();
		}


		$data['css'] = array('experience');

		$data['js'] = array('main');

		$this->load->view('header', $data);

		$this->load->view('experience2', $data);

		$this->load->view('footer', $data);

*/

	}

	public function guest() {

		$data = array(
						'css' => array('main', 'home_guest')
					);

		$data['lines'] = $this->exp_model->get_latest();


		$this->load->view('header', $data);


		$this->load->view('new');


		$this->load->view('footer');


	}

}