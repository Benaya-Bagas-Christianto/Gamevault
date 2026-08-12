<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'GameVault')</title>
</head>
<body style="margin:0; padding:0; background-color:#0d0d10; font-family:'Segoe UI',Arial,sans-serif; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;">

<!-- WRAPPER -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#0d0d10; width:100%;">
<tr><td style="padding:0;">

    <!-- HEADER -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#111116; border-bottom:3px solid #76b900;">
    <tr>
        <td style="padding:20px 48px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td style="vertical-align:middle;">
                    <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="vertical-align:middle; padding-right:10px;">
                            <img src="{{ asset('img/logo.png') }}" alt="GameVault" style="height:32px; display:block;">
                        </td>
                        <td style="vertical-align:middle;">
                            <span style="font-size:22px; font-weight:800; color:#ffffff; letter-spacing:0.3px;">Game<span style="color:#76b900;">Vault</span></span>
                        </td>
                    </tr>
                    </table>
                </td>
                <td style="vertical-align:middle; text-align:right;">
                    <span style="font-size:12px; color:#555560; font-weight:500; text-transform:uppercase; letter-spacing:1px;">Email Notifikasi</span>
                </td>
            </tr>
            </table>
        </td>
    </tr>
    </table>

    <!-- BODY -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#101014;">
    <tr>
        <td style="padding:48px 48px 40px 48px;">
            @yield('content')
        </td>
    </tr>
    </table>

    <!-- DIVIDER -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td style="padding:0 48px;">
            <hr style="border:none; border-top:1px solid #1e1e28; margin:0;">
        </td>
    </tr>
    </table>

    <!-- FOOTER -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#0a0a0d;">
    <tr>
        <td style="padding:28px 48px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td style="vertical-align:middle;">
                    <p style="margin:0; font-size:13px; color:#3a3a48;">
                        &copy; {{ date('Y') }} <strong style="color:#76b900;">GameVault</strong>. Semua hak cipta dilindungi.
                    </p>
                    <p style="margin:6px 0 0 0; font-size:11px; color:#2e2e3a;">
                        Ini adalah email otomatis — mohon tidak membalas email ini.
                    </p>
                </td>
                <td style="vertical-align:middle; text-align:right;">
                    <span style="font-size:11px; color:#2a2a36;">Platform Game Digital Terpercaya</span>
                </td>
            </tr>
            </table>
        </td>
    </tr>
    </table>

</td></tr>
</table>

</body>
</html>
