<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Database\Models\TenantPivot;

/**
 * @property string $id
 * @property string $name
 * @property string $short_name
 * @property string $avatar
 * @property string|null $avatar_url
 */
final class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    /**
     * @return array<int,string>
     */
    public static function getCustomColumns(): array
    {
        return [
            'name',
            'id',
            'short_name',
            'avatar',
        ];
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: function (): ?string {
                if (! $this->avatar) {
                    return null;
                }
                if (tenancy()->initialized) {
                    return Storage::disk('public')->url($this->avatar);
                }
                $tenantPrefix = config('tenancy.filesystem.suffix_base').$this->id;

                return url("/{$tenantPrefix}/{$this->avatar}");
            }
        );
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(CentralUser::class, 'tenant_users', 'tenant_id', 'global_user_id', 'id', 'global_id')
            ->using(TenantPivot::class);
    }
}
