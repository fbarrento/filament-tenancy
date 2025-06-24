<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Stancl\Tenancy\Features\UserImpersonation;

final class ImpersonateController
{
    public function index(Request $request, string $token): RedirectResponse
    {

        return UserImpersonation::makeResponse($token);

    }
}
