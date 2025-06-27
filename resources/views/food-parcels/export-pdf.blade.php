<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Parcels Report - {{ date('Y-m-d') }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3B82F6;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #1E40AF;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0 0 0;
            color: #6B7280;
            font-size: 14px;
        }

        .statistics {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .stat-row {
            display: table-row;
        }

        .stat-cell {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 15px;
            background: #F3F4F6;
            border: 1px solid #E5E7EB;
        }

        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #1E40AF;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 11px;
            color: #6B7280;
            text-transform: uppercase;
        }

        .table-container {
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #E5E7EB;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #F9FAFB;
            font-weight: bold;
            color: #374151;
            font-size: 11px;
            text-transform: uppercase;
        }

        td {
            font-size: 10px;
        }

        .status-active {
            color: #059669;
            font-weight: bold;
        }

        .status-inactive {
            color: #DC2626;
            font-weight: bold;
        }

        .expiry-warning {
            color: #D97706;
            font-weight: bold;
        }

        .expiry-danger {
            color: #DC2626;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            font-size: 10px;
            color: #6B7280;
        }

        .page-break {
            page-break-after: always;
        }

        @media print {
            body {
                margin: 0;
                padding: 15px;
            }

            .header {
                margin-bottom: 20px;
            }

            .no-print {
                display: none;
            }
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #6B7280;
            font-style: italic;
        }
    </style>
</head>
<body>
    <!-- Print Button (hidden in print mode) -->
    <div class="no-print" style="text-align: right; margin-bottom: 20px;">
        <button onclick="window.print()" style="background: #3B82F6; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
            Print Report
        </button>
    </div>

    <!-- Header -->
    <div class="header">
        <h1>Food Parcels Report</h1>
        <p>Generated on {{ date('F d, Y \a\t H:i:s') }}</p>
        <p>Voedselbank Maaskantje</p>
    </div>

    <!-- Statistics -->
    <div class="statistics">
        <div class="stat-row">
            <div class="stat-cell">
                <div class="stat-value">{{ $statistics->total ?? 0 }}</div>
                <div class="stat-label">Total Parcels</div>
            </div>
            <div class="stat-cell">
                <div class="stat-value">{{ $statistics->active ?? 0 }}</div>
                <div class="stat-label">Active Parcels</div>
            </div>
            <div class="stat-cell">
                <div class="stat-value">{{ $statistics->inactive ?? 0 }}</div>
                <div class="stat-label">Inactive Parcels</div>
            </div>
            <div class="stat-cell">
                <div class="stat-value">{{ $statistics->this_month ?? 0 }}</div>
                <div class="stat-label">This Month</div>
            </div>
        </div>
    </div>

    <!-- Food Parcels Table -->
    <div class="table-container">
        @if($foodParcels && $foodParcels->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 8%;">ID</th>
                        <th style="width: 20%;">Customer</th>
                        <th style="width: 15%;">Contact</th>
                        <th style="width: 15%;">Stock Item</th>
                        <th style="width: 10%;">Category</th>
                        <th style="width: 8%;">Qty</th>
                        <th style="width: 8%;">Status</th>
                        <th style="width: 10%;">Expiry</th>
                        <th style="width: 6%;">Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($foodParcels as $parcel)
                        <tr>
                            <td>#{{ $parcel->id }}</td>
                            <td>{{ $parcel->customer_name ?? 'N/A' }}</td>
                            <td>
                                @if($parcel->customer_email)
                                    {{ $parcel->customer_email }}<br>
                                @endif
                                @if($parcel->customer_phone)
                                    {{ $parcel->customer_phone }}
                                @endif
                            </td>
                            <td>{{ $parcel->product_name ?? 'N/A' }}</td>
                            <td>{{ $parcel->category_name ?? 'N/A' }}</td>
                            <td>{{ $parcel->stock_quantity ?? 0 }}</td>
                            <td>
                                @if($parcel->is_active)
                                    <span class="status-active">Active</span>
                                @else
                                    <span class="status-inactive">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @if($parcel->stock_expiry_date)
                                    @php
                                        $expiryDate = \Carbon\Carbon::parse($parcel->stock_expiry_date);
                                        $isExpired = $expiryDate->isPast();
                                        $isExpiringSoon = $expiryDate->diffInDays() <= 7 && !$isExpired;
                                    @endphp

                                    <span class="@if($isExpired) expiry-danger @elseif($isExpiringSoon) expiry-warning @endif">
                                        {{ $expiryDate->format('M d, Y') }}
                                    </span>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($parcel->created_at)->format('M d') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">
                <p>No food parcels found matching the current criteria.</p>
            </div>
        @endif
    </div>

    <!-- Additional Details Section -->
    @if($foodParcels && $foodParcels->count() > 0)
        <div class="page-break"></div>

        <div class="header">
            <h1>Detailed Food Parcels Information</h1>
        </div>

        @foreach($foodParcels as $index => $parcel)
            @if($index > 0 && $index % 5 == 0)
                <div class="page-break"></div>
            @endif

            <div style="margin-bottom: 25px; border: 1px solid #E5E7EB; padding: 15px; border-radius: 5px;">
                <h3 style="margin: 0 0 10px 0; color: #1E40AF;">Food Parcel #{{ $parcel->id }}</h3>

                <table style="width: 100%; margin: 0;">
                    <tr>
                        <td style="width: 30%; border: none; padding: 5px 10px 5px 0;"><strong>Customer:</strong></td>
                        <td style="border: none; padding: 5px 0;">{{ $parcel->customer_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 5px 10px 5px 0;"><strong>Email:</strong></td>
                        <td style="border: none; padding: 5px 0;">{{ $parcel->customer_email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 5px 10px 5px 0;"><strong>Phone:</strong></td>
                        <td style="border: none; padding: 5px 0;">{{ $parcel->customer_phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 5px 10px 5px 0;"><strong>Stock Item:</strong></td>
                        <td style="border: none; padding: 5px 0;">{{ $parcel->product_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 5px 10px 5px 0;"><strong>Category:</strong></td>
                        <td style="border: none; padding: 5px 0;">{{ $parcel->category_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 5px 10px 5px 0;"><strong>Quantity:</strong></td>
                        <td style="border: none; padding: 5px 0;">{{ $parcel->stock_quantity ?? 0 }} units</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 5px 10px 5px 0;"><strong>Status:</strong></td>
                        <td style="border: none; padding: 5px 0;">
                            @if($parcel->is_active)
                                <span class="status-active">Active</span>
                            @else
                                <span class="status-inactive">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 5px 10px 5px 0;"><strong>Created:</strong></td>
                        <td style="border: none; padding: 5px 0;">{{ \Carbon\Carbon::parse($parcel->created_at)->format('F d, Y \a\t H:i:s') }}</td>
                    </tr>
                    @if($parcel->note)
                        <tr>
                            <td style="border: none; padding: 5px 10px 5px 0; vertical-align: top;"><strong>Notes:</strong></td>
                            <td style="border: none; padding: 5px 0;">{{ $parcel->note }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        @endforeach
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>
            This report contains {{ $foodParcels ? $foodParcels->count() : 0 }} food parcel records.<br>
            Generated by Voedselbank Maaskantje Management System<br>
            © {{ date('Y') }} All rights reserved.
        </p>
    </div>

    <script>
        // Auto-print when page loads (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
