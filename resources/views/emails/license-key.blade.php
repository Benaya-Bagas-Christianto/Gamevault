@extends('emails.layout')
@section('title', 'Lisensi Game Anda - GameVault')
@section('content')

<!-- Title Row -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:32px;">
    <tr>
        <td>
            <p style="margin:0 0 4px 0; font-size:12px; font-weight:700; color:#76b900; text-transform:uppercase; letter-spacing:2px;">Pembelian Berhasil</p>
            <h1 style="margin:0; font-size:28px; font-weight:800; color:#ffffff; line-height:1.2;">Kunci Lisensi Game Anda</h1>
        </td>
        <td style="text-align:right; vertical-align:top;">
            <span style="font-size:13px; color:#555568;">Order: <strong style="color:#76b900;">#{{ $transaction->order_id }}</strong></span>
        </td>
    </tr>
</table>

<!-- Greeting -->
<p style="margin:0 0 16px 0; font-size:16px; color:#b0b0c0; line-height:1.6;">Halo,</p>
<p style="margin:0 0 36px 0; font-size:16px; color:#b0b0c0; line-height:1.6;">
    Terima kasih telah berbelanja di <strong style="color:#ffffff;">GameVault</strong>! Berikut adalah Kunci Lisensi untuk game yang berhasil Anda beli. Simpan email ini baik-baik.
</p>

<!-- License Keys -->
@foreach($transaction->details as $detail)
@if($detail->license_key)
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
    <tr>
        <!-- Game Info Row -->
        <td style="background-color:#0d0d10; border:1px solid #1e1e28; border-bottom:none; padding:16px 24px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    @if($detail->game->image)
                    <td style="width:72px; vertical-align:middle; padding-right:16px;">
                        <img src="{{ str_starts_with($detail->game->image, 'http') ? $detail->game->image : asset('storage/' . $detail->game->image) }}" alt="{{ $detail->game->title }}" style="width:72px; height:40px; object-fit:cover; display:block;">
                    </td>
                    @endif
                    <td style="vertical-align:middle;">
                        <p style="margin:0 0 4px 0; font-size:16px; font-weight:700; color:#ffffff;">{{ $detail->game->title }}</p>
                        <p style="margin:0; font-size:12px; color:#555568;">Platform: {{ $detail->game->platform ?? 'PC' }}</p>
                    </td>
                    <td style="vertical-align:middle; text-align:right;">
                        <span style="font-size:13px; color:#76b900; font-weight:700;">Rp {{ number_format($detail->price, 0, ',', '.') }}</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <!-- License Key Row -->
        <td style="background-color:#070709; border:1px solid #1e1e28; border-top:1px dashed #76b900; padding:20px 24px;">
            <p style="margin:0 0 8px 0; font-size:11px; font-weight:700; color:#555568; text-transform:uppercase; letter-spacing:1.5px;">Kunci Lisensi</p>
            <p style="margin:0; font-family:'Courier New',Courier,monospace; font-size:22px; font-weight:700; color:#76b900; letter-spacing:4px;">{{ $detail->license_key }}</p>
        </td>
    </tr>
</table>
@endif
@endforeach

<!-- Info Box -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:32px;">
    <tr>
        <td style="background-color:#0d0d10; border:1px solid #1e1e28; padding:20px 24px;">
            <p style="margin:0 0 8px 0; font-size:13px; font-weight:700; color:#76b900;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; margin-right:6px; margin-top:-2px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>Informasi Penting:
            </p>
            <p style="margin:0 0 6px 0; font-size:13px; color:#888898; line-height:1.6;">• Simpan kunci lisensi ini dengan aman — jangan dibagikan kepada orang lain</p>
            <p style="margin:0 0 6px 0; font-size:13px; color:#888898; line-height:1.6;">• Anda dapat melihat lisensi kapan saja melalui <a href="{{ route('profile.index') }}" style="color:#76b900; text-decoration:none; font-weight:700;">Profil GameVault Anda</a></p>
            <p style="margin:0; font-size:13px; color:#888898; line-height:1.6;">• Hubungi Support jika ada kendala dalam penggunaan lisensi</p>
        </td>
    </tr>
</table>

<!-- Sign off -->
<p style="margin:0; font-size:14px; color:#555568; line-height:1.6;">
    Selamat bermain! <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; margin-left:4px; margin-top:-2px;"><rect x="2" y="6" width="20" height="12" rx="2" ry="2"></rect><line x1="6" y1="12" x2="10" y2="12"></line><line x1="8" y1="10" x2="8" y2="14"></line><line x1="15" y1="13" x2="15.01" y2="13"></line><line x1="18" y1="11" x2="18.01" y2="11"></line></svg><br>
    <strong style="color:#76b900;">Tim GameVault</strong>
</p>

@endsection