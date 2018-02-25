<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        //log_message('debug' 'uid from login: '.$this->usr->email);
    }

	public function index() 
	{
		$this->load->library('login_lib');
		$this->login_lib->index();
		
	}
	public function service($provider) {

		log_message('debug', 'ASDFL;AKJDSFA;LKJDSF;ALKSDJF;ALKSDJF;ALKSDJFA;SLKDFJ;ASLKDFJAS;LDKFJAS;DLKFJAS;DLKFJAS;DLKFJA00000000000000000000000000000000000000000000000000000000000000000000000000#####################################_-----_-_-_---_-_-----____-_----_-_-_-_-_---_-_-_-_');
		try
		{
			log_message('debug', 'controllers.HAuth.login: loading HybridAuthLib');
			$this->load->library('HybridAuthLib');

			if ($this->hybridauthlib->providerEnabled($provider))
			{
				$service = $this->hybridauthlib->authenticate($provider);

				if ($service->isUserConnected()) {

					$user_profile = $service->getUserProfile();

					$data['user_profile'] = $user_profile;

					//load auth related models
					$this->load->model('admin_model');
					$this->load->model('user_model');
					$this->load->library('login_lib');

					log_message('debug', 'PROVIDER UID: '.$user_profile->identifier);

					# 1 - check if user already has authenticated using this provider
					$prior_auth = $this->admin_model->find_by_provider_uid( $provider, $user_profile->identifier );

					# 2 - if previously connected with provider, login and redirect to homepage
					if ($prior_auth) {
						log_message('debug', 'Authentication exists previously.');

						//login
						$this->usr = $this->user_model->find_by_id($prior_auth->user_id);
						$_SESSION['email'] = $this->usr->email_address;
						log_message('debug', 'User, '.$_SESSION["email"].' logged in.');

						//redirect
						redirect('home');
					}

					# 3 - else, email in use?
					if ($user_profile->email ) {
						
						log_message('debug', 'Email retrieved from provider: '.$user_profile->email);

						$user_info = $this->user_model->find_by_email( $user_profile->email );

						if( $user_info ) { // if duplicate email...

							log_message('debug', 'Email already exists in users table.');

							//if relevent 'verified' record exists in verify table
							// then connect accounts
							if ($this->admin_model->connect_accounts_ok($provider, $this->user_model->find_by_email($user_profile->email)->id)) {//check for permission when connecting accounts
									
									//prepare data for insertion
									$provider_uid  = $user_profile->identifier;
									$email         = $user_profile->email;
									$first_name    = $user_profile->firstName;
									$last_name     = $user_profile->lastName;
									$display_name  = $user_profile->displayName;
									$website_url   = $user_profile->webSiteURL;
									$profile_url   = $user_profile->profileURL;

									//connect accounts
									if ($this->admin_model->create_authentication( $this->user_model->find_by_email($user_profile->email)->id, $provider, $user_profile->identifier, $email, $display_name, $first_name, $last_name, $profile_url, $website_url ))
									{
										echo "<h1>Accounts Connected!</h1>";
										die();
									}
								echo "Accounts not connected";    
							}
							else
							{
								if (isset($_SESSION['email'])) {
									if ($_SESSION['email']==$user_profile->email) {

										$provider_uid  = $user_profile->identifier;
										$email         = $user_profile->email;
										$first_name    = $user_profile->firstName;
										$last_name     = $user_profile->lastName;
										$display_name  = $user_profile->displayName;
										$website_url   = $user_profile->webSiteURL;
										$profile_url   = $user_profile->profileURL;

										if ($this->admin_model->create_authentication( $this->user_model->find_by_email($user_profile->email)->id, $provider, $user_profile->identifier, $email, $display_name, $first_name, $last_name, $profile_url, $website_url ))
										{
											echo "<h1>Accounts Connected!</h1>";
											die();
										}
									}
								}

								//no permission found, verfiy by email
								//redirect('verify/send/connectAccounts/'.$provider);
							

							}
							
							die( '<br /><b style="color:red">Duplicate Email</b>');
						}
					

					# 4 - if authentication doesn't exist and email isn't in use, create a new account

					$provider_uid  = $user_profile->identifier;
					$email         = $user_profile->email;
					$first_name    = $user_profile->firstName;
					$last_name     = $user_profile->lastName;
					$display_name  = $user_profile->displayName;
					$website_url   = $user_profile->webSiteURL;
					$profile_url   = $user_profile->profileURL;
					$password      = rand( ) ; # for the password we generate something random

					// 4.1 - create new user
					$new_user_id = $this->user_model->create( $first_name, $last_name, $email, $password );

					// 4.2 - create a new authentication for new user
					$this->admin_model->create_authentication( $new_user_id, $provider, $user_profile->identifier, $email, $display_name, $first_name, $last_name, $profile_url, $website_url );

					// 4.3 - store the email in session
					$q = $this->db
							->where('id', $new_user_id)
							->limit(1)
							->get('users');

					$r = $q->row();

					$_SESSION['email'] = $r->email_address;

					// 4.4 - redirect home
					redirect('home');
					
					}
					log_message('error', 'An email address was not retrieved from the provider');
					die('We did not receive an email address from '.$provider.'.');
				}
				else // Cannot authenticate user
				{
					show_error('Cannot authenticate user');
				}
			}
			else // This service is not enabled.
			{
				log_message('error', 'controllers.HAuth.login: This provider is not enabled ('.$provider.')');
				show_404($_SERVER['REQUEST_URI']);
			}
		}
		catch(Exception $e)
		{
			$error = 'Unexpected error';
			switch($e->getCode())
			{
				case 0 : $error = 'Unspecified error.'; break;
				case 1 : $error = 'Hybriauth configuration error.'; break;
				case 2 : $error = 'Provider not properly configured.'; break;
				case 3 : $error = 'Unknown or disabled provider.'; break;
				case 4 : $error = 'Missing provider application credentials.'; break;
				case 5 : log_message('debug', 'controllers.HAuth.login: Authentification failed. The user has canceled the authentication or the provider refused the connection.');
				         //redirect();
				         if (isset($service))
				         {
				         	log_message('debug', 'controllers.HAuth.login: logging out from service.');
				         	$service->logout();
				         }
				         show_error('User has cancelled the authentication or the provider refused the connection.');
				         break;
				case 6 : $error = 'User profile request failed. Most likely the user is not connected to the provider and he should to authenticate again.';
				         break;
				case 7 : $error = 'User not connected to the provider.';
				         break;
			}

			if (isset($service))
			{
				$service->logout();
			}
		}
	}
}