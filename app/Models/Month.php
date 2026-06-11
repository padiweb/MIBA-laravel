<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Month extends Model {
    protected $table = 'month';
    protected $primaryKey = 'month_id';
    public $timestamps = false;
    protected $fillable = ['month_name'];
}
