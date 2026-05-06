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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use Illuminate\Validation\Rule;

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
            'email'    => [
                'required', 
                'string', 
                'lowercase', 
                'email', 
                'max:255', 
                Rule::unique('users')->whereNull('deleted_at')
            ],
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

        // Send Welcome Email after the response is sent to the browser via Resend API
        dispatch(function () use ($user) {
            try {
                Log::info('Attempting to send welcome email to: ' . $user->email . ' via Resend');

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('RESEND_API_KEY'),
                    'Content-Type'  => 'application/json',
                ])->post('https://api.resend.com/emails', [
                    'from' => env('MAIL_FROM', 'onboarding@resend.dev'),
                    'to'   => [env('TEST_EMAIL_RECIPIENT', $user->email)],
                    'subject' => 'Welcome to Evento!',
                    'html' => view('emails.welcome', ['user' => $user])->render(),
                ]);

                if ($response->successful()) {
                    Log::info('Welcome email sent successfully to: ' . $user->email);
                } else {
                    Log::error('Resend failed: ' . $response->status() . ' - ' . $response->body());
                }
            } catch (\Exception $e) {
                Log::error('Resend failed with exception: ' . $e->getMessage());
            }
        })->afterResponse();

        return match ($user->role) {
            'organizer' => redirect()->route('organizer.dashboard'),
            default     => redirect()->route('home'),
        };
    }
}
