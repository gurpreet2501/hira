<?php
namespace Models;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{ 
    protected $table    = 'feedback';
    protected $fillable    = ['student_id','speaking','listening','reading','writing','added_by'];

    function student(){
    	return $this->belongsTo(StudentsRegistration::class, 'student_id', 'id');
    }

}	
