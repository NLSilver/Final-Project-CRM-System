<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #1f2937; line-height: 1.5; margin: 0; padding: 0; }
        .header { background-color: #0d9488; color: white; padding: 30px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 22px; text-transform: uppercase; letter-spacing: 1px; }
        .header p { margin: 5px 0 0; opacity: 0.8; font-size: 11px; }
        
        .container { padding: 0 30px; }
        
        /* Stats Grid with Borders */
        .stats-table { width: 100%; border-collapse: separate; border-spacing: 10px 0; margin-bottom: 30px; }
        .stat-card { 
            border: 1.5px solid #e2e8f0; 
            padding: 12px; 
            border-radius: 10px; 
            text-align: center; 
            width: 25%;
            background-color: #ffffff;
        }
        .stat-val { display: block; font-size: 22px; font-weight: bold; color: #0d9488; margin-bottom: 2px; }
        .stat-label { font-size: 9px; text-transform: uppercase; color: #64748b; font-weight: bold; letter-spacing: 0.5px; }

        h3 { border-bottom: 2px solid #f1f5f9; padding-bottom: 5px; font-size: 13px; text-transform: uppercase; color: #334155; margin-top: 25px; margin-bottom: 15px; }
        
        /* Pipeline Grid Layout */
        .pipeline-container { width: 100%; border-collapse: separate; border-spacing: 5px; margin-bottom: 20px; }
        .pipeline-cell { 
            padding: 8px 4px; 
            color: white; 
            font-size: 10px; 
            text-align: center; 
            border-radius: 6px;
            width: 14.28%; /* 7 columns */
        }
        .pipeline-count { display: block; font-size: 14px; font-weight: bold; margin-top: 2px; }

        /* Data Tables */
        .data-table { width: 100%; border-collapse: collapse; font-size: 10px; margin-bottom: 20px; }
        .data-table th { background: #f8fafc; color: #475569; text-align: left; padding: 8px 10px; text-transform: uppercase; border-bottom: 2px solid #e2e8f0; }
        .data-table td { padding: 8px 10px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        
        .badge { padding: 2px 6px; border-radius: 4px; font-weight: bold; font-size: 8px; text-transform: uppercase; }
        .badge-ontime { background: #dcfce7; color: #166534; }
        .badge-overdue { background: #fee2e2; color: #991b1b; }
        
        .footer { position: fixed; bottom: 20px; left: 30px; right: 30px; font-size: 8px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Performance Report</h1>
        <p>Staff: {{ $staffId ? 'User ID #'.$staffId : 'All Staff Members' }}</p>
        <p>Date Range: {{ $startDate ?? 'Beginning' }} — {{ $endDate ?? 'Present' }}</p>
    </div>

    <div class="container">
        <table class="stats-table">
            <tr>
                <td class="stat-card">
                    <span class="stat-val">{{ $data['totalCustomers'] }}</span>
                    <span class="stat-label">Customers</span>
                </td>
                <td class="stat-card">
                    <span class="stat-val">{{ $data['totalLeads'] }}</span>
                    <span class="stat-label">Total Leads</span>
                </td>
                <td class="stat-card">
                    <span class="stat-val">{{ $data['completedFollowUps'] }}</span>
                    <span class="stat-label">Done Tasks</span>
                </td>
                <td class="stat-card">
                    <span class="stat-val">{{ $data['pendingFollowUps'] }}</span>
                    <span class="stat-label">Pending</span>
                </td>
            </tr>
        </table>

        <h3>Lead Pipeline Distribution</h3>
        <table class="pipeline-container">
            <tr>
                @php
                    $statuses = [
                        'New' => '#3b82f6', 
                        'Contacted' => '#f97316', 
                        'Qualified' => '#14b8a6', 
                        'Proposal Sent' => '#6366f1', 
                        'Negotiation' => '#a855f7', 
                        'Won' => '#22c55e', 
                        'Lost' => '#ef4444'
                    ];
                @endphp
                @foreach($statuses as $status => $color)
                <td class="pipeline-cell" style="background-color: {{ $color }};">
                    <div style="opacity: 0.9;">{{ $status === 'Proposal Sent' ? 'Proposal' : $status }}</div>
                    <span class="pipeline-count">{{ $data['pipeline'][$status] ?? 0 }}</span>
                </td>
                @endforeach
            </tr>
        </table>

        <h3>Detailed Activity Logs</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="15%">Staff</th>
                    <th width="12%">Type</th>
                    <th>Action Details</th>
                    <th width="15%">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['activities'] as $a)
                <tr>
                    <td><strong>{{ $a->user->name ?? 'System' }}</strong></td>
                    <td><span style="color: #64748b;">{{ strtoupper($a->activity_type) }}</span></td>
                    <td>
                        {{ $a->description }}<br>
                        <small style="color: #94a3b8 italic;">
                            {{ $a->lead ? 'Lead: '.$a->lead->name : ($a->customer ? 'Customer: '.$a->customer->first_name : '') }}
                        </small>
                    </td>
                    <td>{{ $a->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h3>Completed Follow-ups</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="20%">Staff</th>
                    <th>Task & Description</th>
                    <th width="15%">Outcome</th>
                    <th width="15%">Completed</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['completedFollowUpDetails'] as $f)
                <tr>
                    <td><strong>{{ $f->user->name ?? 'N/A' }}</strong></td>
                    <td>
                        <strong>{{ $f->title }}</strong><br>
                        <span style="color: #64748b;">{{ $f->description }}</span>
                    </td>
                    <td>
                        @if($f->updated_at <= $f->due_date)
                            <span class="badge badge-ontime">On-Time</span>
                        @else
                            <span class="badge badge-overdue">Overdue</span>
                        @endif
                    </td>
                    <td>{{ $f->updated_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        NullCRM Internal Performance Report — {{ date('Y-m-d H:i') }}
    </div>
</body>
</html>