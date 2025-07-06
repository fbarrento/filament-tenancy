<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('tenant_id')->nullable();
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants');

            $table->uuid('inviter_id');
            $table->foreign('inviter_id')
                ->references('global_id')
                ->on('users');

            $table->string('email');

            $table->string('token', 100)->unique();

            $table->string('type');

            $table->string('role')->nullable();

            $table->string('status');

            $table->timestamp('expires_at');

            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index('email');
            $table->index('status');
            $table->index('expires_at');
            $table->index('type');
            $table->index(['tenant_id', 'email']);
        });
    }
};
