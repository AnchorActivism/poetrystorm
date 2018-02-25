<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_lib {

	public function index()//login
	{

		$CI =& get_instance();

		//form validation
		$CI->load->library('form_validation');
		$CI->form_validation->set_rules('email_address', 'Email Address', 'required|valid_email');
		$CI->form_validation->set_rules('password', 'Password', 'required|min_length[4]');

		if ( $CI->form_validation->run() !== false ) {
			// it's valid
			$CI->load->model('admin_model');
			$result = $CI
				->admin_model
				->verify_user(
					$CI->input->post('email_address'), 
					$CI->input->post('password')
				);
			//authentication
			if ($result !== false) {
				// person has an account
				
				$_SESSION['email'] = $CI->input->post('email_address');
				redirect('home');
			}

		}

		$CI->load->view('login_view');
		
	}

}