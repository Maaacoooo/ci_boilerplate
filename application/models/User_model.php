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
                'password'  => password_hash('Inventory2017', PASSWORD_DEFAULT),  //Default Password
                'name'      => strip_tags($this->input->post('name')),  
                'email'     => strip_tags($this->input->post('email')),  
                'contact'   => strip_tags($this->input->post('contact')),  
                'usertype'  => strip_tags($this->input->post('usertype'))                            
             );
       
            $create = $this->db->insert('users', $data);      

            //Process Image Upload
              if($_FILES['img']['name'] != NULL)  {        

                $path = checkDir('./uploads/users/'.$this->input->post('username').'/'); //the path to upload

                $config['upload_path'] = $path;
                $config['allowed_types'] = 'gif|jpg|png'; 
                $config['encrypt_name'] = TRUE;                        

                $this->load->library('upload', $config);
                $this->upload->initialize($config);         
                
                $field_name = "img";
                $this->upload->do_upload($field_name);

                $upload_data = $this->upload->data();

                $filepath = $path . $upload_data['file_name'];

                // Set Watermark ////////////////////////////////////////////////////
                $wm_config['quality'] = '100%';
                $wm_config['wm_text'] = 'Copyright '.APP_NAME.' '.date('Y');
                $wm_config['wm_type'] = 'text';
                $wm_config['wm_font_path'] = './system/fonts/arial.ttf';
                $wm_config['wm_font_size'] = '16';
                $wm_config['wm_font_color'] = 'ffffff';
                $wm_config['wm_vrt_alignment'] = 'bottom';
                $wm_config['wm_hor_alignment'] = 'left';
                $wm_config['source_image'] = $filepath; 
                /////////////////////////////////////////////////////////////////////

                //Update row 
                $this->db->update('users', array('img' => $filepath), array('username'=>$username));
            
            } 

            return $create;

    }

    function reset_password($user) {

        $data = array(            
                'password'  => password_hash('Inventory2017', PASSWORD_DEFAULT)  //Default Password
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

            $filepath = $this->userdetails($user)['img']; //gets the old data 

            //Remove Image
            if($this->input->post('remove_img')) {
                if(filexist($filepath)) {
                  unlink($filepath); //removes the file
                }
                $filepath = ''; //set to null
            }

            //Process Image Upload
              if($_FILES['img']['name'] != NULL)  { 

                //remove old img
                if(filexist($filepath)) {
                  unlink($filepath); //removes the file
                } 

                $path = checkDir('./uploads/users/'.$user.'/'); //the path to upload

                $config['upload_path'] = $path;
                $config['allowed_types'] = 'gif|jpg|png'; 
                $config['encrypt_name'] = TRUE;                        

                $this->load->library('upload', $config);
                $this->upload->initialize($config);         
                
                $field_name = "img";
                $this->upload->do_upload($field_name);

                $upload_data = $this->upload->data();

                $filepath = $path . $upload_data['file_name']; //overwrite variable

                 // Set Watermark ////////////////////////////////////////////////////
                $wm_config['quality'] = '100%';
                $wm_config['wm_text'] = 'Copyright '.APP_NAME.' '.date('Y');
                $wm_config['wm_type'] = 'text';
                $wm_config['wm_font_path'] = './system/fonts/arial.ttf';
                $wm_config['wm_font_size'] = '16';
                $wm_config['wm_font_color'] = 'ffffff';
                $wm_config['wm_vrt_alignment'] = 'bottom';
                $wm_config['wm_hor_alignment'] = 'left';
                $wm_config['source_image'] = $filepath; 
                
            }
      
            $data = array(           
                'name'      => $this->input->post('name'),  
                'email'     => $this->input->post('email'),  
                'contact'   => $this->input->post('contact'),  
                'usertype'  => $this->input->post('usertype'),                                         
                'img'       => $filepath   
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
                $this->db->like('name', $search);
                $this->db->or_like('username', $search);
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
            $this->db->like('name', $search);
            $this->db->or_like('username', $search);
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
                if(filexist($filename)) {
                  unlink($filename); //removes the file
                }
                $filename = ''; //set to null
            }


            //Process Image Upload
              if($_FILES['img']['name'] != NULL)  { 

                //Deletes the old photo
                if(filexist($filename)) {
                  unlink($filename); 
                }

                $path = checkDir('./uploads/users/'.$user.'/'); //the path to upload

                $config['upload_path'] = $path;
                $config['allowed_types'] = 'gif|jpg|png'; 
                $config['encrypt_name'] = TRUE;                        

                $this->load->library('upload', $config);
                $this->upload->initialize($config);         
                
                $field_name = "img";
                $this->upload->do_upload($field_name);

                $upload_data = $this->upload->data();

                $filename = $path . $upload_data['file_name'];

                // Set Watermark ////////////////////////////////////////////////////
                $wm_config['quality'] = '100%';
                $wm_config['wm_text'] = 'Copyright '.APP_NAME.' '.date('Y');
                $wm_config['wm_type'] = 'text';
                $wm_config['wm_font_path'] = './system/fonts/arial.ttf';
                $wm_config['wm_font_size'] = '16';
                $wm_config['wm_font_color'] = 'ffffff';
                $wm_config['wm_vrt_alignment'] = 'bottom';
                $wm_config['wm_hor_alignment'] = 'left';
                $wm_config['source_image'] = $filename; 
                /////////////////////////////////////////////////////////////////////

                
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



}