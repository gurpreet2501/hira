<?php

function clubFeeTransactions($fee_details){
	$organised_data = [];	
	foreach ($fee_details as $key => $detail) {
	
		$organised_data[$detail['course_details']['course_name']][] = $detail;

	}
	return $organised_data;
}

function findTotalCourseFeeSubmitted($student_id,$course_id){
	return Models\FeesDetails::where('students_registration_id',$student_id)
										  ->where('course_id',$course_id)->sum('fees_amount');
										
}

function findPendingFees($courses,$transactions){
	$fees_details = [];

	  foreach ($courses as $key => $course) {
		 
				  $total_trx_amount = 0; 

				  
					if(!isset($transactions[$course->courseDetails->course_name])){
						continue;
					}


					foreach ($transactions[$course->courseDetails->course_name] as $key => $trx)
						$total_trx_amount = $total_trx_amount + $trx['fees_amount'];
						
					$fees_details[$key]['course_name']  = $course->courseDetails->course_name; 
					$fees_details[$key]['fees_submitted']  = $total_trx_amount; 
					$fees_details[$key]['total_fees']  = $course->courseDetails->course_fees; 
					$fees_details[$key]['fees_pending'] = $course->courseDetails->course_fees - $total_trx_amount;

				}
		

	return $fees_details;
}


function isFeesPending($data){ 
	
	$is_trx_more_than_pending = false;
	$student_id = $data['student_id'];
	$current_trx = 0;
	$paid_flag = true;
	$courses = Models\StudentsRegistrationCourses::where('students_registration_id',$data['student_id'])->get();
	$pending_fees = 0;
	foreach ($courses as $key => $course) {
		$course_details = $course->courseDetails;
		$total_submitted_fees = findTotalCourseFeeSubmitted($student_id,$course_details->id);

		if($course_details->course_fees>$total_submitted_fees)
			$paid_flag = false;
    
    $total_submitted_fees = $total_submitted_fees + $data['fees_amount']; 
		$pending_fees = $course_details->course_fees - $total_submitted_fees; 
		if($course_details->course_fees < $total_submitted_fees)
			$is_trx_more_than_pending = true; 

		return [
			'is_pending' => $paid_flag,
			'trx_more_than_pending' => $is_trx_more_than_pending,
			'pending_fees' => $pending_fees,
		];
	
	}

	return $paid_flag;
}



function _t($data){
	return htmlspecialchars(htmlentities($data));
}