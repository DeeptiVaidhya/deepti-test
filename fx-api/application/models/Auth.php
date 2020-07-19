<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Model{


	public function insert($data,$table){      
	    $this->db->set($data);
	      $insertData = $this->db->insert($table);
	    if($insertData){
	        return $this->db->insert_id();
	    }else{
	        return FALSE;
	    }
    }

    public function singleRowdata($where_data,$table){  
	    $this->db->where($where_data);
	    $query = $this->db->get($table);
	    return $query->row();
  	}

	public function update($table,$data,$where_data){
	    $this->db->where($where_data);
	    $insertData=$this->db->update($table,$data);
	    if($insertData){
	      return TRUE;
	    }else{
	      return FALSE;
	    }
	}



}