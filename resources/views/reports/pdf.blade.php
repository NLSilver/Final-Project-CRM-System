<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; color: #374151; font-size: 12px; }
        .grid { width: 100%; border-collapse: separate; border-spacing: 10px; }
        .card { border: 1px solid #e5e7eb; padding: 15px; border-radius: 8px; text-align: center; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th { background: #f9fafb; padding: 10px; text-align: left; text-transform: uppercase; font-size: 9px; }
        .table td { border-bottom: 1px solid #f3f4f6; padding: 10px; }
        .badge { padding: 3px 6px; border-radius: 99px; font-size: 8px; font-weight: bold; text-transform: uppercase; }
    </style>
</head>
<body>
    <h1>Performance Report</h1>
    <p><strong>Staff Filter:</strong> {{ request('staff_id') ? 'Specific Staff' : 'All Staff' }}</p>
    <p><strong>Date Range:</strong> {{ $startDate ?? 'All' }} to {{ $endDate ?? 'All' }}</p>

    <table class="grid">
        <tr>
            <td class="card">Customers<br><strong>{{ $data['totalCustomers'] }}</strong></td>
            <td class="card">Total Leads<br><strong>{{ $data['totalLeads'] }}</strong></td>
            <td class="card">Completed<br><strong>{{ $data['completedFollowUps'] }}</strong></td>
            <td class="card">Pending<br><strong>{{ $data['pendingFollowUps'] }}</strong></td>
        </tr>
    </table>

    <h3>Pipeline Distribution</h3>
    <table class="grid">
        <tr>
            @foreach(['New' => '#3b82f6', 'Contacted' => '#f97316', 'Qualified' => '#14b8a6', 'Proposal Sent' => '#6366f1', 'Negotiation' => '#a855f7', 'Won' => '#22c55e', 'Lost' => '#ef4444'] as $status => $color)
                <td class="card" style="background-color: {{ $color }}; color: white;">
                    {{ $status }}<br><strong>{{ $data['pipeline'][$status] ?? 0 }}</strong>
                </td>
            @endforeach
        </tr>
    </table>

    <h3>Detailed Activity Logs</h3>
    <table class="table">
        <thead><tr><th>Staff</th><th>Type</th><th>Details</th><th>Time</th></tr></thead>
        <tbody>
            @foreach($data['activities'] as $a)
            <tr>
                <td>{{ $a->user->name ?? 'System' }}</td>
                <td>{{ $a->activity_type }}</td>
                <td>{{ $a->description }}</td>
                <td>{{ $a->created_at->format('M d, H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Recently Completed Follow-ups</h3>
<table class="table">
    <thead>
        <tr><th>Staff</th><th>Task Details</th><th>Status</th><th>Completed At</th></tr>
    </thead>
    <tbody>
        @foreach($data['completedFollowUpDetails'] ?? [] as $f)
        <tr>
            <td>{{ $f->user->name ?? 'N/A' }}</td>
            <td><strong>{{ $f->title }}</strong><br>{{ $f->description }}</td>
            <td>
                @if($f->updated_at <= $f->due_date)
                    <span class="badge" style="background: #dcfce7; color: #166534;">On Time</span>
                @else
                    <span class="badge" style="background: #fee2e2; color: #991b1b;">Overdue</span>
                @endif
            </td>
            <td style="font-size: 10px; color: #6b7280;">
                {{ $f->updated_at->format('M d, Y - H:i') }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>