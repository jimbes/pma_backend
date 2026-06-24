<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Dashboard</title>
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
        }
        tr:hover {
            background: #f8f9fa;
        }
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            font-size: 0.85rem;
            transition: background 0.2s;
        }
        .btn:hover {
            background: #764ba2;
        }
        .btn.danger {
            background: #dc3545;
        }
        .btn.danger:hover {
            background: #c82333;
        }
        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
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
        <h1>Manage Users</h1>
        <p>View all registered users and manage their accounts</p>
    </div>

    <div class="container">
        <a href="{{ route('admin.dashboard') }}" class="back-link">← Back to Dashboard</a>

        @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
        <div class="alert error">{{ session('error') }}</div>
        @endif

        @if($users->count() > 0)
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Partner</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->couple && $user->couple->users()->where('id', '!=', $user->id)->first())
                                {{ $user->couple->users()->where('id', '!=', $user->id)->first()->name }}
                            @else
                                <span style="color: #999;">-</span>
                            @endif
                        </td>
                        <td><span class="date">{{ $user->created_at->format('M d, Y') }}</span></td>
                        <td>
                            <form action="{{ route('admin.delete-user', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure? This will delete all user data (GDPR compliant).');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="table-container">
            <div class="empty">
                <p>No users found.</p>
            </div>
        </div>
        @endif
    </div>
</body>
</html>
