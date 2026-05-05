<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; padding: 0; }
        .header { background: #0f766e; color: white; padding: 30px 20px; text-align: center; border-radius: 12px 12px 0 0; }
        .content { padding: 30px; border: 1px solid #e2e8f0; border-top: none; border-radius: 0 0 12px 12px; background: white; }
        .button-container { text-align: center; margin: 30px 0; }
        .button { background: #0f766e; color: white !important; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block; }
        .footer { margin-top: 30px; font-size: 14px; color: #64748b; border-top: 1px solid #f1f5f9; pt-20: 20px; }
        .trouble { font-size: 12px; color: #94a3b8; margin-top: 25px; word-break: break-all; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin:0;">Reset Your Password</h2>
        </div>
        <div class="content">
            <p>Hello {{ $name }},</p>
            
            <p>You are receiving this email because we received a password reset request for your account.</p>
            
            <div class="button-container">
                <a href="{{ $url }}" class="button">Reset Password</a>
            </div>

            <p>This password reset link will expire in {{ $count }} minutes.</p>
            
            <p>If you did not request a password reset, no further action is required.</p>
            
            <div class="footer">
                <p>Thanks,<br>
                <strong>Evento Team</strong></p>
            </div>

            <div class="trouble">
                <p>If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser: <br>
                {{ $url }}</p>
            </div>
        </div>
    </div>
</body>
</html>
