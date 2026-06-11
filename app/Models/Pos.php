<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pos extends Model {
    protected $table = 'pos';
    protected $primaryKey = 'pos_id';
    public $timestamps = false;
    protected $fillable = ['pos_name', 'pos_description'];

    public function payments() {
        return $this->hasMany(Payment::class, 'pos_pos_id', 'pos_id');
    }
}
