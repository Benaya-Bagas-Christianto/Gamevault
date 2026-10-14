@extends('emails.layout')
@section('title', 'Reset Password - GameVault')
@section('content')

<!-- Title Row -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:32px;">
    <tr>
        <td>
            <p style="margin:0 0 4px 0; font-size:12px; font-weight:700; color:#76b900; text-transform:uppercase; letter-spacing:2px;">Keamanan Akun</p>
            <h1 style="margin:0; font-size:28px; font-weight:800; color:#ffffff; line-height:1.2;">Reset Password Kamu</h1>
        </td>
    </tr>
</table>

<!-- Greeting -->
<p style="margin:0 0 16px 0; font-size:16px; color:#b0b0c0; line-height:1.6;">Halo <strong style="color:#ffffff;">{{ $user->name }}</strong>,</p>
<p style="margin:0 0 36px 0; font-size:16px; color:#b0b0c0; line-height:1.6;">
    Kami menerima permintaan untuk mereset password akun <strong style="color:#ffffff;">GameVault</strong> kamu. Klik tombol di bawah ini untuk membuat password baru yang aman.
</p>

<!-- CTA Button -->
<table cellpadding="0" cellspacing="0" border="0" style="margin-bottom:36px;">
    <tr>
        <td style="background-color:#76b900; padding:0;">
            <a href="{{ $url }}" style="display:inline-block; padding:16px 40px; font-size:15px; font-weight:800; color:#000000; text-decoration:none; letter-spacing:0.5px; font-family:'Segoe UI',Arial,sans-serif;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; margin-right:6px; margin-top:-2px;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg> Reset Password Sekarang
            </a>
        </td>
    </tr>
</table>

<!-- Info Box -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:32px;">
    <tr>
        <td style="background-color:#0d0d10; border:1px solid #1e1e28; padding:20px 24px;">
            <p style="margin:0 0 10px 0; font-size:13px; font-weight:700; color:#76b900;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; margin-right:6px; margin-top:-2px;"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>Catatan Penting:
            </p>
            <p style="margin:0 0 8px 0; font-size:13px; color:#888898; line-height:1.6;">• Link ini akan <strong style="color:#ffffff;">kedaluwarsa dalam 60 menit</strong></p>
            <p style="margin:0 0 8px 0; font-size:13px; color:#888898; line-height:1.6;">• Jika kamu tidak meminta reset password, <strong style="color:#ffffff;">abaikan email ini</strong> — password kamu tidak akan berubah</p>
            <p style="margin:0; font-size:13px; color:#888898; line-height:1.6;">• Jika tombol tidak berfungsi, salin URL berikut ke browser kamu:</p>
            <p style="margin:8px 0 0 0;">
                <a href="{{ $url }}" style="font-size:12px; color:#76b900; word-break:break-all; font-family:'Courier New',Courier,monospace;">{{ $url }}</a>
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