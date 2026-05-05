<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', 'in:user,organizer'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Send Welcome Email after the response is sent to the browser
        dispatch(function () use ($user) {
            try {
                \Illuminate\Support\Facades\Log::info('Attempting to send welcome email to: ' . $user->email);
                Mail::to($user->email)->send(new WelcomeMail($user));
                \Illuminate\Support\Facades\Log::info('Welcome email sent successfully to: ' . $user->email);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Welcome email failed: ' . $e->getMessage());
            }
        })->afterResponse();

        return match ($user->role) {
            'organizer' => redirect()->route('organizer.dashboard'),
            default     => redirect()->route('home'),
        };
    }
}
