<?php
namespace Models;
use Illuminate\Database\Eloquent\Model;

class Cards extends Model
{ 
    protected $table    = 'assigned_cards';
  
  function student(){
  	return $this->belongsTo(StudentsRegistration::class, 'student_id', 'id');
  }


  function studentCourses(){
  	return $this->belongsToMany(Courses::class,'students_registration_courses','students_registration_id','course_id');
  }


}
