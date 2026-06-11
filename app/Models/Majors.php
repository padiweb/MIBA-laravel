<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Majors extends Model {
    protected $table = 'majors';
    protected $primaryKey = 'majors_id';
    public $timestamps = false;
    protected $fillable = ['majors_name', 'majors_short_name'];

    public function students() {
        return $this->hasMany(Student::class, 'majors_majors_id', 'majors_id');
    }
}
