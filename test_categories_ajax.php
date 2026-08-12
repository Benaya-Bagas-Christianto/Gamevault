<?php
$request = Request::create('/categories', 'GET');
$request->headers->set('X-Requested-With', 'XMLHttpRequest');
$response = app()->handle($request);
echo strlen($response->getContent());
