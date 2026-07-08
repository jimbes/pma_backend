<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PMA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f5f5;
            color: #333;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header h1 {
            margin-bottom: 0.5rem;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .card h3 {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            margin-bottom: 1rem;
            letter-spacing: 1px;
        }
        .card .number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #667eea;
        }
        .actions {
            margin-top: 2rem;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
            margin-right: 1rem;
        }
        .btn:hover {
            background: #764ba2;
        }
        .btn.secondary {
            background: #6c757d;
        }
        .btn.secondary:hover {
            background: #5a6268;
        }
        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 2rem;
        }
        .alert.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Admin Dashboard</h1>
        <p>Manage PMA users and view key metrics</p>
    </div>

    <div class="container">
        @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
        <div class="alert error">{{ session('error') }}</div>
        @endif

        <div class="dashboard-grid">
            <div class="card">
                <h3>Total Users</h3>
                <div class="number">{{ $totalUsers }}</div>
            </div>
            <div class="card">
                <h3>Total Couples</h3>
                <div class="number">{{ $totalCouples }}</div>
            </div>
            <div class="card">
                <h3>Active Couples</h3>
                <div class="number">{{ $activeCouples }}</div>
            </div>
            <div class="card">
                <h3>Pending Notifications</h3>
                <div class="number">{{ $pendingNotifications }}</div>
            </div>
            <div class="card">
                <h3>Failed Notifications</h3>
                <div class="number">{{ $failedNotifications }}</div>
            </div>
        </div>

        <div class="actions">
            <a href="{{ route('admin.users') }}" class="btn">Manage Users</a>
            <a href="{{ route('admin.couples') }}" class="btn">Manage Couples</a>
            <a href="{{ route('admin.notifications') }}" class="btn">Notifications</a>
            <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn secondary">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>
