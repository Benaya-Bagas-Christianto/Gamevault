<?php
$c = file_get_contents('d:/Laragon/laragon/www/tokoGame/resources/views/profile/index.blade.php');
preg_match('/@push\(\'scripts\'\)(.*?)@endpush/is', $c, $m);
$s = preg_replace('/<script.*?>|<\/script>/i', '', $m[1]);
// Fix blade templating for syntax check
$s = str_replace('{{ route("profile.avatar") }}', '/profile/avatar', $s);
$s = str_replace('{{ csrf_token() }}', 'token', $s);
$s = str_replace('{{ config(\'services.midtrans.is_production\') ? \'https://app.midtrans.com/snap/snap.js\' : \'https://app.sandbox.midtrans.com/snap/snap.js\' }}', '', $s);
$s = str_replace('{{ config(\'services.midtrans.client_key\') }}', '', $s);
$s = str_replace('{{ route("profile.orders.check-statuses") }}', '/test', $s);
file_put_contents('test_syntax.js', $s);
