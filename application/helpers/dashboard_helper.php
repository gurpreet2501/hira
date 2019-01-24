<?php 
function dashboard_data(){
	$data = [
		'total_students' => 0,
		'ielts_students' => 0,
		'pte_students' => 0,
		'fee_submitted_today' => 0,
		'_students'
	];

	$students = Models\StudentsRegistration::with('studentCourses')->where('added_by', user_id())->get();
	$data['_students'] = $students;
	
	if(!count($students))
		return $data;

	$data['ielts_students'] = calculate_ielts_students($students);
	$data['pte_students'] = calculate_pte_students($students);
	$data['total_students'] = $students->count();
	$data['fee_submitted_today'] = Models\FeesDetails::where('fee_submission_date','>=',date('Y-m-d 00:00:00'))
														->where('fee_submission_date','<=',date('Y-m-d 23:59:59'))
														->where('added_by',user_id())->sum('fees_amount');

	$data['feedbacks'] = Models\Feedback::with('student')->get();													

	return $data;

}

function calculate_ielts_students($students){

	$ielts_students = 0;
	foreach ($students as $key => $student) {
		foreach ($student->studentCourses as $key => $course) {
			if (strpos(strtolower($course), 'ielts') !== false)
				{
					$ielts_students++;
					break;
				} 
		}
	}

	return $ielts_students;
	
}

function calculate_pte_students($students){

	$pte_students = 0;
	foreach ($students as $key => $student) {
		foreach ($student->studentCourses as $key => $course) {
			if (strpos(strtolower($course), 'pte') !== false)
				{
					$pte_students++;
					break;
				} 
		}
	}
 
	return $pte_students;

}

function getEmojiUrl($name){
	
	if($name == 'PATHATIC')
		return base_url('/assets/images/pathatic.png');
	else if($name == 'SAD')
		return base_url('/assets/images/sad.png');
	else if($name == 'NORMAL')
		return base_url('/assets/images/ok.png');
	else if($name == 'HAPPY')
		return base_url('/assets/images/happy.png');
	
}


