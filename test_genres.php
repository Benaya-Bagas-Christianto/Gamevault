<?php
$genres = App\Models\Game::pluck('genre')->toArray();
var_dump($genres);
