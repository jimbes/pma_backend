<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Admin Dashboard</title>
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
        .back-link {
            display: inline-block;
            margin-bottom: 1.5rem;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        .section-title {
            padding: 1rem 1.5rem;
            background: #f8f9fa;
            border-bottom: 2px solid #ddd;
            font-weight: 600;
            color: #667eea;
            text-transform: uppercase;
            font-size: 0.85rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .filters a {
            display: inline-block;
            margin-left: 0.5rem;
            padding: 0.3rem 0.8rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            color: #666;
            background: #eee;
        }
        .filters a.active {
            background: #667eea;
            color: white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 600;
            color: #666;
            font-size: 0.8rem;
            text-transform: uppercase;
            border-bottom: 1px solid #eee;
        }
        td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #eee;
            font-size: 0.9rem;
            vertical-align: top;
        }
        tr:last-child td {
            border-bottom: none;
        }
        tr:hover {
            background: #f8f9fa;
        }
        .badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge.sent { background: #d4edda; color: #155724; }
        .badge.pending { background: #fff3cd; color: #856404; }
        .badge.failed { background: #f8d7da; color: #721c24; }
        .muted {
            color: #999;
        }
        .empty {
            padding: 2rem;
            text-align: center;
            color: #999;
        }
        code {
            background: #f0f0f0;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            font-size: 0.85rem;
        }
        .pagination {
            padding: 1rem 1.5rem;
            display: flex;
            gap: 0.5rem;
        }
        .pagination a, .pagination span {
            padding: 0.4rem 0.8rem;
            border-radius: 4px;
            text-decoration: none;
            color: #667eea;
            border: 1px solid #ddd;
            font-size: 0.85rem;
        }
        .pagination span.disabled {
            color: #ccc;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Notifications</h1>
        <p>Historique des rappels envoyés à tous les couples</p>
    </div>

    <div class="container">
        <a href="{{ route('admin.dashboard') }}" class="back-link">← Retour au tableau de bord</a>

        <div class="section">
            <div class="section-title">
                <span>{{ $notifications->total() }} notification(s)</span>
                <div class="filters">
                    <a href="{{ route('admin.notifications') }}" class="{{ $status ? '' : 'active' }}">Toutes</a>
                    <a href="{{ route('admin.notifications', ['status' => 'pending']) }}" class="{{ $status === 'pending' ? 'active' : '' }}">En attente</a>
                    <a href="{{ route('admin.notifications', ['status' => 'sent']) }}" class="{{ $status === 'sent' ? 'active' : '' }}">Envoyées</a>
                    <a href="{{ route('admin.notifications', ['status' => 'failed']) }}" class="{{ $status === 'failed' ? 'active' : '' }}">Échouées</a>
                </div>
            </div>
            @if($notifications->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Couple</th>
                        <th>Destinataire</th>
                        <th>Type</th>
                        <th>Canal</th>
                        <th>Prévue le</th>
                        <th>Statut</th>
                        <th>Envoyée le</th>
                        <th>Erreur</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notifications as $notification)
                    <tr>
                        <td><a href="{{ route('admin.couple-detail', $notification->couple_id) }}">Couple #{{ $notification->couple_id }}</a></td>
                        <td>{{ $notification->user->name ?? '-' }}</td>
                        <td>{{ $notification->type }}</td>
                        <td>{{ $notification->channel }}</td>
                        <td>{{ $notification->scheduled_for?->format('d/m/Y H:i') ?? '-' }}</td>
                        <td><span class="badge {{ $notification->status }}">{{ $notification->status }}</span></td>
                        <td>{{ $notification->sent_at?->format('d/m/Y H:i') ?? '-' }}</td>
                        <td class="muted">{{ $notification->failed_reason ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination">
                {{ $notifications->links() }}
            </div>
            @else
            <div class="empty">Aucune notification</div>
            @endif
        </div>
    </div>
</body>
</html>
