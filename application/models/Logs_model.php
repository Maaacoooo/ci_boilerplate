<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Logs Model
 *
 * This is a general log model. 
 * This should be autoloaded.
 */
Class Logs_model extends CI_Model
{

    /**
     * Inserting a Log
     * This only inserts one row
     * - This will be SOON DEPRECATED and only used for backward versions purposes
     * @param  String       $user       The user
     * @param  String       $tag        The table of module
     * @param  String       $tag_id     The row ID of record
     * @param  String       $action     The action of the User
     * @return Boolean                  returns TRUE if success
     */
    function create_log($user, $tag, $tag_id, $action) {

      
            $data = array(              
                'user'        => $user,  
                'tag'         => $tag,  
                'tag_id'      => $tag_id,                 
                'action'      => $action,
                'ip_address'  => $_SERVER['REMOTE_ADDR']                      
             );
       
            return $this->db->insert('logs', $data);      

    }

    /**
     * New Logging function - More convenient that create_log()
     * Inserts multiple rows
     * @param  Multidimensional Array    $log    a set of array of log data
     * @return Boolean      [description]
     */
    function save_logs($logs) {
        
        foreach ($logs as $log) {
            $data['user'] = $log['user'];
            $data['tag'] = $log['tag'];
            $data['tag_id'] = $log['tag_id'];
            $data['action'] = $log['action'];
            $data['ip_address'] = $_SERVER['REMOTE_ADDR'] ;
            $data['user_agent'] = $this->input->user_agent();

            $data_arr[] = $data; //build data
        } 

        return $this->db->insert_batch('logs', $data_arr);
    }


    /**
     * Fetching Logs by Request
     * @param  String           $tag        The table of request / module
     * @param  String           $tag_id     The row ID of record
     * @param  String           $limit      The Limit of Request
     * @return String Array                 The result array 
     */
    function fetch_logs($tag, $tag_id, $limit) {

            if($limit) {
                $this->db->limit($limit);
            }

            $this->db->where('tag', $tag);
            $this->db->where('tag_id', $tag_id);
            $this->db->order_by('date_time', 'DESC');

            $query = $this->db->get("logs");

            return $query->result_array();
          
    }


    function fetch_user_logs($user, $limit) {
            $this->db->select('
            id,
            user,
            tag,
            tag_id,
            action,
            ip_address,
            date_time');
            if($limit) {
                $this->db->limit($limit);
            }

            if(!is_null($user)){
                $this->db->where('user', $user);;
            }
            $this->db->order_by('date_time', 'DESC');

            $query = $this->db->get("logs");

            return $query->result_array();
          
    }




}