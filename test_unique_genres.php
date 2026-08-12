<?php
print_r(App\Models\Game::pluck('genre')->flatten()->unique()->sort()->values()->toArray());
