<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Information extends Model {
    protected $table = 'information';
    protected $primaryKey = 'information_id';
    public $timestamps = false;
    protected $fillable = [
        'information_title', 'information_desc', 'information_img',
        'information_publish', 'user_user_id',
        'information_input_date', 'information_last_update',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_user_id', 'user_id');
    }
}
