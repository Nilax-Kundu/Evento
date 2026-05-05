<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Evento</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; padding: 0; }
        .header { background: #0f766e; color: white; padding: 30px 20px; text-align: center; border-radius: 12px 12px 0 0; }
        .content { padding: 30px; border: 1px solid #e2e8f0; border-top: none; border-radius: 0 0 12px 12px; background: white; }
        .button-container { text-align: center; margin: 30px 0; }
        .button { background: #0f766e; color: white !important; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block; }
        .footer { margin-top: 30px; font-size: 14px; color: #64748b; border-top: 1px solid #f1f5f9; padding-top: 20px; }
        .role-badge { display: inline-block; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: bold; text-transform: uppercase; margin-bottom: 15px; }
        .role-user { background: #f1f5f9; color: #475569; }
        .role-organizer { background: #f0fdfa; color: #0f766e; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin:0;">Welcome to Evento!</h2>
        </div>
        <div class="content">
            <p>Hi {{ $user->name }},</p>
            
            <p>We're thrilled to have you join our community! Your account has been successfully created.</p>

            <div class="role-badge {{ $user->role === 'organizer' ? 'role-organizer' : 'role-user' }}">
                {{ $user->role === 'organizer' ? 'Organizer Account' : 'Attendee Account' }}
            </div>

            @if($user->role === 'organizer')
                <p>As an organizer, you can now start creating events, managing registrations, and tracking attendee attendance.</p>
                <div class="button-container">
                    <a href="{{ route('organizer.dashboard') }}" class="button">Go to Dashboard</a>
                </div>
            @else
                <p>Explore upcoming events, register for your favorite ones, and manage all your tickets in one place.</p>
                <div class="button-container">
                    <a href="{{ route('home') }}" class="button">Explore Events</a>
                </div>
            @endif

            <p>If you have any questions, feel free to reply to this email.</p>
            
            <div class="footer">
                <p>Best regards,<br>
                <strong>The Evento Team</strong></p>
            </div>
        </div>
    </div>
</body>
</html>
