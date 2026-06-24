<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2196F3; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { border: 1px solid #ddd; padding: 20px; border-radius: 0 0 5px 5px; }
        .button { display: inline-block; background: #2196F3; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Invitation à rejoindre PMA</h2>
        </div>
        <div class="content">
            <p>Bonjour,</p>
            <p>Vous avez été invité à rejoindre PMA - Couples Medical Manager par votre partenaire.</p>
            <p>Cliquez sur le bouton ci-dessous pour accepter l'invitation et créer votre compte :</p>
            <p>
                <a href="{{ $acceptLink }}" class="button">Accepter l'invitation</a>
            </p>
            <p>Cette invitation expire le <strong>{{ $invitation->expires_at->format('d/m/Y') }}</strong></p>
            <p style="margin-top: 30px; font-size: 12px; color: #666;">
                Si vous n'avez pas demandé cette invitation, veuillez ignorer cet email.
            </p>
        </div>
        <div class="footer">
            <p>PMA - Couples Medical Manager</p>
        </div>
    </div>
</body>
</html>
