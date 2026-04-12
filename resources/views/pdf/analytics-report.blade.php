<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $reportName }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10pt;
            color: #000;
            background: #fff;
        }
        .header {
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 20pt;
            color: #1f2937;
            margin-bottom: 5px;
        }
        .header .meta {
            font-size: 9pt;
            color: #6b7280;
        }
        .kpi-grid {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: separate;
            border-spacing: 8px;
        }
        .kpi-card {
            border: 1px solid #d1d5db;
            border-radius: 4px;
            padding: 10px;
            background: #fff;
            width: 25%;
            vertical-align: top;
        }
        .kpi-card .label {
            font-size: 8pt;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 5px;
        }
        .kpi-card .value {
            font-size: 19pt;
            font-weight: bold;
            color: #111827;
        }
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .section h2 {
            font-size: 12pt;
            color: #374151;
            margin-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        .chart-container {
            text-align: center;
            margin: 10px 0;
        }
        .chart-image {
            width: 100%;
            max-height: 280px;
            object-fit: contain;
        }
        .chart-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px;
            margin-bottom: 20px;
        }
        .chart-cell {
            width: 50%;
            vertical-align: top;
            page-break-inside: avoid;
        }
        .footer {
            margin-top: 26px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            font-size: 8pt;
            color: #6b7280;
            text-align: center;
        }
        @page {
            size: A4 landscape;
            margin: 1cm;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $reportName }}</h1>
        <div class="meta">
            Genere le {{ $date }}
            @if($dateFrom && $dateTo)
                | Periode: {{ $dateFrom }} - {{ $dateTo }}
            @endif
        </div>
    </div>

    <table class="kpi-grid">
        <tr>
            <td class="kpi-card">
                <div class="label">Total Formateurs</div>
                <div class="value">{{ $metrics['totalFormateurs'] ?? 0 }}</div>
            </td>
            <td class="kpi-card">
                <div class="label">Total Groupes</div>
                <div class="value">{{ $metrics['totalGroupes'] ?? 0 }}</div>
            </td>
            <td class="kpi-card">
                <div class="label">Modules Actifs</div>
                <div class="value">{{ $metrics['modulesActifs'] ?? 0 }} / {{ $metrics['totalModules'] ?? 0 }}</div>
            </td>
            <td class="kpi-card">
                <div class="label">Taux d'Absence</div>
                <div class="value">{{ $metrics['tauxAbsence'] ?? 0 }}%</div>
            </td>
        </tr>
    </table>

    <div class="section">
        <h2>Avancement par Groupe</h2>
        <div class="chart-container">
            <img src="{{ $chartImages['mainChart'] }}" class="chart-image" alt="Avancement par Groupe">
        </div>
    </div>

    <table class="chart-grid">
        <tr>
            <td class="chart-cell">
                <div class="section">
                    <h2>Progression des Modules</h2>
                    <div class="chart-container">
                        <img src="{{ $chartImages['moduleChart'] }}" class="chart-image" alt="Progression des Modules">
                    </div>
                </div>
            </td>
            <td class="chart-cell">
                <div class="section">
                    <h2>Tendance des Absences</h2>
                    <div class="chart-container">
                        <img src="{{ $chartImages['absenceChart'] }}" class="chart-image" alt="Tendance des Absences">
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div class="section">
        <h2>Charge de Travail</h2>
        <div class="chart-container">
            <img src="{{ $chartImages['workloadChart'] }}" class="chart-image" alt="Charge de Travail" style="max-height: 220px;">
        </div>
    </div>

    <div class="footer">
        eDT Pro - Rapport Analytique | {{ $reportType }}
    </div>
</body>
</html>
