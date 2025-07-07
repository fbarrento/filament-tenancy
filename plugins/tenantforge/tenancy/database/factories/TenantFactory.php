<?php

declare(strict_types=1);

namespace TenantForge\Tenancy\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use TenantForge\Tenancy\Models\Tenant;

/**
 * @extends Factory<Tenant>
 */
final class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->company();

        return [
            'id' => (string) Str::uuid(),
            'name' => $name,
            'short_name' => Str::slug($name),
            'avatar' => null,
        ];
    }

    /**
     * Set the tenant name.
     */
    public function named(string $name): static
    {
        return $this->state(fn () => [
            'name' => $name,
            'short_name' => Str::slug($name),
        ]);
    }

    /**
     * Set the tenant short name.
     */
    public function shortName(string $shortName): static
    {
        return $this->state(fn () => [
            'short_name' => $shortName,
        ]);
    }

    /**
     * Set the tenant with an avatar.
     */
    public function withAvatar(string $avatar = 'avatar.png'): static
    {
        return $this->state(fn () => [
            'avatar' => $avatar,
        ]);
    }

    /**
     * Create a tenant with a specific domain.
     */
    public function withDomain(string $domain): static
    {
        return $this->afterCreating(function (Tenant $tenant) use ($domain) {
            $tenant->domains()->create([
                'domain' => $domain,
            ]);
        });
    }

    /**
     * Create a tenant with multiple domains.
     *
     * @param  array<string>  $domains
     */
    public function withDomains(array $domains): static
    {
        return $this->afterCreating(function (Tenant $tenant) use ($domains) {
            foreach ($domains as $domain) {
                $tenant->domains()->create([
                    'domain' => $domain,
                ]);
            }
        });
    }
}
