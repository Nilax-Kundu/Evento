<!DOCTYPE html>
<html>
<head>
    <title>Registration Confirmed</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-w-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #0f766e; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { padding: 20px; border: 1px solid #ddd; border-top: none; border-radius: 0 0 8px 8px; }
        .ticket { background: #f9fafb; border: 2px dashed #cbd5e1; padding: 15px; text-align: center; margin: 20px 0; font-family: monospace; font-size: 1.2em; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>You're going to {{ $registration->event->title }}!</h2>
        </div>
        <div class="content">
            <p>Hi {{ $registration->user->name }},</p>
            
            <p>Your registration for <strong>{{ $registration->event->title }}</strong> is confirmed. Below are your ticket details:</p>
            
            <ul>
                <li><strong>Date:</strong> {{ $registration->event->date->format('l, F j, Y') }}</li>
                <li><strong>Time:</strong> {{ $registration->event->time->format('g:i A') }}</li>
                <li><strong>Location:</strong> {{ $registration->event->location }}</li>
            </ul>

            <div class="ticket">
                TICKET ID: <br>
                <strong>{{ $registration->ticket_id }}</strong>
            </div>

            <p>Please keep this ticket ID handy for check-in at the event.</p>
            
            <p>Thanks,<br>
            Evento Team</p>
        </div>
    </div>
</body>
</html>
