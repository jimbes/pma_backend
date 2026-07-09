<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PMA - Couples Medical Appointment & Medication Manager</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            text-align: center;
        }
        header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        .hero {
            text-align: center;
            padding: 3rem 0;
        }
        .hero h2 {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #667eea;
        }
        .hero p {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 2rem;
        }
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
        }
        .feature {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        .feature h3 {
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        .feature p {
            color: #666;
        }
        .cta {
            text-align: center;
            padding: 2rem 0;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        footer {
            background: #f8f9fa;
            color: #666;
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <header>
        <h1>PMA</h1>
        <p>Couples Medical Appointment & Medication Manager</p>
    </header>

    <div class="container">
        <div class="hero">
            <h2>Coordinated Healthcare for Couples</h2>
            <p>Manage appointments, medications, and treatment plans together. Stay synchronized with your partner every step of the way.</p>
        </div>

        <div class="features">
            <div class="feature">
                <h3>📅 Shared Appointments</h3>
                <p>View and manage medical appointments together. Get notifications at the right time for both partners.</p>
            </div>
            <div class="feature">
                <h3>💊 Medication Tracking</h3>
                <p>Never miss a dose. Track medications, schedules, and adherence with reminders for both partners.</p>
            </div>
            <div class="feature">
                <h3>👥 Partner Coordination</h3>
                <p>Invite your partner and access the same information. Stay coordinated on your treatment journey.</p>
            </div>
            <div class="feature">
                <h3>🔔 Smart Notifications</h3>
                <p>Choose who gets notified for what. Flexible notification settings for every event.</p>
            </div>
            <div class="feature">
                <h3>🔒 Secure & Private</h3>
                <p>Your health data is encrypted and secure. Only you and your partner have access.</p>
            </div>
            <div class="feature">
                <h3>📱 Mobile & Web</h3>
                <p>Access PMA on iOS, Android, or web. Sync automatically across all your devices.</p>
            </div>
        </div>

        <div class="cta">
            <p style="margin-bottom: 1.5rem; font-size: 1.1rem; color: #666;">
                Download the app or visit our mobile platform to get started.
            </p>
            <a href="#" class="btn">Get Started Today</a>
        </div>

    </div>

    <footer>
        <p>&copy; 2026 PMA - Couples Medical Appointment Manager. All rights reserved.</p>
        <p style="margin-top: 1rem; font-size: 0.9rem;">
            <a href="{{ route('privacy') }}" style="color: #667eea; text-decoration: none;">Politique de confidentialité</a>
            ·
            <a href="{{ route('account-deletion') }}" style="color: #667eea; text-decoration: none;">Suppression de compte</a>
        </p>
    </footer>
</body>
</html>
