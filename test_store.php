<?php
auth()->loginUsingId(2);
$c = new \App\Http\Controllers\HomeController();
echo $c->store(request())->render();
