<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {

	public function create( $first_name, $last_name, $email_address, $password )
	{

		$data = array(
			'first_name'=>$first_name,
			'last_name'=>$last_name,
			'email_address'=>$email_address,
			'password'=>sha1($password)
			);
		$this->db->insert('users', $data);
		return $this->db->insert_id();

	}

	public function test() 
	{
	}

	public function update( $user_id, $first_name, $last_name, $email_address, $password )
	{

		$data = array(
			'first_name'=>$first_name,
			'last_name'=>$last_name,
			'email_address'=>$email_address,
			'password'=>sha1($password)
			);

		$this->db->where('id', $user_id);
		$this->db->update('users', $data);

	}

	public function find_by_id( $id ) 
	{

		$q = $this
			->db
			->where('id', $id )
			->limit(1)
			->get('users');

		if ( $q->num_rows > 0 ) {
			return $q->row();
		}
		return false;

	}

	public function find_by_email( $email ) 
	{

		$q = $this
			->db
			->where('email_address', $email )
			->limit(1)
			->get('users');

		if ( $q->num_rows > 0 ) {
			return $q->row();
		}
		return false;

	}

	public function find_by_email_and_password( $email, $password ) 
	{

		$q = $this
			->db
			->where('email_address', $email)
			->where('password', sha1($password))
			->limit(1)
			->get('users');

		if ( $q->num_rows > 0 ) {
			return $q->row();
		}
		return false;

	}

}