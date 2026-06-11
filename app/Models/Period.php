<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Period extends Model {
    protected $table = 'period';
    protected $primaryKey = 'period_id';
    public $timestamps = false;
    protected $fillable = ['period_start', 'period_end', 'period_status'];

    public function payments() {
        return $this->hasMany(Payment::class, 'period_period_id', 'period_id');
    }

    public function scopeActive($query) {
        return $query->where('period_status', 1);
    }
}
