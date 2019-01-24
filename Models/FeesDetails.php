<?php
namespace Models;
use Illuminate\Database\Eloquent\Model;

class FeesDetails extends Model
{ 
    protected $table    = 'fees_details';
    protected $fillable    = ['fees_amount','course_id','students_registration_id','fee_submission_date','added_by'];

 	  function courseDetails(){
    	return $this->hasOne(Courses::class,'id','course_id');
    }

}	
