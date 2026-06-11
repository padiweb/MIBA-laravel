<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BebasPay extends Model {
    protected $table = 'bebas_pay';
    protected $primaryKey = 'bebas_pay_id';
    public $timestamps = false;
    protected $fillable = [
        'bebas_bebas_id', 'bebas_pay_number', 'bebas_pay_bill',
        'bebas_pay_desc', 'user_user_id', 'bebas_pay_input_date', 'bebas_pay_last_update',
    ];

    public function bebas() {
        return $this->belongsTo(Bebas::class, 'bebas_bebas_id', 'bebas_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_user_id', 'user_id');
    }
}
