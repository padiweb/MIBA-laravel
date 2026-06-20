<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
    protected $table = 'payment';
    protected $primaryKey = 'payment_id';
    public $timestamps = false;
    protected $fillable = [
        'payment_type', 'period_period_id', 'pos_pos_id',
        'payment_input_date', 'payment_last_update',
    ];

    public function period() {
        return $this->belongsTo(Period::class, 'period_period_id', 'period_id');
    }

    public function pos() {
        return $this->belongsTo(Pos::class, 'pos_pos_id', 'pos_id');
    }

    public function bulans() {
        return $this->hasMany(Bulan::class, 'payment_payment_id', 'payment_id');
    }

    public function bebas() {
        return $this->hasMany(Bebas::class, 'payment_payment_id', 'payment_id');
    }
}
