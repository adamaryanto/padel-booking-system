<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kuitansi Resmi #{{ $booking->id }}</title>
    <style>
        @page {
            margin: 0.5cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 40px;
            background: #fff;
        }
        .header {
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header table {
            width: 100%;
        }
        .brand-section {
            text-align: left;
        }
        .brand-name {
            font-size: 28px;
            font-weight: bold;
            color: #000;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .company-info {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }
        .invoice-title {
            text-align: right;
            font-size: 24px;
            font-weight: bold;
            color: #000;
            text-transform: uppercase;
        }
        .document-id {
            text-align: right;
            font-size: 12px;
            color: #666;
        }
        .details-grid {
            width: 100%;
            margin-bottom: 40px;
        }
        .details-grid td {
            vertical-align: top;
            padding: 10px 0;
        }
        .section-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #999;
            margin-bottom: 5px;
            border-bottom: 1px solid #eee;
            padding-bottom: 3px;
            display: block;
        }
        .detail-value {
            font-size: 13px;
            font-weight: bold;
            color: #111;
        }
        .booking-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .booking-table th {
            text-align: left;
            background: #f9f9f9;
            padding: 12px 10px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px solid #ddd;
        }
        .booking-table td {
            padding: 15px 10px;
            font-size: 13px;
            border-bottom: 1px solid #eee;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            background: #e2e8f0;
            color: #475569;
        }
        .status-verified {
            background: #dcfce7;
            color: #166534;
        }
        .summary-section {
            width: 100%;
        }
        .summary-table {
            width: 300px;
            float: right;
        }
        .summary-table td {
            padding: 8px 0;
            font-size: 13px;
        }
        .total-row td {
            font-size: 18px;
            font-weight: bold;
            border-top: 2px solid #333;
            padding-top: 15px;
        }
        .footer {
            margin-top: 100px;
            clear: both;
            border-top: 1px solid #eee;
            padding-top: 20px;
            font-size: 10px;
            color: #777;
            text-align: center;
        }
        .signature-section {
            margin-top: 50px;
            width: 200px;
            text-align: center;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            margin-bottom: 5px;
            height: 40px;
        }
    </style>
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <td class="brand-section">
                    <div class="brand-name">PADELHUB</div>
                    <div class="company-info">
                        Manajemen Arena Profesional<br>
                        Jakarta, Indonesia<br>
                        contact@padelhub.id
                    </div>
                </td>
                <td>
                    <div class="invoice-title">Kuitansi Resmi</div>
                    <div class="document-id">No: #{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="details-grid">
        <tr>
            <td width="50%">
                <span class="section-title">Ditagihkan Kepada</span>
                <div class="detail-value">{{ $booking->user->name }}</div>
                <div class="company-info">{{ $booking->user->email }}</div>
            </td>
            <td width="50%">
                <span class="section-title">Tanggal Terbit</span>
                <div class="detail-value">{{ now()->format('d F Y') }}</div>
            </td>
        </tr>
    </table>

    <table class="booking-table">
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th>Jadwal</th>
                <th>Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="detail-value">{{ $booking->court->name }}</div>
                    <div class="company-info">Reservasi Arena</div>
                </td>
                <td>
                    <div class="detail-value">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</div>
                    <div class="company-info">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB</div>
                </td>
                <td>
                    <div class="detail-value">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                </td>
                <td>
                    <span class="status-badge status-verified">TERVERIFIKASI</span>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="summary-section">
        <table class="summary-table">
            <tr>
                <td>Subtotal</td>
                <td style="text-align: right;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pajak (0%)</td>
                <td style="text-align: right;">Rp 0</td>
            </tr>
            <tr class="total-row">
                <td>Total Keseluruhan</td>
                <td style="text-align: right;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="signature-section">
        <div class="section-title">Tanda Tangan Sah</div>
        <div class="signature-line"></div>
        <div class="company-info">Management PadelHub</div>
    </div>

    <div class="footer">
        <p>Kuitansi ini dibuat secara otomatis oleh komputer dan tidak memerlukan tanda tangan fisik untuk validasi.</p>
        <p>Terima kasih telah memesan di PadelHub. Sampai jumpa di arena!</p>
        <p>&copy; {{ date('Y') }} PADELHUB INDONESIA. All rights reserved.</p>
    </div>
</body>
</html>
