<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Passport Manifest</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #10b981;
        }
        .header h1 {
            font-size: 18px;
            color: #10b981;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px 6px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #1e293b;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.5px;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        tr:hover {
            background-color: #f1f5f9;
        }
        .passport-number {
            font-family: monospace;
            color: #10b981;
            font-weight: bold;
        }
        .gender-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }
        .gender-m {
            background-color: #dbeafe;
            color: #2563eb;
        }
        .gender-f {
            background-color: #fce7f3;
            color: #db2777;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 9px;
        }
        .total {
            margin-top: 15px;
            text-align: right;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Passport Data Manifest</h1>
        <p>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>S/N</th>
                <th>Given Names</th>
                <th>Last Name</th>
                <th>Gender</th>
                <th>Date of Birth</th>
                <th>LGA</th>
                <th>Nationality</th>
                <th>Passport No.</th>
                <th>Document Number</th>
                <th>Document Expiry Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($passports as $index => $passport)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $passport->givennames }}</td>
                <td>{{ $passport->lastname }}</td>
                <td>
                    <span class="gender-badge {{ $passport->gender === 'M' ? 'gender-m' : 'gender-f' }}">
                        {{ $passport->gender }}
                    </span>
                </td>
                <td>{{ $passport->date_of_birth }}</td>
                <td>{{ $passport->lga }}</td>
                <td>{{ $passport->nationality }}</td>
                <td class="passport-number">{{ $passport->passport_number }}</td>
                <td class="passport-number">{{ $passport->document_number }}</td>
                <td class="passport-number">{{ $passport->document_expiry_date }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total Records: {{ $passports->count() }}
    </div>

    <div class="footer">
        Passport Data Capture Application &bull; Confidential Document
    </div>
</body>
</html>
