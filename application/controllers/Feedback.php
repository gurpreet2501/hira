<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
use App\Libs\ApiClient;
use App\Libs\MobileSms as Sms;

class Feedback extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		// $this->load->library('tank_auth');
	}

	function form($added_by,$student_id){
		
		$this->load->view('feedback/form',[
			'added_by' => $added_by,
			'student_id' => $student_id
		]);

	}

	function form_post(){
		

		if(empty($_POST['student_id']))
			return 404;
		
		$exists = Models\Feedback::where('student_id', $_POST['student_id'])->count();

		if($exists)
		{
			failure('Feedback Already Submitted');
			return redirect('feedback/form/'.$_POST['student_id']);
		}

		Models\Feedback::create($_POST);

		success('Feedback Submitted successfully');
		return redirect('feedback/form/'.$_POST['added_by'].'/'.$_POST['student_id']);

	}
	
	function send_link($added_by,$student_id){
		
	  $student = Models\StudentsRegistration::where('id', $student_id)->first();
	  
	  if(!$student->phone){
	  		failure('Student phone no missing. Phone is required to send feedback link');
	  		redirect('student/add_student');
				
			}	

		$message = "Click url for feedback: ";
		$message .= site_url('feedback/form/'.user_id().'/'.$student_id);	
		
		Sms::send($student->phone,$message);
		success('Feedback link sent successfully on registered mobile no');
  	redirect('student/add_student');

	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
