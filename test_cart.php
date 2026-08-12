<?php
auth()->loginUsingId(2);
$c = new \App\Http\Controllers\CartController();
echo $c->index()->render();
