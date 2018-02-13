<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * SessNotif - Session Notification Library 
 *
 * This is a bastardized notification library utilizing flashdata(); of CI's native session library 
 * This is purely inspired by laziness, and to have a cleaner-looking view
 *
 * IMPORTANT NOTES
 * as of this version of this library, showNotif() greatly works with AdminLTE 2.4.0 / Bootstrap 3.x
 * if you are in an different CSS framework, you are free to modify 
 *
 * DEFAULT NOTIFICATION TAGS - this is basically an array index
 * - success
 * - error
 * - warning
 *
 * USAGE 
 *  1. It is much better to autoload this library, but you can initiate this like a common CI Library. Duh
 *  2. Loading Notification Messages
 *  		When loading notification, you should identify its notifcation tag
 *  	e.g 
 *  	
 *  	//Single Message Notification   //////////	
 *  	
 * 		$notif['success'] = "Your message"; //set your message
 * 		$this->SessNotif->setNotif($notif); //sets the notification   	
 * 
 * 
 * 		//Multiple Message Notification ////////
 * 		
 * 		$notif['success'][] = "Your message one"; //set your message
 * 		$notif['success'][] = "Your message two"; //set your message
 *   	$this->SessNotif->setNotif($notif); //sets the notification   	
 *   	
 * 		or you can also do this...
 *
 * 		$notif['success'] = array("message one", "message two"); //set your message	
 * 		$this->SessNotif->setNotif($notif); //sets the notification   	
 *
 * 3. Fetching Notification - Well, you can actually skip this. But if you want to use this, yes, you can. duh
 * 	  simply call 
 * 	  		$this->SessNotif->getNotifMessages($tag); //$tag = notification tag
 * 	  		
 *    this will return an array of messages of a specific tag
 *    
 * 4. Showing the notification
 * 	  Inspired by great laziness, you can directly call this in the view. This will automatically create a notification panel thingy showing all notification messages.
 *    This also WORKS with FORM VALIDATION ERRORS
 * 		
 *    e.g 
 *    <?=$this->SessNotif->showNotif();?>
 * 
 * 
 * 
 */
class SessNotif 
{
	protected $CI;
	
	function __construct()
	{
		$this->CI =& get_instance();
	}

	/**
	 * Sets a notification data
	 * @param Array 	$notif 		an array of messages with specified index
	 */
	function setNotif($notif) {

		$existing_sess = $this->CI->session->flashdata();

		//check for existing data
		if ($existing_sess) {

			//loop old data
			foreach ($existing_sess as $key => $value) {
				if (is_array($value)) {
					for ($i=0; $i < sizeof($value); $i++) { 
						$new_data[$key][] = $value[$i];
					}
				} else {
					//check if there is a new data passed with a distinct key
					if(isset($notif[$key])) {
						$new_data[$key][] = $value; 
					} else {
						$new_data[$key] = $value;
					} 				
					
				}
			}
			//loop new data 
			foreach ($notif as $key => $value) {
				if (is_array($value)) {
					for ($i=0; $i < sizeof($value); $i++) { 
						$new_data[$key][] = $value[$i];
					}
				} else {
					//check if there's an existing data in a distinct key
					if($this->getNotifMessages($key)) {
						$new_data[$key][] = $value;
					} else {
						$new_data[$key] = $value;
					}
				}
			}

			//loop new values and rebuild the flashdata
			$this->CI->session->set_flashdata($new_data);

		} else {
			//Initialise the very first flashdata
			foreach ($notif as $key => $value) {
				$this->CI->session->set_flashdata($key, $value);
			}
		}

	}

	/**
	 * Returns the messages of a specic 
	 * @param  String 		$tag 	the tag of the notification / index of the notification array
	 * @return  Array      			Array of messages
	 */
	function getNotifMessages($tag) {
		return $this->CI->session->flashdata($tag);
	}

	/**
	 * Returns an HTML-formatted Notification Messages
	 * @return String 	an HTML-formatted string
	 */
	function showNotif() {

		$show = ''; //initialize variable

		foreach ($this->CI->session->flashdata() as $key => $value) {
			switch ($key) {
				case ('error'):
					$show .= '<div class="alert alert-danger alert-dismissible">' . "\n";
					$show .= "\t" . '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' . "\n";
					$show .= "\t" . '<h4><i class="icon fa fa-ban"></i> Oops!</h4>' . "\n";
					//checks if it is an array
					if(is_array($value)) {
						$show .= "\t" . '<ul>' . "\n";
						for($x=0;$x<sizeof($value);$x++) {
							$show .= "\t\t" . '<li>'.$value[$x].'</li>' . "\n";
						}
						$show .= "\t" . '</ul>' . "\n";
					} else {
						$show .= "\t<p>". $value . "</p>\n";
					}
					
					$show .= '</div>';
					break;

				case 'success':
					$show .= '<div class="alert alert-success alert-dismissible">' . "\n";
					$show .= "\t" . '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' . "\n";
					$show .= "\t" . '<h4><i class="icon fa fa-check"></i> Success!</h4>' . "\n";
					//checks if it is an array
					if(is_array($value)) {
						$show .= "\t" . '<ul>' . "\n";
						for($x=0;$x<sizeof($value);$x++) {
							$show .= "\t\t" . '<li>'.$value[$x].'</li>' . "\n";
						}
						$show .= "\t" . '</ul>' . "\n";
					} else {
						$show .= "\t<p>". $value . "</p>\n";
					}
					$show .= '</div>';
					break;

				case 'warning':
					$show .= '<div class="alert alert-warning alert-dismissible">' . "\n";
					$show .= "\t" . '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' . "\n";
					$show .= "\t" . '<h4><i class="icon fa fa-ban"></i> Oops!</h4>' . "\n";
					//checks if it is an array
					if(is_array($value)) {
						$show .= "\t" . '<ul>' . "\n";
						for($x=0;$x<sizeof($value);$x++) {
							$show .= "\t\t" . '<li>'.$value[$x].'</li>' . "\n";
						}
						$show .= "\t" . '</ul>' . "\n";
					} else {
						$show .= "\t<p>". $value . "</p>\n";
					}
					$show .= '</div>';
					break;
				
			} 
			
			//Destroy the Flashdata 
			$this->CI->session->unmark_flash($key);

		}
	   /**
		* Shows the Form Validation Errors
	 	* For the sake of cleaner-looking view		 
	 	*/
		
		if(validation_errors()) {

			 $this->CI->form_validation->set_error_delimiters('<li>', '</li>');
	         $show .= '<div class="alert alert-warning alert-dismissible">';
	         $show .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
	         $show .= '<h4><i class="icon fa fa-warning"></i> Warning!</h4>';
	         $show .= validation_errors();
	         $show .= '</div>';         
	            
			 
		}

		return $show;
	}


}