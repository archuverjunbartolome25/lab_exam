<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Requests\LoginRequest;

class AuthenticateUser
{
    /**
     * Handle the incoming authentication request.
     *
     * @param  \Laravel\Fortify\Http\Requests\LoginRequest  $request
     * @return mixed
     */
    public function handle(LoginRequest $request, callable $next)
    {
        // First, ensure the login is not throttled
        $this->ensureLoginIsNotThrottled($request);

        // Attempt to authenticate the user
        $user = $this->authenticateUser($request);

        if (! $user) {
            return $this->failedLoginResponse($request);
        }

        // Check if user is admin - admins should not login through regular user login
        if ($user->role === 'admin') {
            return $this->adminAccessDeniedResponse($request);
        }

        // Prepare the authenticated session
        $this->prepareAuthenticatedSession($request, $user);

        return $next($request);
    }

    /**
     * Ensure the login request is not throttled.
     *
     * @param  \Laravel\Fortify\Http\Requests\LoginRequest  $request
     * @return void
     */
    protected function ensureLoginIsNotThrottled(LoginRequest $request)
    {
        if (Features::enabled(Features::throttleLogin())) {
            if (! $this->ensureLoginIsNotThrottled($request)) {
                throw ValidationException::withMessages([
                    Fortify::username() => __('auth.throttle', [
                        'seconds' => RateLimiter::availableIn($this->throttleKey($request)),
                    ]),
                ]);
            }
        }
    }

    /**
     * Attempt to authenticate the user.
     *
     * @param  \Laravel\Fortify\Http\Requests\LoginRequest  $request
     * @return \App\Models\User|null
     */
    protected function authenticateUser(LoginRequest $request)
    {
        $user = User::where(Fortify::username(), $request->input(Fortify::username()))->first();

        if ($user && Hash::check($request->input('password'), $user->password)) {
            return $user;
        }

        return null;
    }

    /**
     * Prepare the authenticated session.
     *
     * @param  \Laravel\Fortify\Http\Requests\LoginRequest  $request
     * @param  \App\Models\User  $user
     * @return void
     */
    protected function prepareAuthenticatedSession(LoginRequest $request, User $user)
    {
        $request->session()->regenerate();

        // Set the authenticated user in the session
        auth()->login($user);
    }

    /**
     * Get the failed login response.
     *
     * @param  \Laravel\Fortify\Http\Requests\LoginRequest  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function failedLoginResponse(LoginRequest $request)
    {
        throw ValidationException::withMessages([
            Fortify::username() => __('auth.failed'),
        ]);
    }

    /**
     * Get the admin access denied response.
     *
     * @param  \Laravel\Fortify\Http\Requests\LoginRequest  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function adminAccessDeniedResponse(LoginRequest $request)
    {
        throw ValidationException::withMessages([
            Fortify::username() => 'Admin accounts should use the admin login panel.',
        ]);
    }
}
