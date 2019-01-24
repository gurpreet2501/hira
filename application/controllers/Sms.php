<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
use App\Libs\ApiClient;
use App\Libs\MobileSms;

class Sms extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		auth_force();
		$this->load->helper('url');
		$this->load->library('tank_auth');
	}

	function send(){

		if(empty($_POST['phone']) || empty($_POST['message'])){
			failure('Either Phone or message is invalid or missing !');
			redirect('fees/details/'.$_POST['student_id']);
		}

		MobileSms::send($_POST['phone'],$_POST['message']);
 	  success('Sms sent successfully');
 	  redirect('fees/details/'.$_POST['student_id']);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
