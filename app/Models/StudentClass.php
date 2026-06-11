<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model {
    protected $table = 'class';
    protected $primaryKey = 'class_id';
    public $timestamps = false;
    protected $fillable = ['class_name'];

    public function students() {
        return $this->hasMany(Student::class, 'class_class_id', 'class_id');
    }
}
