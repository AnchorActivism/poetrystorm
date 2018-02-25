<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();

        error_reporting(E_ALL);
        //ini_set('display_errors', 1);

        //$this->load->library('session');


/*
        if ( !isset($_SESSION['email'])) {
            
            if (current_url() !== base_url().'home/guest') {
     
              redirect('home/guest');
            
            }
        }
        else
        {
            $this->load->model('user_model');
            $this->usr = $this->user_model->find_by_email($_SESSION['email']);
        }
 /*       

log_message('debug', '######-!-!-!-!-!-!-!-!-###############**********!*!*!*!*!*!*!07!-!-!-!-!');
            
        if (isset($_SESSION['email'])) {
        	log_message('debug', '######-!-!-!-!-!-!-!-!-###############**********!*!*!*!*!*!*!07!-!-!-!-!logged user: '.$_SESSION['email']);
	        $this->load->model('user_model');
	        $this->session->user = $this->user_model->find_by_email($_SESSION['email']);
    	}
        else
        {
            log_message('debug', '######-!-!-!-!-!-!-!-!-###############**********!*!*!*!*!*!*!07!-!-!-!-!NOPE');
        }
    */
    }

    public function index() {

        log_message('debug', '1234Cats');

    }
}