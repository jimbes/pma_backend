<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc3545; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { border: 1px solid #ddd; padding: 20px; border-radius: 0 0 5px 5px; }
        .field { margin-bottom: 12px; }
        .label { font-weight: bold; color: #666; font-size: 12px; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Demande de suppression de compte</h2>
        </div>
        <div class="content">
            <div class="field">
                <div class="label">Compte concerné</div>
                <div>{{ $requesterEmail }}</div>
            </div>
            @if($message)
            <div class="field">
                <div class="label">Message</div>
                <div>{{ $message }}</div>
            </div>
            @endif
            <p style="margin-top: 24px; font-size: 13px; color: #666;">
                Soumis depuis le formulaire public de suppression de compte LTMO.
                Vérifiez l'identité du demandeur puis supprimez le compte et le couple
                associé depuis l'espace d'administration.
            </p>
        </div>
    </div>
</body>
</html>
