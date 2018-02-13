<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct()	{
		parent::__construct();		
       $this->load->model('user_model');
	}	


	public function index()		{

		$userdata = $this->session->userdata('admin_logged_in'); //it's pretty clear it's a userdata

		if($userdata)	{

			$data['title'] = 'Dashboard';
			$data['site_title'] = APP_NAME;
			$data['user'] = $this->user_model->userdetails($userdata['username']); //fetches users record

			$data['passwordverify'] = $this->user_model->check_user($userdata['username'], 'Inventory2017'); //boolean - returns false if default password

		
			$this->load->view('blank', $data);					


		} else {

			$this->session->set_flashdata('error', 'You need to login!');
			redirect('dashboard/login', 'refresh');
		}

	}


	/**
	 * -------------------------------------------------------------------------------------------------------
	 * Login Functionality
	 */

	public function login()		{

		$data['title'] = 'Login';
		$data['site_title'] = APP_NAME;	


		if($this->session->userdata('admin_logged_in'))	{
		        redirect('dashboard', 'refresh');
		} else {
			
			//FORM VALIDATION
			$this->form_validation->set_rules('username', 'Username', 'trim|required|callback_check_user');   
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
			 
			   if($this->form_validation->run() == FALSE)	{

					$this->load->view('user/admin_login', $data);

				} else {
					//Sets user data
					$username = $this->input->post('username');
					$this->session->set_userdata('admin_logged_in', array('username' => $username)); //set userdata
					//Set logs
					$log[] = array(
							'user' 		=> 	$username,
							'tag' 		=> 	' ',
							'tag_id'	=> 	' ',
							'action' 	=> 	'User Logged In'
						);

					//sets a notification //////////////////////////////
					$notification['success'] = "Welcome back $username!";
					$this->sessnotif->setNotif($notification);
						
					//Save Logs/////////////////////////
					$this->logs_model->save_logs($log);		

					redirect('dashboard', 'refresh');
			}
				
		}	
	}

	public function check_user($username) {

		$result = $this->user_model->check_user($username, $this->input->post('password'));

		if($result) {	
			return TRUE;
		} else {
			$this->form_validation->set_message('check_user', 'Username or Password does not match!');
			return FALSE;
		}
	}

	/**
	 * ---------------------------------------------------------------------------------------------------------
	 */



	public function logout() {
		//sets a notification //////////////////////////////
		$notification['success'] = "You have successfully logged out!";
		$this->sessnotif->setNotif($notification);

		$this->session->unset_userdata('admin_logged_in');		  
		redirect('dashboard/login', 'refresh');
	}


	public function test()		{

		$userdata = $this->session->userdata('admin_logged_in'); //it's pretty clear it's a userdata

		if($userdata)	{

			$data['title'] = 'Dashboard';
			$data['site_title'] = APP_NAME;
			$data['user'] = $this->user_model->userdetails($userdata['username']); //fetches users record

			$nope['success'] = "HAHAHAHAHHAHAHA PUTA EJdklsajdklasjdkljakldj";
			//$nope['success'] = "HAHAHAHAHHAHAHA PUTA EJdklsajdklasjdkljakldj";
			$this->sessnotif->setNotif($nope);
			//$this->sessnotif->setNotif($nope);
			//$this->sessnotif->setNotif($nope);
			//$this->sessnotif->setNotif($nope);
			//$this->sessnotif->setNotif($nope);

			$wow['warning'] = "Lorem ipsum dolor sit amet.";
			$this->sessnotif->setNotif($wow);
			//$this->sessnotif->setNotif($wow);

			$peste['error'] = "HAHAHAHHAHAHA";		
			$this->sessnotif->setNotif($peste);				
			//$this->sessnotif->setNotif($peste);				
			$peste2['error'] = "Lorem Perspiciatis neque distinctio tempora eveniet iure, vitae ipsum rem totam accusantium ullam, natus veritatis eius earum?";
			$this->sessnotif->setNotif($peste2);

			 

			var_dump($this->session->flashdata());

			
			$this->load->view('blank', $data);					


		} else {

			$this->session->set_flashdata('error', 'You need to login!');
			redirect('dashboard/login', 'refresh');
		}

	}


}
