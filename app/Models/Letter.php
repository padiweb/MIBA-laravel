<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Letter extends Model {
    protected $table = 'letter';
    protected $primaryKey = 'letter_id';
    public $timestamps = false;
    protected $fillable = ['letter_number', 'letter_month', 'letter_year'];
}
