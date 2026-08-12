<?php
auth()->loginUsingId(2);
$c = new \App\Http\Controllers\CheckoutController();
$r = request();
echo $c->getToken($r)->getContent();
