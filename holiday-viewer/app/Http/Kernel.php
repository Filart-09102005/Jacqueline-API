<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // Handles trusted proxies
        \App\Http\Middleware\TrustProxies::class,

        // Handles CORS (Cross-Origin Resource Sharing)
        \Illuminate\Http\Middleware\HandleCors::class,

        // Checks for maintenance mode
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,

        // Validates POST size limit
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,

        // Trims whitespace from request data
        \App\Http\Middleware\TrimStrings::class,

        // Converts empty strings to null
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * These groups may be assigned to routes in your web.php and api.php files.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            // Encrypts cookies
            \App\Http\Middleware\EncryptCookies::class,

            // Adds cookies to response
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,

            // Starts the session
            \Illuminate\Session\Middleware\StartSession::class,

            // Shares validation errors via sessions
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,

            // Protects against CSRF attacks
            \App\Http\Middleware\VerifyCsrfToken::class,

            // Substitutes route bindings
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // Limits request rate
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',

            // Substitutes route bindings
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned individually to routes.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        // Default Laravel auth middleware
        'auth' => \App\Http\Middleware\Authenticate::class,

        // Redirects logged-in users away from guest routes
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,

        // Confirms password re-entry for sensitive actions
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,

        // Ensures email is verified
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        // Restricts throttled requests
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces a specific order for certain middleware.
     *
     * @var array<int, class-string|string>
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Authenticate::class,
        \Illuminate\Routing\Middleware\ThrottleRequests::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ];
}
