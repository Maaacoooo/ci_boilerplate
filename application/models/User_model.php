<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class User_model extends CI_Model
{

     function check_user($user, $pass) {

             $this->db->select('*');        
             $this->db->where('username', $user);    
             $this->db->where('is_deleted', 0);
             $this->db->limit(1);

             $query = $this->db->get('users');

             if($query->num_rows() == 1)   { //checks if username exists

                $result = $query->row_array(); //fetch the row array

                if(password_verify($pass, $result['password'])) {
                    return $result;
                } else {
                    return FALSE;
                }
                
               
             }   else   {

                return FALSE;

             }

    }

    /**
     * Fetches the User Records
     * @param  String       $user     the username
     * @return String Array           the array of row 
     */
    function userdetails($user) {

             $this->db->select('
              users.username,
              users.name,
              users.email,
              users.contact,
              users.img,
              users.is_deleted,
              usertypes.user_level,
              usertypes.title as usertype
              ');        

             $this->db->where('users.username', $user);          
             $this->db->join('usertypes', 'usertypes.title = users.usertype', 'LEFT');

             $query = $this->db->get('users');

             return $query->row_array();
    }


    function create_user($username) {
      
            $data = array(              
                'username'  => $username,  
                'password'  => password_hash(DEFAULT_PASS, PASSWORD_DEFAULT),  //Default Password
                'name'      => strip_tags($this->input->post('name')),  
                'email'     => strip_tags($this->input->post('email')),  
                'contact'   => strip_tags($this->input->post('contact')),  
                'usertype'  => strip_tags($this->input->post('usertype'))                            
             );
       
            $create = $this->db->insert('users', $data);      

            //Process Image Upload
              if($_FILES['img']['name'] != NULL)  {        

                $filename = UploadFile('users/'.$username.'/', 'img');
                ImgCropper($filename);

                //Update row 
                $this->db->update('users', array('img' => $filename), array('username'=>$username));
            
            } 

            return $create;

    }

    function reset_password($user) {

        $data = array(            
                'password'  => password_hash(DEFAULT_PASS, PASSWORD_DEFAULT)  //Default Password
             );
            $this->db->where('username', $user);            
            
            return $this->db->update('users', $data);   
    }

    /**
     * Updates a user record
     * @param  int      $id    the DECODED id of the item. 
     * @return void            returns TRUE if success
     */
    function update_user($user) { 


            $filename = $this->userdetails($user)['img']; //gets the old data 

            //Remove Image
            if($this->input->post('remove_img')) {
                if (RemoveFile($filename)) {
                  $filename = null;
                }
            }

            //Process Image Upload
            if($_FILES['img']['name'] != NULL)  { 
                //Remove Existing
                RemoveFile($filename);
                //Upload file
                $filename = UploadFile('users/'.$user.'/', 'img');
                //Crop Image
                ImgCropper($filename);
            }
      
            $data = array(           
                'name'      => $this->input->post('name'),  
                'email'     => $this->input->post('email'),  
                'contact'   => $this->input->post('contact'),  
                'usertype'  => $this->input->post('usertype'),                                         
                'img'       => $filename   
             );
            
            $this->db->where('username', $user);
            return $this->db->update('users', $data);          
        
    }


        /**
     * Deletes a user record
     * @param  int    $id    the DECODED id of the item.   
     * @return boolean    returns TRUE if success
     */
    function delete_user($user) {

 
           $data = array(           
                'is_deleted'      => 1
             );
            
            $this->db->where('username', $user);
            return $this->db->update('users', $data);          

    }


    /**
     * Returns the paginated array of rows 
     * @param  int      $limit      The limit of the results; defined at the controller
     * @param  int      $id         the Page ID of the request. 
     * @return Array        The array of returned rows 
     */
    function fetch_users($limit, $id, $search, $is_deleted = 0) {

            if($search) {
                $this->db->group_start();
                $this->db->like('name', $search);
                $this->db->or_like('username', $search);
                $this->db->group_end();                
            }

            $this->db->where('is_deleted', $is_deleted);            
            $this->db->limit($limit, (($id-1)*$limit));

            $query = $this->db->get("users");

            if ($query->num_rows() > 0) {
                return $query->result_array();
            }
            return false;

    }

    /**
     * Returns the total number of rows of users
     * @return int       the total rows
     */
    function count_users($search, $is_deleted = 0) {
        if($search) {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('username', $search);
            $this->db->group_end(); 
        }
        $this->db->where('is_deleted', $is_deleted);
        return $this->db->count_all_results("users");
    }

    ////////////////////////////////
    /// HELPER                    //
    ////////////////////////////////
   

    function usertypes() {

            $this->db->select('*');
            $query = $this->db->get('usertypes');

            return $query->result_array();

    }


    function update_profile($user) { 

            $filename = $this->userdetails($user)['img']; //gets the old data 

            //Remove Image
            if($this->input->post('remove_img')) {
                if (RemoveFile($filename)) {
                  $filename = null;
                }
            }

            //Process Image Upload
            if($_FILES['img']['name'] != NULL)  { 
                //Remove Existing
                RemoveFile($filename);
                //Upload file
                $filename = UploadFile('users/'.$user.'/', 'img');
                //Crop Image
                ImgCropper($filename);
            }
      
            $data = array(           
                'name'      => $this->input->post('name'),    
                'email'     => $this->input->post('email'),  
                'contact'   => $this->input->post('contact'),              
                'img'       => $filename  
             );
            
            $this->db->where('username', $user);
            return $this->db->update('users', $data);          
        
    }

    function update_profile_pass($user) { 

           
      
            $data = array(           
                'password'  => password_hash($this->input->post('newpass'), PASSWORD_DEFAULT)          
             );
            
            $this->db->where('username', $user);
            return $this->db->update('users', $data);          
        
    }




    function getActivity($user) {
      $this->db->select('
        COUNT(id) as count,
        DATE(date_time) as date
        ');
      $this->db->where('user', $user);
      $this->db->group_by('DATE(date_time)');
      return $this->db->get('logs')->result_array();
    }



}