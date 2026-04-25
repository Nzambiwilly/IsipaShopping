<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'role',
        'description',
    ];

    public function attributions()
    {
        return $this->hasMany(Attributions::class, 'id_permission');
    }
}
