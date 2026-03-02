<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Notification - CuniApp Élevage</title>
    <style>
        /* Same professional email styling as verification email */
        /* Add notification-specific styles with colored header based on type */
        .notification-header { border-left: 4px solid {{ $color }}; padding-left: 16px; }
        .notification-icon { font-size: 28px; color: {{ $color }}; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <div class="logo">...</div>
            <h1>CuniApp Élevage</h1>
            <p>Gestion intelligente de votre cheptel</p>
        </div>
        
        <div class="email-body">
            <p class="greeting">Bonjour,</p>
            
            <div class="notification-card">
                <div class="notification-header">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                        <i class="bi {{ $notification->icon }} notification-icon"></i>
                        <h2 style="color: #1f2937; margin: 0; font-size: 20px;">{{ $notification->title }}</h2>
                    </div>
                    <p style="color: #4b5563; line-height: 1.6; margin-bottom: 24px;">{{ $notification->message }}</p>
                    
                    @if($notification->action_url)
                    <div class="button-container">
                        <a href="{{ $notification->action_url }}" class="cta-button">
                            Voir les détails
                        </a>
                    </div>
                    @endif
                    
                    <div style="background: #f9fafb; border-radius: 8px; padding: 16px; margin-top: 24px; font-size: 13px; color: #6b7280;">
                        <p style="margin: 0; display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-clock-history"></i>
                            <span>Cette notification a été générée le {{ $notification->created_at->format('d/m/Y à H:i') }}</span>
                        </p>
                    </div>
                </div>
            </div>
            
            <p class="message">Vous recevez cet email car vous avez activé les notifications par email dans vos paramètres CuniApp.</p>
        </div>
        
        <div class="footer">...</div>
    </div>
</body>
</html>