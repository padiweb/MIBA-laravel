<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Bulan extends Model {
    protected $table = 'bulan';
    protected $primaryKey = 'bulan_id';
    public $timestamps = false;
    protected $fillable = [
        'student_student_id', 'payment_payment_id', 'month_month_id',
        'bulan_bill', 'bulan_status', 'bulan_pay_desc', 'bulan_number_pay',
        'bulan_date_pay', 'user_user_id', 'bulan_input_date', 'bulan_last_update',
    ];

    public function student() {
        return $this->belongsTo(Student::class, 'student_student_id', 'student_id');
    }

    public function payment() {
        return $this->belongsTo(Payment::class, 'payment_payment_id', 'payment_id');
    }

    public function month() {
        return $this->belongsTo(Month::class, 'month_month_id', 'month_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_user_id', 'user_id');
    }
}
