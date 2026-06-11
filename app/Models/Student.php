<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Student extends Model {
    protected $table = 'student';
    protected $primaryKey = 'student_id';
    public $timestamps = false;

    protected $fillable = [
        'student_nis', 'student_nisn', 'student_password', 'student_full_name',
        'student_gender', 'student_born_place', 'student_born_date', 'student_img',
        'student_phone', 'student_hobby', 'student_address', 'student_name_of_mother',
        'student_name_of_father', 'student_parent_phone', 'class_class_id',
        'majors_majors_id', 'student_status', 'student_input_date', 'student_last_update',
    ];

    public function class() {
        return $this->belongsTo(StudentClass::class, 'class_class_id', 'class_id');
    }

    public function majors() {
        return $this->belongsTo(Majors::class, 'majors_majors_id', 'majors_id');
    }

    public function payments() {
        return $this->hasMany(Bulan::class, 'student_student_id', 'student_id');
    }

    public function scopeActive($query) {
        return $query->where('student_status', 1);
    }
}
