<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
use App\Libs\ApiClient;
use App\Libs\MobileSms;
use App\Libs\QrCode;

class Student extends CI_Controller
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

	public function index()
	{
		$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
	}

	public function bulk_sms(){
		$courses = Models\Courses::where('added_by',user_id())->get();

		$js_files = [
			base_url('assets/js/input-clearance.js')
		];

		$data['js_files'] = $js_files;
		$data['courses'] = $courses;

		$this->load->view('student/bulk-sms',$data);
	}

	function filter_students_attendence(){
		
		if(empty($_POST['course_id']))
			return 404;

		$course_id = $_POST['course_id'];

		$attendence_data = Models\StudentsAttendence::with('student')
																						->with('course')
																						->with('machine')
																						->where('created_at','>=',date('Y-m-d 00:00:00'))
																						->where('created_at','<=',date('Y-m-d 23:59:59'))
																						->where('course_id',$course_id)->get();
    
    

		$this->load->view('student/filter_students_attendence',[
			'attendence_data' => $attendence_data,
		
	  ]);
	}

	function fee_report(){

		  $data['js_files'] = [
		  	base_url('assets/js/jquery-ui.min.js')
 		 ];

      $data['css_files'] = [
		  	base_url('assets/css/jquery-ui.min.css')
 		 ];

		$this->load->view('student/fee_report',$data);
	}

	function post_fee_report(){
		
		if(empty($_POST['start_date']) && $_POST['end_date'])
			return [];
		
		$start_date = $this->db->escape_str(date('Y-m-d 00:00:00', strtotime($_POST['start_date'])));
		$end_date = $this->db->escape_str(date('Y-m-d 23:56:56', strtotime($_POST['end_date'])));
	
		$SQL = "SELECT students_registration.name,students_registration.phone,courses.course_name,courses.course_fees,sum(fees_amount) as total_fees_paid, (courses.course_fees- sum(fees_amount)) as pending_fees
		FROM `fees_details` 
		left JOIN students_registration on students_registration.id= fees_details.students_registration_id
		left join courses on courses.id = fees_details.course_id
		where students_registration.added_by=".$this->db->escape_str(user_id())." AND
		fees_details.fee_submission_date >= '".$this->db->escape_str($start_date)."' AND  fees_details.fee_submission_date <= '".$this->db->escape_str($end_date)."'
		GROUP BY students_registration_id,course_id";
	
		$query = $this->db->query($SQL);
	
		$results = $query->result_array();

		header('Content-type: text/csv');
	  header('Content-Disposition: attachment; filename="cmr_details_report.csv"');
	
		if(!count($results))
			return;

		// $keys = array_keys($results);
	
		$buffer = fopen('php://output', 'w');

		fputcsv($buffer, ['Name','Phone','Course Name','Course Fees','Total Fees Paid','Pending Fees']);

		foreach ($results as $entry) {
		  fputcsv($buffer, $entry);
		}

		fclose($buffer);
			
	}


	function view_attendence(){
		$courses = Models\Courses::where('added_by', user_id())->get();
		$data['courses'] = $courses;
		$this->load->view('student/view_attendence',$data);
	}

	public function bulk_sms_post(){
		
		
		foreach ($_POST as $key => $value)
			$data[$key] = htmlspecialchars($value);
		
		  $students = Models\StudentsRegistration::with('studentCourses')->where('added_by', user_id())->get();
		  $phone_nos = [];
		
		foreach ($students as $key => $student) {
			foreach ($student->studentCourses as $key => $course) {
				if($course->pivot->course_id == $data['course_id']){
							$phone_nos[] = 	$student->phone;
				}

			}
		}

		MobileSms::sendToMany($phone_nos,$data['sms_text']);			
		success('Sms Sent Successfully');
		redirect('student/bulk_sms');
	}

		public function add_student()
	{ 

			$crud = new grocery_CRUD();
			$crud->columns('name','email','phone','registration_date','registration_confirmation','fees_status');
			$crud->set_table('students_registration');
			$crud->add_action('Pay Fees', '', 'fees/details','fa fa-cc-visa');
			$crud->where('added_by',user_id());
			$crud->display_as('id','#Student Id');
			$crud->display_as('city_id','City');
			$crud->display_as('state_id','State');
			$crud->display_as('country_id','Country');
			$crud->callback_after_insert(array($this, 'update_student_unique_code'));
			$crud->field_type('city_id','hidden',1);
			$crud->field_type('state_id','hidden',1);
			$crud->set_relation('country_id','countries','name');
			$crud->set_lang_string('update_error', "Please fill in the required fields");
			$crud->set_lang_string('insert_error', 'Please fill in the required fields');
			$crud->callback_before_insert(array($this,'student_validations'));
			$crud->callback_before_insert(array($this,'generate_student_password'));
			$crud->callback_before_update(array($this,'student_validations'));

			$crud->set_rules('name', 'Name', 'alpha_numeric_spaces');
			// $crud->callback_after_update(array($this, 'update_student_unique_code'));
			$crud->set_relation_n_n('courses', 'students_registration_courses', 'courses', 'students_registration_id', 'course_id', 'course_name','',['courses.added_by' => user_id()]);
			$crud->unset_texteditor('address');
			$crud->add_action('More', '', 'demo/action_more','ui-icon-plus');
			$crud->add_action('Generate Link', base_url('assets/images/feedback_link.png'), 'feedback/send_link/'.user_id());
			// $crud->unique_fields(array('email','phone'));
			$crud->required_fields('name','phone','city','address','highest_qualification');
			$crud->set_field_upload('profile_image','assets/uploads/students/profile_images');
			$crud->set_rules('email', 'email_id', 'valid_email');
			if($crud->getState()=='add'){
				$crud->field_type('registration_confirmation','hidden','PENDING');
			  $crud->field_type('fees_status','hidden');
			}
			if($crud->getState()=='edit')
			  $crud->field_type('fees_status','hidden');
			
			$crud->field_type('added_by','hidden',user_id());
			$crud->field_type('password','hidden');
			$crud->field_type('student_unique_code','hidden');
			$crud->field_type('registration_date','hidden',date('Y-m-d H:i:s'));
			$crud->field_type('created_at','hidden',date('Y-m-d H:i:s'));
			$crud->field_type('updated_at','hidden',date('Y-m-d H:i:s'));
			// $crud->set_theme('bootstrap');
	    $output = $crud->render();
			$this->_example_output($output);

	} 

	function student_validations($post_array, $primary_key=null) {
		
		$err = '';
		$fields = ['name','email'];
		foreach ($fields as $key => $field) {
			if(empty($post_array[$field]))	 {
				
				return false;
			}
		}
		
		return true;
			
	}

	function generate_student_password($post_array, $primary_key=null) {
			
			$post_array['password'] = password_hash($post_array['phone'], PASSWORD_DEFAULT);
			return $post_array;
			
	}


	function qr_code() {
		 $data = [
		 	'spot_id' => user_id(),
		 	'date' => date('Y-m-d')
		 ];

		 $this->load->view('student/qr_code',[
		 	'qr_code' => QRcode::png(json_encode($data))
		 ]);
	}


	function file_uploads(){
			$crud = new grocery_CRUD();
			$crud->columns('file_url','status');
			$crud->set_table('file_uploads');
			$crud->where('added_by',user_id());
			$crud->where('status',1);
			$crud->callback_after_update(array($this, 'delete_inactive_files'));
			$crud->callback_column('file_url',array($this,'_callback_webpage_url'));
			$crud->set_field_upload('file_url','assets/uploads/files');
			$crud->field_type('added_by','hidden',user_id());
  		$crud->field_type('created_at','hidden',date('Y-m-d H:i:s'));
			$crud->field_type('updated_at','hidden',date('Y-m-d H:i:s'));
			$crud->set_theme('bootstrap');
	    $output = $crud->render();
			$this->_example_output($output);

	}	
	
	function view_feedbacks(){
		
			$crud = new grocery_CRUD();
			$crud->set_table('feedback');
			$crud->where('added_by', user_id());
			$crud->display_as('student_id', 'Student Details');
			$crud->unset_read();
			$crud->columns('student_id','speaking','listening','reading','writing');
			$crud->callback_column('student_id',array($this,'getStudentDetails'));
			$crud->callback_after_update(array($this, 'delete_inactive_files'));
			$crud->callback_column('file_url',array($this,'_callback_webpage_url'));
  		$crud->field_type('created_at','hidden',date('Y-m-d H:i:s'));
			$crud->field_type('updated_at','hidden',date('Y-m-d H:i:s'));
			$crud->set_theme('bootstrap');
	    $output = $crud->render();
			$this->_example_output($output);

	}	

	public function getStudentDetails($student_id, $row)
	{
		$student = Models\StudentsRegistration::where('id', $student_id)->first();
		if(!$student)
			return '';
		$disp  = "<div class='align-left'><strong>ID</strong> ".$student->student_unique_code."<br/>";
		$disp .= "<strong>Name </strong> ".$student->name."<br/>";
		$disp .= "<strong>Phone</strong>  ".$student->phone."<br/>";
		$disp .= "<strong>Email</strong> ".$student->email."<br/></div>";
		
		return $disp;	
	}

	function assign_cards(){
			$crud = new grocery_CRUD();
			$crud->set_table('assigned_cards');
			$crud->unset_read();
			$crud->where('assigned_cards.added_by',user_id());
			$crud->set_relation('student_id','students_registration','{student_unique_code} - {name}',array('students_registration.added_by' => user_id()));
			$crud->field_type('added_by','hidden',user_id());
			$crud->callback_before_insert(array($this,'validate_no_of_chars'));
			$crud->callback_before_update(array($this,'validate_no_of_chars'));
			$crud->set_lang_string('insert_error', 'Card Serial Max Length is 14 characters');
			$crud->set_lang_string('update_error', 'Card Serial Max Length is 14 characters');
  		$crud->field_type('created_at','hidden',date('Y-m-d H:i:s'));
			$crud->field_type('updated_at','hidden',date('Y-m-d H:i:s'));
			$crud->set_theme('bootstrap');
	    $output = $crud->render();
			$this->_example_output($output);

	}	

	function validate_no_of_chars($post,$primary_key=null){  

		if(strlen($post['card_serial']) > 14)
			return false;
		return true;
	
	}

	function delete_inactive_files($post_array,$primary_key){
		 $file_path = __DIR__.'/../../assets/uploads/files/'.$post_array['file_url'];
		 if(file_exists($file_path)){
			 	unlink($file_path);
		 }

		return true;

	}

 function _callback_webpage_url($value, $row)
	{
		return "<a target='_BLANK' href='".base_url('assets/uploads/files/'.$value)."'>".base_url('assets/uploads/files/'.$value)."</a>";
	}

  function update_student_unique_code($post_array,$primary_key)
	{
		
		$data = array(
			"student_unique_code" => 'STU'.$primary_key,
			'fees_status' => 'PENDING',
			'registration_confirmation' => 'PENDING'
		);

		$this->db->update('students_registration', $data, array('id' => $primary_key));	 
			 
		return true;
	}

		public function courses()
	{ 
 
			$crud = new grocery_CRUD();
			$crud->set_theme('bootstrap');
			$crud->columns('course_name','course_fees','course_detail');
			$crud->set_table('courses');
			$crud->unset_texteditor('course_detail');
			$crud->where('added_by',user_id());
			$crud->field_type('added_by','hidden',user_id());
			$crud->field_type('created_at','hidden',date('Y-m-d H:i:s'));
			$crud->field_type('updated_at','hidden',date('Y-m-d H:i:s'));
	    $output = $crud->render();
			$this->_example_output($output);

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
