<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $fillable = [
        'user_email', 'user_password', 'user_full_name',
        'user_image', 'user_description', 'user_role_role_id',
        'user_is_deleted', 'user_input_date', 'user_last_update',
    ];

    protected $hidden = ['user_password'];

    // Override auth password field
    public function getAuthPassword() {
        return $this->user_password;
    }

    public function role() {
        return $this->belongsTo(UserRole::class, 'user_role_role_id', 'role_id');
    }

    public function scopeActive($query) {
        return $query->where('user_is_deleted', 0);
    }
}
