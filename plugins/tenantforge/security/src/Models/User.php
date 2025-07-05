<?php

namespace TenantForge\Security\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Tenant;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection as SupportCollection;
use Spatie\Permission\Traits\HasRoles;
use Stancl\Tenancy\Contracts\Syncable;
use Stancl\Tenancy\Database\Concerns\ResourceSyncing;
use TenantForge\Security\Database\Factories\UserFactory;

/**
 * @property $id
 * @property $name
 * @property $email
 * @property $email_verified_at
 * @property $password
 */
class User extends Authenticatable implements FilamentUser, Syncable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasRoles;
    use Notifiable;
    use ResourceSyncing;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    /**
     * @return SupportCollection<int, Tenant>|array<int, Tenant>
     */
    public function getTenants(Panel $panel): SupportCollection|array
    {

        return CentralUser::query()
            ->where('global_id', $this->global_id)
            ->first()
            ->tenants;
    }

    public function getGlobalIdentifierKey()
    {
        return $this->getAttribute($this->getGlobalIdentifierKeyName());
    }

    public function getGlobalIdentifierKeyName(): string
    {
        return 'global_id';
    }

    public function getCentralModelName(): string
    {
        return CentralUser::class;
    }

    public function getSyncedAttributeNames(): array
    {
        return [
            'name',
            'password',
            'email',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
