<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LogTrx extends Model {
    protected $table = 'log_trx';
    protected $primaryKey = 'log_trx_id';
    public $timestamps = false;
    protected $fillable = [
        'student_student_id', 'bulan_bulan_id', 'bebas_pay_bebas_pay_id',
        'log_trx_input_date', 'log_trx_last_update',
    ];

    public function student() {
        return $this->belongsTo(Student::class, 'student_student_id', 'student_id');
    }
}
