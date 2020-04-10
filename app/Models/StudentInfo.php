<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class StudentInfo extends Model
{
    protected $table = "student_info";

    public function user(){
        return $this->belongsTo(User::class);
    }
}
