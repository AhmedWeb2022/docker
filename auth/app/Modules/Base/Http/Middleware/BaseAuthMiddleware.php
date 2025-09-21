<?php

namespace App\Modules\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;

class BaseAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        // Define valid guards explicitly, you can add more as needed
        $validGuards = array_map(fn($guard) => $guard->value, AuthGurdTypeEnum::cases());

        // Ensure at least one guard is passed and is valid
        if (empty($guards)) {
            return response()->json(['error' => 'No guard specified'], 400);
        }

        // Filter out invalid guards that are not in the valid guards list
        $guards = array_filter($guards, fn($g) => in_array($g, $validGuards));

        if (empty($guards)) {
            return response()->json(['error' => 'Invalid guard specified'], 400);
        }

        // Loop through the guards and check authentication
        foreach ($guards as $guard) {
            if (!array_key_exists($guard, config('auth.guards'))) {
                return $next($request);
                // return response()->json(['error' => "Auth guard [{$guard}] is not defined."], 400);
            }

            // Check if the user is authenticated with the specified guard
            if (Auth::guard($guard)->check()) {
                Auth::shouldUse($guard); // Set the current guard to use for the request
                return $next($request);
            }
        }

        // If not authenticated with any valid guard, throw an exception
        throw new AuthenticationException('Unauthenticated.', $guards);
    }
}
