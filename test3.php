<?php
$ctx = stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]);
var_dump(get_headers('https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/2358720/header.jpg?t=1760601605', 1, $ctx));
