<?php
$request = Request::create('/categories', 'GET');
$response = app()->handle($request);
echo strlen($response->getContent());
