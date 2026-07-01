<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Couple #{{ $couple->id }} - Admin Dashboard</title>
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
        .badge.done { background: #d4edda; color: #155724; }
        .badge.in_progress { background: #fff3cd; color: #856404; }
        .badge.upcoming { background: #e2e3e5; color: #383d41; }
        .badge.yes { background: #d4edda; color: #155724; }
        .badge.no { background: #f8d7da; color: #721c24; }
        .muted {
            color: #999;
        }
        .empty {
            padding: 2rem;
            text-align: center;
            color: #999;
        }
        .schedule-row {
            background: #fafafa;
            font-size: 0.85rem;
        }
        code {
            background: #f0f0f0;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            font-size: 0.85rem;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Couple #{{ $couple->id }}</h1>
        <p>Créé le {{ $couple->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="container">
        <a href="{{ route('admin.couples') }}" class="back-link">← Retour aux couples</a>

        @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
        <div class="alert error">{{ session('error') }}</div>
        @endif

        <!-- Members -->
        <div class="section">
            <div class="section-title">Membres ({{ $couple->users->count() }})</div>
            @if($couple->users->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Inscrit le</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($couple->users as $member)
                    <tr>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->email }}</td>
                        <td>{{ $member->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty">Aucun membre</div>
            @endif
        </div>

        <!-- Journey stages -->
        <div class="section">
            <div class="section-title">Parcours FIV ({{ $couple->journeyStages->count() }} étapes)</div>
            @if($couple->journeyStages->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>Statut</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Durée</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($couple->journeyStages as $stage)
                    <tr>
                        <td>{{ $stage->order }}</td>
                        <td>{{ $stage->type }}</td>
                        <td><span class="badge {{ $stage->status }}">{{ $stage->status }}</span></td>
                        <td>{{ $stage->start_date->format('d/m/Y') }}</td>
                        <td>{{ $stage->end_date ? $stage->end_date->format('d/m/Y') : '-' }}</td>
                        <td>{{ $stage->duration_days ? $stage->duration_days . ' j' : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty">Aucune étape configurée</div>
            @endif
        </div>

        <!-- Medications -->
        <div class="section">
            <div class="section-title">Médicaments ({{ $couple->medications->count() }})</div>
            @if($couple->medications->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Dosage</th>
                        <th>Forme</th>
                        <th>Actif</th>
                        <th>Horaires</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($couple->medications as $medication)
                    <tr>
                        <td>{{ $medication->name }}</td>
                        <td>{{ $medication->dosage }} {{ $medication->unit }}</td>
                        <td>{{ $medication->form ?? '-' }}</td>
                        <td><span class="badge {{ $medication->active ? 'yes' : 'no' }}">{{ $medication->active ? 'Oui' : 'Non' }}</span></td>
                        <td>{{ $medication->schedules->count() }} horaire(s)</td>
                    </tr>
                    @if($medication->schedules->count() > 0)
                    <tr class="schedule-row">
                        <td colspan="5" style="padding-left: 2rem;">
                            @foreach($medication->schedules as $schedule)
                            <div style="margin-bottom: 0.5rem;">
                                <code>{{ $schedule->frequency }}</code>
                                @if($schedule->frequency === 'specific_days' && $schedule->days_of_week)
                                    jours: {{ implode(',', $schedule->days_of_week) }}
                                @endif
                                · heures: {{ implode(', ', $schedule->reminder_times ?? []) }}
                                · du {{ $schedule->start_date->format('d/m/Y') }}
                                @if($schedule->end_date) au {{ $schedule->end_date->format('d/m/Y') }} @endif
                                @if($schedule->journeyStage)
                                    · lié à l'étape <strong>{{ $schedule->journeyStage->type }}</strong>
                                @endif
                            </div>
                            @endforeach
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty">Aucun médicament</div>
            @endif
        </div>

        <!-- Appointments -->
        <div class="section">
            <div class="section-title">Rendez-vous ({{ $couple->appointments->count() }})</div>
            @if($couple->appointments->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Lieu</th>
                        <th>Terminé</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($couple->appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->title }}</td>
                        <td>{{ $appointment->type ?? '-' }}</td>
                        <td>{{ $appointment->appointment_date->format('d/m/Y') }}</td>
                        <td>{{ $appointment->appointment_time ?? '-' }}</td>
                        <td>{{ $appointment->location ?? '-' }}</td>
                        <td><span class="badge {{ $appointment->completed ? 'yes' : 'no' }}">{{ $appointment->completed ? 'Oui' : 'Non' }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty">Aucun rendez-vous</div>
            @endif
        </div>

        <!-- Invitations -->
        <div class="section">
            <div class="section-title">Invitations ({{ $couple->invitations->count() }})</div>
            @if($couple->invitations->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Email invité</th>
                        <th>Statut</th>
                        <th>Expire le</th>
                        <th>Token</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($couple->invitations as $invitation)
                    <tr>
                        <td>{{ $invitation->invitee_email }}</td>
                        <td>
                            @if($invitation->accepted)
                                <span class="badge yes">Acceptée</span>
                            @elseif($invitation->expires_at->isPast())
                                <span class="badge no">Expirée</span>
                            @else
                                <span class="badge in_progress">En attente</span>
                            @endif
                        </td>
                        <td>{{ $invitation->expires_at->format('d/m/Y H:i') }}</td>
                        <td><code>{{ $invitation->token }}</code></td>
                        <td>
                            <form action="{{ route('admin.delete-invitation', $invitation) }}" method="POST" onsubmit="return confirm('Supprimer cette invitation ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn danger" style="padding: 0.35rem 0.8rem; font-size: 0.8rem; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty">Aucune invitation</div>
            @endif
        </div>
    </div>
</body>
</html>
