@extends('emails.layout')
@section('title', 'Pemberitahuan Keamanan - GameVault')
@section('content')

<!-- Title Row -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:32px;">
<tr>
    <td>
        <p style="margin:0 0 4px 0; font-size:12px; font-weight:700; color:#76b900; text-transform:uppercase; letter-spacing:2px;">Pemberitahuan Keamanan</p>
        <h1 style="margin:0; font-size:28px; font-weight:800; color:#ffffff; line-height:1.2;">{{ $title }}</h1>
    </td>
</tr>
</table>

<!-- Greeting -->
<p style="margin:0 0 16px 0; font-size:16px; color:#b0b0c0; line-height:1.6;">Halo,</p>
<p style="margin:0 0 32px 0; font-size:16px; color:#b0b0c0; line-height:1.6;">
    {{ $messageContent }}
</p>

<!-- Security Alert Box -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:32px;">
<tr>
    <td style="background-color:#1c0a0a; border:1px solid #3d1212; border-left:4px solid #dc2626; padding:20px 24px;">
        <p style="margin:0 0 8px 0; font-size:13px; font-weight:700; color:#ef4444;">⚠ Peringatan Keamanan</p>
        <p style="margin:0; font-size:13px; color:#fca5a5; line-height:1.7;">
            Jika Anda <strong>tidak melakukan tindakan ini</strong>, segera hubungi tim Support kami karena akun Anda mungkin dalam bahaya. Segera ganti password Anda.
        </p>
    </td>
</tr>
</table>

<!-- Activity Info -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:32px;">
<tr>
    <td style="background-color:#0d0d10; border:1px solid #1e1e28; padding:16px 24px;">
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td style="font-size:12px; color:#555568; padding:4px 0;">Waktu Aktivitas</td>
            <td style="font-size:12px; color:#888898; text-align:right; padding:4px 0;">{{ now()->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</td>
        </tr>
        <tr>
            <td style="font-size:12px; color:#555568; padding:4px 0; padding-top:8px;">Platform</td>
            <td style="font-size:12px; color:#888898; text-align:right; padding:4px 0; padding-top:8px;">GameVault Web</td>
        </tr>
        </table>
    </td>
</tr>
</table>

<!-- Sign off -->
<p style="margin:0; font-size:14px; color:#555568; line-height:1.6;">
    Terima kasih,<br>
    <strong style="color:#76b900;">Tim Keamanan GameVault</strong>
</p>

@endsection
