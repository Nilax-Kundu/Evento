<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $user = $this;
        dispatch(function () use ($token, $user) {
            try {
                Log::info('Attempting to send password reset email to: ' . $user->email . ' via Resend');

                $url = url(route('password.reset', [
                    'token' => $token,
                    'email' => $user->getEmailForPasswordReset(),
                ], false));

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('RESEND_API_KEY'),
                    'Content-Type'  => 'application/json',
                ])->post('https://api.resend.com/emails', [
                    'from' => env('MAIL_FROM', 'no-reply@resend.dev'),
                    'to'   => [$user->email],
                    'subject' => 'Reset Your Password - Evento',
                    'html' => view('emails.reset-password', [
                        'url' => $url,
                        'name' => $user->name,
                        'count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire'),
                    ])->render(),
                ]);

                if ($response->successful()) {
                    Log::info('Password reset email sent successfully to: ' . $user->email);
                } else {
                    Log::error('Resend failed for reset: ' . $response->status() . ' - ' . $response->body());
                }
            } catch (\Exception $e) {
                Log::error('Resend failed for reset with exception: ' . $e->getMessage());
            }
        })->afterResponse();
    }
}
