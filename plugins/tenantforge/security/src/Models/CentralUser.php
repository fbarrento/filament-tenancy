<?php

declare(strict_types=1);

namespace TenantForge\Security\Models;

use App\Models\Tenant;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Stancl\Tenancy\Contracts\SyncMaster;
use Stancl\Tenancy\Database\Concerns\CentralConnection;
use Stancl\Tenancy\Database\Concerns\ResourceSyncing;
use Stancl\Tenancy\Database\Models\TenantPivot;
use TenantForge\Security\Database\Factories\CentralUserFactory;
use TenantForge\Security\Enums\SecurityPermission;

/**
 * @property string $id
 * @property string $global_id
 * @property string $name
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 */
final class CentralUser extends Authenticatable implements FilamentUser, MustVerifyEmail, SyncMaster
{
    use CentralConnection, Notifiable, ResourceSyncing;

    /** @use HasFactory<CentralUserFactory> */
    use HasFactory;

    use HasRoles;
    use Notifiable;

    public $table = 'users';

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class, 'tenant_users', 'global_user_id', 'tenant_id', 'global_id')
            ->using(TenantPivot::class);
    }

    public function getTenantModelName(): string
    {
        return User::class;
    }

    public function getGlobalIdentifierKeyName(): string
    {
        return 'global_id';
    }

    public function getCentralModelName(): string
    {
        return self::class;
    }

    public function getSyncedAttributeNames(): array
    {
        return [
            'name',
            'password',
            'email',
            'notifications',
        ];
    }

    public function getGlobalIdentifierKey(): string
    {
        return $this->getAttribute($this->getGlobalIdentifierKeyName());
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->can(SecurityPermission::AccessAdminPanel->value);
    }

    public static function newFactory(): CentralUserFactory
    {
        return CentralUserFactory::new();
    }
}
