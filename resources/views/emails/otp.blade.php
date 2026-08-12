@extends('emails.layout')
@section('title', 'Kode OTP GameVault')
@section('content')

<!-- Title Row -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:32px;">
<tr>
    <td>
        <p style="margin:0 0 4px 0; font-size:12px; font-weight:700; color:#76b900; text-transform:uppercase; letter-spacing:2px;">Keamanan Akun</p>
        <h1 style="margin:0; font-size:28px; font-weight:800; color:#ffffff; line-height:1.2;">Kode OTP Anda</h1>
    </td>
</tr>
</table>

<!-- Greeting -->
<p style="margin:0 0 16px 0; font-size:16px; color:#b0b0c0; line-height:1.6;">Halo,</p>
<p style="margin:0 0 36px 0; font-size:16px; color:#b0b0c0; line-height:1.6;">
    Anda telah meminta kode OTP untuk keperluan keamanan di akun <strong style="color:#ffffff;">GameVault</strong> Anda. Gunakan kode di bawah ini untuk melanjutkan proses verifikasi.
</p>

<!-- OTP Block -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:36px;">
<tr>
    <td style="background-color:#0d0d10; border:1px solid #2a2a38; border-left:4px solid #76b900; padding:32px 40px;">
        <table cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td>
                <p style="margin:0 0 8px 0; font-size:12px; font-weight:700; color:#555568; text-transform:uppercase; letter-spacing:1.5px;">Kode Verifikasi</p>
                <p style="margin:0; font-family:'Courier New',Courier,monospace; font-size:44px; font-weight:800; color:#76b900; letter-spacing:12px; line-height:1;">{{ $code }}</p>
            </td>
        </tr>
        </table>
    </td>
</tr>
</table>

<!-- Warning Row -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:32px;">
<tr>
    <td style="padding:16px 20px; background-color:#1a0e0e; border-left:3px solid #dc2626;">
        <p style="margin:0; font-size:13px; color:#fca5a5; line-height:1.6;">
            <strong>⚠ Penting:</strong> Kode ini akan <strong>kedaluwarsa dalam 5 menit</strong>. Jangan pernah membagikan kode ini kepada siapa pun, termasuk pihak GameVault.
        </p>
    </td>
</tr>
</table>

<!-- Sign off -->
<p style="margin:0; font-size:14px; color:#555568; line-height:1.6;">
    Terima kasih,<br>
    <strong style="color:#76b900;">Tim Keamanan GameVault</strong>
</p>

@endsection
