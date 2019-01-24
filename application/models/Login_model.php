<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model {
    
    //function for Login
    function login($email,$phone)
	{
        $this->db->where('email', $email);
    	$this->db->where('phone', $phone);
    	$query = $this->db->get('students_registration'); 
        $row=$query->row_array(); 
    	
    	//echo $this->db->last_query();die;
    	if($query->num_rows()>0)
		{           
			return $row;
		}
		else
		{
			return 0;
		}
	}
    
    
    
    
}
?>