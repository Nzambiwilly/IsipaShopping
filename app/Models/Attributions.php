<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attributions extends Model
{
    protected $table = 'attributions';

    protected $fillable = [
        'id_permission',
        'id_role',
    ];

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'id_permission');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }
}
