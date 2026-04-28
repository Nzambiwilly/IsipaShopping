<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public const SUPER_ADMIN = 'superadmin';
    public const ADMIN = 'admin';
    public const EDITOR = 'editor';
    public const USER = 'user';

    protected $fillable = [
        'nom',
        'description',
    ];

    public function attributions()
    {
        return $this->hasMany(Attributions::class, 'id_role');
    }

    public static function definitions(): array
    {
        return [
            self::SUPER_ADMIN => [
                'label' => 'SuperAdmin',
                'description' => 'Acces complet a la plateforme et a la gestion des utilisateurs.',
            ],
            self::ADMIN => [
                'label' => 'Admin',
                'description' => 'Administration du catalogue, des comptes et des operations courantes.',
            ],
            self::EDITOR => [
                'label' => 'Editeur',
                'description' => 'Gestion du contenu, du catalogue et consultation limitee des outils internes.',
            ],
            self::USER => [
                'label' => 'Client',
                'description' => 'Compte client standard sans acces au back-office.',
            ],
        ];
    }

    public static function labels(): array
    {
        return collect(self::definitions())
            ->mapWithKeys(fn (array $definition, string $role) => [$role => $definition['label']])
            ->all();
    }
}
