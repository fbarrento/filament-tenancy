<?php

namespace App\Livewire;

use Filament\Facades\Filament;
use Filament\Livewire\DatabaseNotifications as BaseDatabaseNotifications;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use TenantForge\Security\Enums\AuthGuard;
use TenantForge\Security\Models\CentralUser;

use function get_class;
use function info;

class DatabaseNotifications extends BaseDatabaseNotifications
{
    public static ?string $authGuard = AuthGuard::Central->value;

    public function getUser(): Model|Authenticatable|null
    {

        info('Get user');

        $user = CentralUser::query()
            ->where('global_id', Filament::auth()->user()->global_id)
            ->first();

        info('Get user', [
            'user' => get_class($user),
        ]);

        return $user;

    }

    public function getNotificationsQuery(): Builder|Relation
    {
        info('Get notifications');

        return $this->getUser()->notifications()->where('data->format', 'filament');
    }
}
