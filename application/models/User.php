<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends CI_Model{
    function __construct() {
        $this->userTbl = 'ing_vendor_leads';
    }
    
    /*
     * Insert user information
     */
    public function insert($data = array()) {
        // //add created and modified data if not included
        // if(!array_key_exists("created", $data)){
        //     $data['created'] = date("Y-m-d H:i:s");
        // }
        // if(!array_key_exists("modified", $data)){
        //     $data['modified'] = date("Y-m-d H:i:s");
        // }
        
        //insert user data to users table
        $insert = $this->db->insert($this->userTbl, $data);
        
        //return the status
        if($insert){
            return $this->db->insert_id();;
        }else{
            return false;
        }
    }


        function getcity($state_id) {
        $this->db->from('ing_cities');
        $this->db->where('state_id =', $state_id);
        $query = $this->db->get();
        $data = $query->result();
          foreach ($data as $value) {
                
            $array[] = array (
            'label' => $value->name,
            'value' => $value->name,
        );
           return $array; }
    }

        function getcountry() {
            $this->db->from('ing_countries');
            $searchTerm = $this->input->get('term');
            $this->db->like('name', $searchTerm);         

            $query = $this->db->get();
            $result = $query->result();
            return $result;
           
        }

        function getstate($country_id) {
            $this->db->from('ing_states');
             $searchTerm = $this->input->get('term');
            $this->db->where('country_id =', $country_id);
            $this->db->like('name', $searchTerm);   
            $query = $this->db->get();
           $data = $query->result();
            foreach ($data as $value) {
                
            $array[] = array (
            'label' => $value->name,
            'value' => $value->name,
        );
           return $array; }
        }
}