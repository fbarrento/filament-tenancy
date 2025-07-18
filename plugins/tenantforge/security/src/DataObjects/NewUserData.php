<?php

declare(strict_types=1);

namespace TenantForge\Security\DataObjects;

use Carbon\Carbon;
use Illuminate\Support\Str;

final class NewUserData
{
    public ?Carbon $emailVerifiedAt;

    public function __construct(
        public ?string $globalId,
        public string $name,
        public string $email,
        public string $password,
        public bool $isVerified = false,
    ) {

        if (! $globalId) {
            $this->$globalId = Str::uuid()->toString();
        }

        if ($isVerified) {
            $this->emailVerifiedAt = now();
        }

    }

    /**
     * @param  array{globalId?: string, name: string, email: string, password: string, isVerified?: bool}  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            globalId: $data['globalId'] ?? null,
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            isVerified: $data['isVerified'] ?? false
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'global_id' => $this->globalId,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ];

        if ($this->emailVerifiedAt) {
            $data['email_verified_at'] = $this->emailVerifiedAt;
        }

        return $data;
    }
}
