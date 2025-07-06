<?php

declare(strict_types=1);

namespace TenantForge\Security\Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use TenantForge\Security\Enums\InvitationStatus;
use TenantForge\Security\Enums\InvitationType;
use TenantForge\Security\Models\CentralUser;
use TenantForge\Security\Models\Invitation;

/**
 * @extends Factory<Invitation>
 */
class InvitationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Invitation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'token' => Str::random(40),
            'role' => fake()->randomElement(['admin', 'editor', 'viewer']),
            'status' => InvitationStatus::PENDING,
            'expires_at' => Carbon::now()->addDays(7),
            'metadata' => null,
        ];
    }

    /**
     * Set the invitation type to tenant.
     */
    public function tenant(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => InvitationType::TENANT,
        ]);
    }

    /**
     * Set the invitation type to central.
     */
    public function central(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => InvitationType::CENTRAL,
            'tenant_id' => null,
        ]);
    }

    /**
     * Set the invitation status to pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => InvitationStatus::PENDING,
            'expires_at' => Carbon::now()->addDays(7),
        ]);
    }

    /**
     * Set the invitation status as accepted.
     */
    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => InvitationStatus::ACCEPTED,
        ]);
    }

    /**
     * Set the invitation status as expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => InvitationStatus::EXPIRED,
            'expires_at' => Carbon::now()->subDays(1),
        ]);
    }

    /**
     * Set the invitation status as revoked.
     */
    public function revoked(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => InvitationStatus::REVOKED,
        ]);
    }

    /**
     * Set the tenant for the invitation.
     */
    public function forTenant(Tenant $tenant): static
    {
        return $this->state(fn (array $attributes) => [
            'tenant_id' => $tenant->id,
            'type' => InvitationType::TENANT,
        ]);
    }

    /**
     * Set the inviter for the invitation.
     */
    public function from(CentralUser $inviter): static
    {
        return $this->state(fn (array $attributes) => [
            'inviter_id' => $inviter->global_id,
        ]);
    }
}
