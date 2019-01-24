<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header('Content-Type: application/json');
use App\Libs\QrCode;
use App\Libs\AttendenceHandler;
/*
	This Controller is for Landing page without Login Attendance Check
*/

class Api extends CI_Controller {

    public function __construct()
  	{
        parent::__construct();
    }
		
    function validator($keys,$data){
      $v = new Valitron\Validator($data);
      $v->rule('required',$keys);
      return $v;
    }

    function login(){
     
      $keys = ['email','password'];
      
      $validObj = $this->validator($keys, $_POST);

      if(!$validObj->validate())
        return apifailure('Validation Error',[], $validObj->errors());
    
    	if(empty($_POST['email']) || empty($_POST['password']))
    		return apiFailure('Email and password both are required'); 	
  		 
  		$record = Models\StudentsRegistration::where('email',$_POST['email'])->first(); 

  		if(!$record)
    		return apiFailure('Either email or password is incorrect');

  		if($record->registration_confirmation != 'CONFIRMED')
    		return apiFailure('Your account is not activated. Please wait or contact the concerned person');

    	if(!password_verify(trim(_t($_POST['password'])), $record->password))	 	
    		return apiFailure('Either email or password is incorrect');

   		return apiSuccess('Login Successful', $record->toArray());

  	
   } 

   function mark_attendence(){
       
      $keys = ['student_id','spot_id','date'];
      
      $validObj = $this->validator($keys, $_POST);

      if(!$validObj->validate())
        return apifailure('Validation Error',[], $validObj->errors());

      $stu_id = $_POST['student_id'];
      $date = $_POST['date'];

      if($date!=date('Y-m-d'))
      	return apifailure('Please use the updated QR code to mark attendance');

      $spot_id = $_POST['spot_id'];
      $courses = Models\Courses::get();
      $obj = new AttendenceHandler;
      $course_id = $obj->findCurrentCourseAccToCurrentTime($courses);

      
			if(!$course_id)
				 return apifailure('No course is active at this time');
			
			$course_verified = $obj->verifyCourse($stu_id,$course_id);	
			
			if(!$course_verified)
				return apifailure('No course is currently active at this time from your opted list of courses');
			
			$verified = $obj->verifyStudent($stu_id,$spot_id);			

			if(!$verified)
				return apifailure('You are not authorized to do this operation');

      $resp = $obj->markAttendence($stu_id,$course_id);
      // Institute ID
      if($resp)
      	return apiSuccess('Attendance marked successfully');
      
      return apifailure('Unable to mark attendance or you have already marked your attendance');	

   }

   public function get_machine_status(){
   	 
   }
    
}
