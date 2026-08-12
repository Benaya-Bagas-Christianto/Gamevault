@extends('emails.layout')
@section('title', 'Refund Disetujui - GameVault')
@section('content')

<!-- Title Row -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:32px;">
    <tr>
        <td>
            <p style="margin:0 0 4px 0; font-size:12px; font-weight:700; color:#76b900; text-transform:uppercase; letter-spacing:2px;">Refund Disetujui</p>
            <h1 style="margin:0; font-size:28px; font-weight:800; color:#ffffff; line-height:1.2;">Pengajuan Refund Disetujui ✓</h1>
        </td>
    </tr>
</table>

<!-- Greeting -->
<p style="margin:0 0 16px 0; font-size:16px; color:#b0b0c0; line-height:1.6;">Halo,</p>
<p style="margin:0 0 36px 0; font-size:16px; color:#b0b0c0; line-height:1.6;">
    Kabar baik! Pengajuan refund Anda untuk game <strong style="color:#ffffff;">{{ $detail->game->title }}</strong> telah disetujui oleh tim admin kami.
</p>

<!-- Refund Detail Table -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:32px;">
    <tr>
        <td style="background-color:#0d0d10; border:1px solid #1e1e28; border-left:4px solid #76b900; padding:0;">
            <!-- Row: Order ID -->
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td style="padding:16px 24px; border-bottom:1px solid #1a1a22;">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td style="font-size:13px; color:#555568; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Order ID</td>
                                <td style="font-size:14px; color:#ffffff; font-weight:700; text-align:right;">#{{ $detail->transaction->order_id }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:16px 24px; border-bottom:1px solid #1a1a22;">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td style="font-size:13px; color:#555568; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Game</td>
                                <td style="font-size:14px; color:#ffffff; font-weight:700; text-align:right;">{{ $detail->game->title }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:16px 24px;">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td style="font-size:13px; color:#555568; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Nominal Refund</td>
                                <td style="font-size:20px; color:#76b900; font-weight:800; text-align:right;">Rp {{ number_format($detail->harga_saat_beli, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- Info Box -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:32px;">
    <tr>
        <td style="background-color:#0d0d10; border:1px solid #1e1e28; padding:20px 24px;">
            <p style="margin:0 0 8px 0; font-size:13px; font-weight:700; color:#76b900;">📋 Informasi Proses Refund:</p>
            <p style="margin:0 0 6px 0; font-size:13px; color:#888898; line-height:1.6;">• Dana akan dikembalikan ke metode pembayaran asli Anda dalam <strong style="color:#ffffff;">3–5 hari kerja</strong></p>
            <p style="margin:0 0 6px 0; font-size:13px; color:#888898; line-height:1.6;">• Waktu proses tergantung kebijakan bank atau penyedia layanan pembayaran Anda</p>
            <p style="margin:0; font-size:13px; color:#888898; line-height:1.6;">• Akses Anda ke game dan Kunci Lisensi telah dicabut</p>
        </td>
    </tr>
</table>

<!-- Sign off -->
<p style="margin:0; font-size:14px; color:#555568; line-height:1.6;">
    Terima kasih atas kepercayaan Anda,<br>
    <strong style="color:#76b900;">Tim GameVault</strong>
</p>

@endsection