<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Kredit extends Model {
    protected $table = 'kredit';
    protected $primaryKey = 'kredit_id';
    public $timestamps = false;
    protected $fillable = [
        'kredit_date', 'kredit_desc', 'kredit_value',
        'user_user_id', 'kredit_input_date', 'kredit_last_update',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_user_id', 'user_id');
    }
}
