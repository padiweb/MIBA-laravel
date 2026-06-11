<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model {
    protected $table = 'setting';
    protected $primaryKey = 'setting_id';
    public $timestamps = false;
    protected $fillable = ['setting_name', 'setting_value', 'setting_last_update'];

    // Helper: ambil nilai setting berdasarkan ID
    public static function getValue(int $id): string {
        $s = self::find($id);
        return $s ? ($s->setting_value ?? '') : '';
    }
}
