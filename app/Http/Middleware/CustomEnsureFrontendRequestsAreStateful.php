<?php

namespace App\Http\Middleware;

use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class CustomEnsureFrontendRequestsAreStateful extends EnsureFrontendRequestsAreStateful
{
    /**
     * Configure secure cookie sessions conditionally.
     *
     * @return void
     */
    protected function configureSecureCookieSessions()
    {
        $sameSite = app()->environment('local') ? 'none' : 'lax';

        config([
            'session.http_only' => true,
            'session.same_site' => $sameSite,
        ]);
    }
}
