<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model {
    protected $table = 'holiday';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['year', 'date', 'info'];
}
