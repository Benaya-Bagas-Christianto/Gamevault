<?php
$url = "https://video.akamai.steamstatic.com/store_trailers/1174180/254769/efa525d1de9a4d54e46083647659459113046baa/1750634263/movie_max.mp4?t=1574881311";
$headers = get_headers($url);
print_r($headers);
