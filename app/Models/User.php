<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nom_complet',
        'email',
        'password',
        'role',
        'permission',
        'adresse_de_livraison',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roleRecord(): HasOne
    {
        return $this->hasOne(Role::class, 'nom', 'role');
    }

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_user')
            ->withTimestamps();
    }

    public function directPermissionCodes(): array
    {
        $permissions = $this->relationLoaded('permissions')
            ? $this->getRelation('permissions')
            : $this->permissions()->get();

        return $permissions
            ->pluck('role')
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public function rolePermissionCodes(): array
    {
        return Permission::query()
            ->whereHas('attributions.role', function ($query): void {
                $query->where('nom', $this->role);
            })
            ->pluck('role')
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public function allPermissionCodes(): array
    {
        return collect($this->rolePermissionCodes())
            ->merge($this->directPermissionCodes())
            ->unique()
            ->values()
            ->all();
    }

    public function hasPermission(string $permissionCode): bool
    {
        if ($this->hasRole(Role::SUPER_ADMIN)) {
            return true;
        }

        return in_array($permissionCode, $this->allPermissionCodes(), true);
    }

    public function syncPermissionsByCodes(array $permissionCodes): void
    {
        $permissionIds = Permission::query()
            ->whereIn('role', collect($permissionCodes)->filter()->unique()->values())
            ->pluck('id')
            ->all();

        $this->permissions()->sync($permissionIds);
    }
}
