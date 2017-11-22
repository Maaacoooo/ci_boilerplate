<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	public function __construct()	{
		parent::__construct();		
       $this->load->model('user_model');
	}	



	public function index()		{

		$userdata = $this->session->userdata('admin_logged_in'); //it's pretty clear it's a userdata

		if($userdata)	{

			$data['title'] 		= 'Users';
			$data['site_title'] = APP_NAME;
			$data['user'] 		= $this->user_model->userdetails($userdata['username']); //fetches users record


			//Page Data 
			$data['usertypes']	= $this->user_model->usertypes();

			//Search
			$search = '';
			if(isset($_GET['search'])) {
				$search = $_GET['search'];
			}

			//Paginated data - Candidate Names				            
	   		$config['num_links'] = 5;
			$config['base_url'] = base_url('/users/index/');
			$config["total_rows"] = $this->user_model->count_users($search);
			$config['per_page'] = 20;				
			$this->load->config('pagination'); //LOAD PAGINATION CONFIG

			$this->pagination->initialize($config);
		    if($this->uri->segment(3)){
		       $page = ($this->uri->segment(3)) ;
		  	}	else 	{
		       $page = 1;		               
		    }

		    $data["results"] = $this->user_model->fetch_users($config["per_page"], $page, $search);
		    $str_links = $this->pagination->create_links();
		    $data["links"] = explode('&nbsp;',$str_links );

		    //ITEM NUMBERING
		    $data['per_page'] = $config['per_page'];
		    $data['page'] = $page;

		    //GET TOTAL RESULT
		    $data['total_result'] = $config["total_rows"];
		    //END PAGINATION		
		
			//Form Validation for user
			$this->form_validation->set_rules('name', 'Name', 'trim|required'); 
			$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email'); 
			$this->form_validation->set_rules('contact', 'Contact Number', 'trim'); 
			$this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[users.username]|alpha_dash'); 
			$this->form_validation->set_rules('usertype', 'Usertype', 'trim|required'); 			
			
			//Validate Usertype
			if($data['user']['user_level'] >= 10) {

				if($this->form_validation->run() == FALSE)	{
					$this->load->view('user/list', $data);
				} else {	

					$username = strip_tags($this->input->post('username')); //the username

					//Proceed saving user				
					if($this->user_model->create_user($username)) {			

						
						// Save Log Data ///////////////////				

						$log[] = array(
							'user' 		=> 	$userdata['username'],
							'tag' 		=> 	'user',
							'tag_id'	=> 	$username,
							'action' 	=> 	'User Registration'
							);

				
						//Save Logs/////////////////////////
						$this->logs_model->save_logs($log);		
					
						$this->session->set_flashdata('success', 'User registered!');
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					} else {
						//failure
						$this->session->set_flashdata('error', 'Error occured!');
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					}		
				}
			} else {
				show_error('Oops! Your account does not have the privilege to view the content. Please Contact the Administrator', 403, 'Access Denied!');
			}

		} else {

			$this->session->set_flashdata('error', 'You need to login!');
			redirect('dashboard/login', 'refresh');
		}

	}

	public function update($id)		{

		$userdata = $this->session->userdata('admin_logged_in'); //it's pretty clear it's a userdata

		if($userdata)	{

			$data['site_title'] = APP_NAME;
			$data['user'] = $this->user_model->userdetails($userdata['username']); //fetches users record

			//Page Data 
			$data['usertypes']		= $this->user_model->usertypes();			

			$data['info']		= $this->user_model->userdetails($id);
			$data['logs']		= $this->logs_model->fetch_user_logs($id, 50);
			$data['title'] 		= $data['info']['name'];


			//Download Log Data 
			if ($this->uri->segment(4) == 'download_logs' && $data['user']['user_level'] >= 10) {

				$this->load->library('magictable'); //load magictable library

				$log_data = $this->logs_model->fetch_user_logs($id, NULL);

		        foreach ($log_data as $lg) {
		        	$logs[] = (explode(';', (implode(";", $lg))));
		        }

		        $upload_path = checkDir('./uploads/logs/'.$id.'/');
		        $file_path = $upload_path.$id.'_'.date('Y-m-d_H-i-s').'.log';

		        write_file($file_path, $this->magictable->maketable(array('ID', 'USERNAME', 'TAG', 'TAG_ID', 'ACTION', 'IP ADDRESS', 'DATE | TIME'),$logs));
		        force_download($file_path, NULL);
			}

			//Validate if record exist
			 //IF NO ID OR NO RESULT, REDIRECT
				if(!$id || !$data['info'] || $data['info']['is_deleted']) {
					redirect('users', 'refresh');
			}	

			//Form Validation for user
			$this->form_validation->set_rules('name', 'Name', 'trim|required');  
			$this->form_validation->set_rules('usertype', 'Usertype', 'trim|required'); 
			$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email'); 
			$this->form_validation->set_rules('contact', 'Contact Number', 'trim');  
			$this->form_validation->set_rules('usertype', 'Usertype', 'trim|required'); 
		
			//Validate Usertype
			if($data['user']['user_level'] >= 10) {
				if($this->form_validation->run() == FALSE)	{
				$this->load->view('user/update', $data);
				} else {			

					$location = NULL;
					if($this->input->post('location')) {
						$location = $this->input->post('location');
					}
					//Proceed saving candidate				
					$key_id = $this->encryption->decrypt($this->input->post('id')); //ID of the row
					if($this->user_model->update_user($key_id, $location)) {		

						$log[] = array(
							'user' 		=> 	$userdata['username'],
							'tag' 		=> 	'user',
							'tag_id'	=> 	$key_id,
							'action' 	=> 	'Updated User Information'
							);

				
						//Save Logs/////////////////////////
						$this->logs_model->save_logs($log);		
						
					
						$this->session->set_flashdata('success', 'Succes! User Updated!');
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					} else {
						//failure
						$this->session->set_flashdata('error', 'Oops! Error occured!');
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					}			
					
				}	
			} else {
				show_error('Oops! Your account does not have the privilege to view the content. Please Contact the Administrator', 403, 'Access Denied!');				
			}		

		} else {

			$this->session->set_flashdata('error', 'You need to login!');
			redirect('dashboard/login', 'refresh');
		}

	}


	public function delete()		{

		$userdata = $this->session->userdata('admin_logged_in'); //it's pretty clear it's a userdata

		if($userdata)	{
			
			//FORM VALIDATION
			$this->form_validation->set_rules('id', 'ID', 'trim|required');   
		 
		   if($this->form_validation->run() == FALSE)	{

				$this->session->set_flashdata('error', 'An Error has Occured!');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');

			} else {

				$key_id = $this->encryption->decrypt($this->input->post('id')); //ID of the row				

				if($this->user_model->delete_user($key_id)) {

					$log[] = array(
							'user' 		=> 	$userdata['username'],
							'tag' 		=> 	'user',
							'tag_id'	=> 	$key_id,
							'action' 	=> 	'Deleted User'
							);

				
					//Save Logs/////////////////////////
					$this->logs_model->save_logs($log);		

					$this->session->set_flashdata('success', 'User Deleted!');
					redirect('users', 'refresh');
				}
			}

		} else {

			$this->session->set_flashdata('error', 'You need to login!');
			redirect('dashboard/login', 'refresh');
		}

	}


	public function resetpassword()		{

		$userdata = $this->session->userdata('admin_logged_in'); //it's pretty clear it's a userdata

		if($userdata)	{
			
			//FORM VALIDATION
			$this->form_validation->set_rules('id', 'ID', 'trim|required');   
		 
		   if($this->form_validation->run() == FALSE)	{

				$this->session->set_flashdata('error', 'An Error has Occured!');
				redirect($_SERVER['HTTP_REFERER'], 'refresh');

			} else {

				$key_id = $this->encryption->decrypt($this->input->post('id')); //ID of the row				

				if($this->user_model->reset_password($key_id)) {

					$log[] = array(
							'user' 		=> 	$userdata['username'],
							'tag' 		=> 	'user',
							'tag_id'	=> 	$key_id,
							'action' 	=> 	'Resetted Password to Default'
							);

				
					//Save Logs/////////////////////////
					$this->logs_model->save_logs($log);		

					$this->session->set_flashdata('success', 'Password Resetted to Default!');
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
				}
			}

		} else {

			$this->session->set_flashdata('error', 'You need to login!');
			redirect('dashboard/login', 'refresh');
		}

	}



}
