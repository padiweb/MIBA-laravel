<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Log extends Model {
    protected $table = 'logs';
    protected $primaryKey = 'log_id';
    public $timestamps = false;
    protected $fillable = [
        'log_date', 'log_action', 'log_module', 'log_info', 'user_id',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
