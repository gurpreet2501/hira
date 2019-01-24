<?php
namespace Models;
use Illuminate\Database\Eloquent\Model;

class StudentsRegistrationCourses extends Model
{ 
    protected $table    = 'students_registration_courses';
    protected $fillable    = [];
 
    function courseDetails(){
    	return $this->hasOne(Courses::class,'id','course_id');
    }
 
}	
