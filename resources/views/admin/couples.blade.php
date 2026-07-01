<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Couples - Admin Dashboard</title>
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
        .table-container {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead {
            background: #f8f9fa;
            border-bottom: 2px solid #ddd;
        }
        th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #667eea;
            text-transform: uppercase;
            font-size: 0.85rem;
        }
        td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }
        tr:hover {
            background: #f8f9fa;
        }
        .member {
            display: block;
            margin-bottom: 0.25rem;
        }
        .member .email {
            color: #999;
            font-size: 0.85rem;
        }
        .badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-right: 0.4rem;
        }
        .badge.solo {
            background: #fff3cd;
            color: #856404;
        }
        .badge.paired {
            background: #d4edda;
            color: #155724;
        }
        .counts span {
            display: inline-block;
            margin-right: 0.75rem;
            font-size: 0.85rem;
            color: #666;
        }
        .counts strong {
            color: #333;
        }
        .empty {
            text-align: center;
            padding: 3rem;
            color: #999;
        }
        .date {
            font-size: 0.9rem;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Manage Couples</h1>
        <p>View all couples, their members, and how much data each holds</p>
    </div>

    <div class="container">
        <a href="{{ route('admin.dashboard') }}" class="back-link">← Back to Dashboard</a>

        @if($couples->count() > 0)
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Members</th>
                        <th>Data</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($couples as $couple)
                    <tr>
                        <td>
                            @if($couple->users->count() >= 2)
                                <span class="badge paired">Paired</span>
                            @elseif($couple->users->count() === 1)
                                <span class="badge solo">Solo</span>
                            @else
                                <span class="badge solo">Empty</span>
                            @endif
                        </td>
                        <td>
                            @forelse($couple->users as $member)
                                <span class="member">
                                    {{ $member->name }}
                                    <span class="email">({{ $member->email }})</span>
                                </span>
                            @empty
                                <span style="color: #999;">No members</span>
                            @endforelse
                        </td>
                        <td class="counts">
                            <span><strong>{{ $couple->appointments_count }}</strong> RDV</span>
                            <span><strong>{{ $couple->medications_count }}</strong> médicaments</span>
                            <span><strong>{{ $couple->journey_stages_count }}</strong> étapes</span>
                        </td>
                        <td><span class="date">{{ $couple->created_at->format('M d, Y') }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="table-container">
            <div class="empty">
                <p>No couples found.</p>
            </div>
        </div>
        @endif
    </div>
</body>
</html>
