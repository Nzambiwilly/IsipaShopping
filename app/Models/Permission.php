<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public const MANAGE_USERS = 'manage_users';
    public const CREATE_USER = 'can_create_user';
    public const EDIT_USER = 'can_edit_user';
    public const DELETE_USER = 'can_delete_user';
    public const VIEW_REPORTS = 'view_reports';
    public const EDIT_PRODUCTS = 'edit_products';

    protected $fillable = [
        'role',
        'description',
    ];

    public function attributions()
    {
        return $this->hasMany(Attributions::class, 'id_permission');
    }

    public static function definitions(): array
    {
        return [
            self::MANAGE_USERS => 'Ouvre le module d administration des comptes utilisateurs.',
            self::CREATE_USER => 'Autorise la creation d utilisateurs depuis le back-office.',
            self::EDIT_USER => 'Autorise la modification des utilisateurs existants.',
            self::DELETE_USER => 'Autorise la suppression d utilisateurs.',
            self::VIEW_REPORTS => 'Autorise la consultation des rapports et indicateurs.',
            self::EDIT_PRODUCTS => 'Autorise la gestion du catalogue produit.',
        ];
    }
}
