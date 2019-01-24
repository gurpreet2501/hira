<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
	This Controller is for Landing page without Login Attendance Check
*/


class Web_service extends CI_Controller {


    public function __construct()
	{
        parent::__construct();
      	$this->load->model('login_model');    
		date_default_timezone_set('Asia/Kolkata');	
    }
    
    public function index()
	{
		$this->load->view('web_form.php');
	}
	
	public function login()
	{
	     $email=$this->input->get('email');
		 $phone=$this->input->get('phone'); 
		 
		 $res=$this->login_model->login($email,$phone);
		   
		    $id=$res['id'];
		    
		    $unique_id=$res['student_unique_code'];
		    if(!empty($res))
		     {
				echo json_encode(array('status'=>'true','message'=>'Login success','userid'=>$id,'unique_id'=>$unique_id));
	         }
		    else
		     {
				echo json_encode(array('status'=>'false','message'=>'Login unsuccess'));
		     }	
	}
    
    

    
    
    
}
?>    