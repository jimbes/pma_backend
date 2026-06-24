<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4CAF50; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { border: 1px solid #ddd; padding: 20px; border-radius: 0 0 5px 5px; }
        .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ $notification->subject }}</h2>
        </div>
        <div class="content">
            <p>{{ $notification->message }}</p>
            <p style="margin-top: 30px;">
                <strong>PMA - Couples Medical Manager</strong>
            </p>
        </div>
        <div class="footer">
            <p>This is an automated notification from PMA. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
