<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Debit extends Model {
    protected $table = 'debit';
    protected $primaryKey = 'debit_id';
    public $timestamps = false;
    protected $fillable = [
        'debit_date', 'debit_desc', 'debit_value',
        'user_user_id', 'debit_input_date', 'debit_last_update',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_user_id', 'user_id');
    }
}
