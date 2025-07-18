<?php

declare(strict_types=1);

namespace TenantForge\Security\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Stancl\Tenancy\Database\Concerns\CentralConnection;
use TenantForge\Security\Database\Factories\InvitationFactory;
use TenantForge\Security\Enums\InvitationStatus;
use TenantForge\Security\Enums\InvitationType;
use TenantForge\Tenancy\Models\Tenant;

/**
 * Invitation Model for inviting users to tenants or central application.
 *
 * @property string $id UUID primary key
 * @property string|null $tenant_id UUID of the associated tenant (null for central invitations)
 * @property string $inviter_id UUID of the user who created the invitation
 * @property string $email Email address of the invited user
 * @property string $token Secure random token for verification
 * @property InvitationType $type Type of invitation (central or tenant)
 * @property string|null $role Role to assign to the user upon acceptance
 * @property InvitationStatus $status Current status of the invitation
 * @property Carbon $expires_at Timestamp when the invitation expires
 * @property array|null $metadata Additional JSON metadata
 * @property Carbon $created_at Timestamp when the invitation was created
 * @property Carbon $updated_at Timestamp when the invitation was last updated
 * @property-read Tenant|null $tenant The tenant this invitation is for
 * @property-read CentralUser $inviter The user who created the invitation
 *
 * @method static \Illuminate\Database\Eloquent\Builder query()
 * @method static static create(array $attributes)
 * @method static static findOrFail(string $id)
 */
class Invitation extends Model
{
    use CentralConnection;

    /** @use HasFactory<InvitationFactory> */
    use HasFactory;

    use HasUuids;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'immutable_datetime',
            'metadata' => 'encrypted:array',
            'type' => InvitationType::class,
            'status' => InvitationStatus::class,
        ];
    }

    /**
     * Generate a unique invitation token.
     */
    public static function generateToken(): string
    {
        return Str::random(40);
    }

    /**
     * Check if the invitation is expired based on the expiration date.
     */
    public function hasExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Update the status based on expiration.
     */
    public function updateStatus(): self
    {
        if ($this->status === InvitationStatus::PENDING && $this->hasExpired()) {
            $this->status = InvitationStatus::EXPIRED;
            $this->save();
        }

        return $this;
    }

    /**
     * Get the tenant that the invitation is for.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the user who created the invitation.
     */
    public function inviter(): BelongsTo
    {
        return $this->belongsTo(CentralUser::class, 'inviter_id', 'global_id');
    }

    public function isTenantInvitation(): bool
    {
        return $this->type === InvitationType::TENANT;
    }

    public function isCentralInvitation(): bool
    {
        return $this->type === InvitationType::CENTRAL;
    }

    /**
     * Mark the invitation as accepted.
     */
    public function markAsAccepted(): self
    {
        $this->status = InvitationStatus::ACCEPTED;
        $this->save();

        return $this;
    }

    /**
     * Mark the invitation as revoked.
     */
    public function markAsRevoked(): self
    {
        $this->status = InvitationStatus::REVOKED;
        $this->save();

        return $this;
    }

    /**
     * Create a new invitation for a tenant.
     */
    public static function createForTenant(
        string $email,
        Tenant $tenant,
        CentralUser $inviter,
        ?string $role = null,
        ?array $metadata = null,
        int $expiresInDays = 7
    ): self {
        return static::create([
            'email' => $email,
            'tenant_id' => $tenant->id,
            'inviter_id' => $inviter->global_id,
            'token' => static::generateToken(),
            'type' => InvitationType::TENANT,
            'role' => $role,
            'status' => InvitationStatus::PENDING,
            'expires_at' => Carbon::now()->addDays($expiresInDays),
            'metadata' => $metadata,
        ]);
    }

    /**
     * Create a new invitation for the central application.
     */
    public static function createForCentral(
        string $email,
        CentralUser $inviter,
        ?string $role = null,
        ?array $metadata = null,
        int $expiresInDays = 7
    ): self {
        return static::create([
            'email' => $email,
            'tenant_id' => null,
            'inviter_id' => $inviter->global_id,
            'token' => static::generateToken(),
            'type' => InvitationType::CENTRAL,
            'role' => $role,
            'status' => InvitationStatus::PENDING,
            'expires_at' => Carbon::now()->addDays($expiresInDays),
            'metadata' => $metadata,
        ]);
    }

    /**
     * Find an invitation by its token.
     */
    public static function findByToken(string $token): ?self
    {
        return static::query()->where('token', $token)->first();
    }

    /**
     * Check if an email already has a pending invitation for a specific tenant.
     */
    public static function hasPendingInvitationForTenant(string $email, Tenant $tenant): bool
    {
        return static::query()
            ->where('email', $email)
            ->where('tenant_id', $tenant->id)
            ->where('status', InvitationStatus::PENDING)
            ->where('expires_at', '>', now())
            ->exists();
    }

    /**
     * Check if an email already has a pending invitation for the central application.
     */
    public static function hasPendingInvitationForCentral(string $email): bool
    {
        return static::query()
            ->where('email', $email)
            ->whereNull('tenant_id')
            ->where('status', InvitationStatus::PENDING)
            ->where('expires_at', '>', now())
            ->exists();
    }

    protected static function newFactory(): InvitationFactory
    {
        return InvitationFactory::new();
    }
}
