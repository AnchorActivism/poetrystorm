<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Verify extends MY_Controller {

    
    public function __construct()
    {
        parent::__construct();
    }

    public function index($type = null)
    {
		
		switch ($type) 
		{

			case null:
			$this->load->library('form_validation');
			$this->form_validation->set_rules('verify', 'Verification Code', 'required|xss_clean|max_length[111]');

			if ( $this->form_validation->run() !== false ) {
				redirect('verify/code/'.$this->input->post('verify'));
			}

			$this->load->view('header');

				echo form_open('verify');

				echo form_label('Verify:', 'verify');
				echo form_input('verify','', 'id="verify_input"');

				echo form_submit('submit', 'Verify');

				echo form_close();

			$this->load->view('footer');
			break;

			case 'newAccount';
			echo "<h1>Verify New Account</h1>";
			echo anchor('verify/send/newAccount', 'Send Confirmation Email', 'title="Send Confirmation Email"');
			break;

			case 'connectAccounts':
			echo "<h1>Connect Accounts</h1><h2>An account with that email already exists.</h2>";
			echo anchor('verify/send/connectAccounts', 'Connect Accounts', 'title="Connect Accounts"');
			break;

		}
    }

	public function send($type = null, $provider = null) {

		$this->load->model('verify_model');

		//create random code record in db
		$code = $this->verify_model->create_code($type, $provider);

		if (isset($code)) {
			log_message('debug', 'created verify code');
			$this->verify_model->send_verification_email($code);

			$this->load->view('header');
			echo "<h1>Verfication Code Sent</h1><h2>Please check your email.</h2>";
			$this->load->view('footer');
		}

	}

	public function code($verificationtext=null) //verify outside code with database and update
	{

		if ($verificationtext==null)
		{
			$this->load->view('header');

			echo "<h1>No Code Was Provided</h1><h2>Please check your inbox for the latest email which contains a confirmation code, or manually enter a code </h2>";
			echo anchor('verify', 'here.', 'title="Click Here to Enter Verification Code."');

			$this->load->view('footer');
		}
		else
		{
			$this->load->model('verify_model');

			$data = $this->verify_model->verify($verificationtext);

			if (!empty($data))
			{
				switch ($data->type) 
				{
					case null:
					echo "<h1>What's happening here?</h1><h2>Nothing seems to have happened.";
					log_message('error', 'null type when verifying verification code');
					break;

					case 'newAccount';
					echo "<h1>Email Confirmed!</h1>";
					break;

					case 'connectAccounts':
					redirect('login/service/'.$data->provider);
					break;

					$this->load->view('header');
					$this->load->view('footer');		
				}
			}
		}
	}
}