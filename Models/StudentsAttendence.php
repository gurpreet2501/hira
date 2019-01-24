<?php
namespace Models;
use Illuminate\Database\Eloquent\Model;

class StudentsAttendence extends Model
{ 
    protected $table    = 'students_attendence';
    protected $fillable    = ['course_id','student_id','card_id','attendence_time','machine_id','added_by'];

    function student(){
    	return $this->belongsTo(StudentsRegistration::class, 'student_id', 'id');
    }
    function course(){
    	return $this->belongsTo(Courses::class, 'course_id', 'id');
    }

    function machine(){
    	return $this->belongsTo(Machines::class, 'machine_id', 'id');
    }
}	
