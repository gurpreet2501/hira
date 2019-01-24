<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
use App\Libs\ApiClient;
use App\Libs\MobileSms;

class Fees extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		auth_force();
		$this->load->helper('url');
		$this->load->library('tank_auth');
	}


	public function _example_output($output = null)
	{
		$this->load->view('crud.php',(array)$output);
	}

	public function payment_selection(){
			$students = Models\StudentsRegistration::where('added_by',user_id())->get();
			$this->load->view('fees/payment_selection',[
				'students' => $students
			]);
	}
 	
 	public function payment_post(){
		
		if(empty($_POST['student_id']))
			return 404;

		redirect('fees/details/'.$_POST['student_id']);

	}

	public function index()
	{
		$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
	}


		public function details($id)
	{ 

		$courses = Models\StudentsRegistrationCourses::where('students_registration_id', $id)->with('courseDetails')->get();
		
		if(!count($courses)){
			failure('Please assign the course to student first');
			redirect('student/add_student');
		}

		$student_details = Models\StudentsRegistration::where('id',$id)->with('feesDetails.courseDetails')->first();

 		$student_details = $student_details->toArray();
		$fees_details = [];
		$fees_trx = [];
		if(count($student_details['fees_details'])){
			$fees_trx = clubFeeTransactions($student_details['fees_details']);
		}

		$pending_fees_details = findPendingFees($courses,$fees_trx);

		$data = [
			'student_details' => $student_details,
			'fees_details' => $fees_trx,
			'pending_fees_details' => $pending_fees_details,
			'courses' => $courses,
			'student_id' => $id
		];

		$jsFiles = [
			base_url('assets/js/hide-payment-panel.js?432'),
		];
		
		$data['js_files'] = $jsFiles;

		$this->load->view('fees/details',$data);

	} 

	public function pay(){
		
		$data = $_POST;
		
				
		$flag = isFeesPending($data);
		
		if($flag['trx_more_than_pending']){
			failure('You cant pay more than the pending amount.');
			redirect('fees/details/'.$data['student_id']);
		}


		if($flag['is_pending'])
			Models\StudentsRegistration::where('id',$data['student_id'])->update([
				'fees_status' => 'PAID'
			]);
		else
			Models\StudentsRegistration::where('id',$data['student_id'])->update([
				'fees_status' => 'PENDING'
			]);


		Models\FeesDetails::create([
			'fees_amount' => $data['fees_amount'],
			'students_registration_id' => $data['student_id'],
			'course_id' => $data['course_id'],
			'fee_submission_date' => date('Y-m-d H:i:s'),
			'added_by' => user_id()
		]);

		success('Fees Paid Successfully');
		// Mobile Sms
		$msg = "Fees payment of Rs.".$data['fees_amount']." successful on ".date('Y-m-d').".";
		$msg .=" Your total pending fees is Rs.".$flag['pending_fees'].".";
		// MobileSms::send($data['phone_no'],$msg);
		MobileSms::send($data['phone_no'],$msg);
		redirect('fees/details/'.$data['student_id']);
	}

	function on_update_encrypt_password_callback($post_array){
		if($post_array['password'] != '__DEFAULT_PASSWORD_'){
      $password=$post_array['password'];
			$hasher = new PasswordHash(
	    		$this->config->item('phpass_hash_strength', 'tank_auth'),
		    	$this->config->item('phpass_hash_portable', 'tank_auth')
			);

			$post_array['password'] = $hasher->HashPassword($password);
			$post_array['activated'] = 1;
			return $post_array;
		}

		unset($post_array['password']);
		return $post_array;
	}

	  function edit_password_callback($post_array){
		return '<input type="password" class="form-control" value="__DEFAULT_PASSWORD_" name="password" style="width:462px">';
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
