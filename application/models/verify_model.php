<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Verify_model extends CI_Model {

	public function create_code($type = null, $provider=null) 
	{
		$this->load->helper('string');
	
		$code = random_string('alnum', 20);

		$data = array(
			'uid' => $this->usr->id,
			'code' => $code,
			'type' => $type,
			'provider' => $provider
			);

		$this->db->insert('verify', $data);
		return $code;

	}

	public function send_verification_email($verificationText)
	{
		//db $email with $uid
		$this->db->select('email_address');
		$query = $this->db->get_where('users', array('id' => $this->usr->id));
		$row   = $query->row();
		$email = $row->email_address;


		$this->load->library('email');

		$config['protocol'] = 'smtp';
		$config['mailpath'] = '/usr/sbin/sendmail';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;

		$this->email->initialize($config);

		$this->email->set_newline("\r\n");
		$this->email->from('patrick.turmala@gmail.com', "TagTree");
		$this->email->reply_to('no-reply@gmail.com');
		$this->email->to($email);		
		$this->email->subject("Email Verification");
		$this->email->message("Dear User,\nPlease click on below URL or paste into your browser to verify your Email Address\n\n ".anchor($verificationText, base_url($verificationText))."\n\n\nThanks\nAdmin Team");
		$this->email->set_alt_message("Dear User,\nPlease paste below URL into your browser:\n".base_url($verificationText)."\n\n\nThanks\nAdmin Team");

		$this->email->send();

		echo $this->email->print_debugger();
	}

	public function verify($code) 
	{
		log_message('debug', 'uid from verify_model: '.$this->usr->id);

		if (!empty($code)) 
		{
			//find latest record of same 'type'
			$q1 = $this->db
					->where('code', $code)
					->limit(1)
					->get('verify');

			$r1 = $q1->row();

			$q2 = $this->db
					->where('type', $r1->type)
					->order_by('date_created', 'DESC')
					->limit(1)
					->get('verify');

			$r2 = $q2->row();

			if ($r2->code == $code)
			{		
				$data = array('verified'=>'1');
				$this->db->where('code', $code)->update('verify', $data);

				if ($this->db->affected_rows()>0)
				{
					return $r1;
					die();
				}
				die('This code has already been verified');
			}
			else
			{
				$this->load->view('header');
				echo "<h1>Latest Code Not Matched</h1><h2>Please check your inbox for the latest email, or</h2>";
				echo anchor('verify/send/'.$r1->type, 'Send Again', 'title="Send Again"');
				$this->load->view('footer');

				die();

			}
		}
		die('No code was provided.');
	}

}