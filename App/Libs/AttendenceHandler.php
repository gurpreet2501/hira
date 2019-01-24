<?php
namespace App\Libs;
use App\Response\Factory as RF;
use Models as M;

class AttendenceHandler{
 
 function __construct(){
 }
 
function isMachineValid($data){

	if(empty($data['machine_serial']))
	   return RF::error('Machine serial is required');
	 
	 $resp =  M\Machines::where('machine_serial',trim($data['machine_serial']))
	 						 ->where('disabled',0)
	 						 ->count();
	 					
	if(!$resp)
	 		return RF::error('Either machine is disabled or serial incorrect');	

	return RF::success();
	 							 

}

function isCardValid($data){

	if(empty($data['card_serial']))
	   return RF::error('Card Serial is required');
	 
	 $resp =  M\Cards::where('card_serial',trim($data['card_serial']))
	 						 ->where('blocked',0)
	 						 ->count();
			 
	 	if(!$resp)
	  		return RF::error('Either card is missing or blocked');	

	 	return RF::success();
	 							 
}


 
function ifDataValid($data){

		$resp = $this->isMachineValid($data);
		
		if($resp->failed())
			return $resp->errorsArray();
		
		$resp = $this->isCardValid($data);
		
		if($resp->failed())
			return $resp->errorsArray();

		return $this->getAssignedCardDetails($data['card_serial']);

} 

function getAssignedCardDetails($card_serial){
	$card = M\Cards::with('student.studentCourses')->where('card_serial',trim($card_serial))->first();

	if(!count($card->student->studentCourses))
			return RF::error('Student is not assigned to any course');	

	$course_id = $this->findCurrentCourseAccToCurrentTime($card->student->studentCourses);

	if(empty($course_id))
			return RF::error('Unable to mark attendence. Server Error occured');	

	return $this->markAttendence($card->student_id,$course_id,$card->id, $card->machine_id);


}

function markAttendence($student_id,$course_id,$card_id=null,$machine_id=null){

	$student = M\StudentsRegistration::where('id',$student_id)->first();
	
	$count = M\StudentsAttendence::where('course_id', $course_id)
										  ->where('added_by', $student->added_by)
										  ->where('student_id', $student_id)
										  ->where('created_at','>=', date('Y-m-d 00:00:00'))
										  ->where('created_at','<=', date('Y-m-d 23:59:59'));
						
						if($machine_id && $card_id) {

						    $count->where('machine_id', $machine_id)
										  ->where('card_id', $card_id);
						}

	$count = $count->count();					
	

	// Enabled

	if($count && env('environment','PRODUCTION') != 'DEVELOPMENT')
		return false;
 	
	$attendence_data = [
		'course_id' => $course_id,
		'student_id' => $student_id,
		'added_by' => $student->added_by,
		'attendence_time' => date('Y-m-d H:i:s')
	];

 	if($machine_id && $card_id) {
 			$attendence_data['machine_id'] = $machine_id;
 			$attendence_data['card_id'] = $card_id;
 	}

   M\StudentsAttendence::create($attendence_data);

	return  true;


}

function findCurrentCourseAccToCurrentTime($courses){
	foreach ($courses as $key => $course) {
		
		$current_time = date('H:i:s');

		if($course->start_time <  $current_time &&  $course->end_time >  $current_time){
			return $course->id;
		}
			
	}
	return 0;

}

function processing($data){
	return $this->ifDataValid($data);	
} 

	function verifyCourse($stu_id,$course_id){
		
		return M\StudentsRegistrationCourses::where('students_registration_id',$stu_id)->where('course_id',$course_id)->count();
	}

	function verifyStudent($student_id,$spot_id){
		return M\StudentsRegistration::where('id',$student_id)->where('added_by',$spot_id)->count();
	}

}