<?php

class Admin_model extends CI_Model {

	public function verify_user($email, $password)
	{	
		$q = $this
			->db
			->where('email_address', $email)
			->where('password', sha1($password))
			->limit(1)
			->get('users');

		if ( $q->num_rows() > 0 ) {
			return $q->row();
		}
		return false;
	}

	public function find_by_provider_uid( $provider, $provider_uid)
	{

		log_message('debug', 'find_by_provider_uid: initialized. provider: "'.$provider.'". unique: "'.$provider_uid);
		log_message('debug', 'PROVIDER: '.$provider.' --- ID: '.$provider_uid);

		$q = $this
			->db
			->where('provider', $provider)
			->where('provider_uid', $provider_uid)
			->limit(1)
			->get('authentications');
		

		log_message('debug', 'find_by_provider_uid: continuing');

		if ( $q->num_rows() > 0 ) {
			log_message('debug', 'find_by_provider_uid: FOUND A RESULT');
			return $q->row();
		}
		log_message('debug', 'did not find a result');
		return false;
	}

	public function create_authentication( $user_id, $provider, $provider_uid, $email, $display_name, $first_name, $last_name, $profile_url, $website_url )
	{

		$q = $this->db
		->where('user_id', $user_id)
		->where('provider', $provider)
		->limit(1)
		->get('authentications');

		if ( $q->num_rows == 0) {



			log_message('debug', 'Admin Model: PROVIDER UID: '.$provider_uid);

			$data = array(
				'user_id' 		=> $user_id,
				'provider' 		=> $provider,
				'provider_uid'  => $provider_uid,
				'email' 		=> $email,
				'display_name'  => $display_name,
				'first_name'    => $first_name,
				'last_name' 	=> $last_name,
				'profile_url'   => $profile_url,
				'website_url'   => $website_url
				);

			log_message('debug', 'Admin Model: PROVIDER UID: '.$provider_uid);


			$this->db->insert('authentications', $data);

			return true;

		}
		log_message('debug', 'An authentication has been created previously for user #'.$user_id.' with '.$provider.'.');
		return false;
	}
	
	public function find_by_user_id( $user_id )
	{
		$q = $this
			 ->db
			 ->where('user_id', $user_id)
			 ->limit(1)
			 ->get('authentications');

		if ( $q->num_rows > 0 ) {
			return $q->row();
		}
		return false;		
	}

	public function connect_accounts_ok($provider, $uid) {

		$q = $this->db
			->where('provider', $provider)
			->where('uid', $uid)
			->where('type', 'connectAccounts')
			->where('verified', 1)
			->limit(1)
			->get('verify');

		if ( $q->num_rows > 0 ) {
			return true;
		}
		return false;
	}
}