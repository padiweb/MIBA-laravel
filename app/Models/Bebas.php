<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Bebas extends Model {
    protected $table = 'bebas';
    protected $primaryKey = 'bebas_id';
    public $timestamps = false;
    protected $fillable = [
        'student_student_id', 'payment_payment_id', 'bebas_bill',
        'bebas_total_pay', 'bebas_desc', 'bebas_input_date', 'bebas_last_update',
    ];

    public function student() {
        return $this->belongsTo(Student::class, 'student_student_id', 'student_id');
    }

    public function payment() {
        return $this->belongsTo(Payment::class, 'payment_payment_id', 'payment_id');
    }

    public function bebasPays() {
        return $this->hasMany(BebasPay::class, 'bebas_bebas_id', 'bebas_id');
    }
}
