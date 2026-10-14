<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;
use App\Models\GameGallery;
use Illuminate\Support\Str;

class DetailedGameSeeder extends Seeder
{
    public function run()
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Game::truncate();
        GameGallery::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $games = [

            [
                'title' => base64_decode('QXNzYXNzaW7igJlzIENyZWVkwq4gSVYgQmxhY2sgRmxhZ+KEog=='),
                'description' => base64_decode('RGlzY292ZXIgQXNzYXNzaW5cJ3MgQ3JlZWQgSVYgQmxhY2sgRmxhZywgdGhlIGNsYXNzaWMgcGlyYXRlIGFjdGlvbi1hZHZlbnR1cmUgZ2FtZSBmcm9tIDIwMTMuIENvbW1hbmQgeW91ciBzaGlwLCBodW50IFRlbXBsYXJzIGFuZCBjYXJ2ZSB5b3VyIGxlZ2VuZCBhcyBFZHdhcmQgS2Vud2F5IGFjcm9zcyB0aGUgQ2FyaWJiZWFuXCdzIEdvbGRlbiBBZ2Ugb2YgUGlyYWN5Lg=='),
                'price' => 409000,
                'genre' => json_decode(base64_decode('WyJBa3NpIiwgIlBldHVhbGFuZ2FuIl0='), true),
                'developer' => base64_decode('VWJpc29mdCBNb250cmVhbA=='),
                'publisher' => base64_decode('VWJpc29mdA=='),
                'release_date' => date('Y-m-d', strtotime('')),
                'cover_image' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/242050/header.jpg?t=1777052740',
                'steam_appid' => '242050',
                'galleries' => json_decode(base64_decode('W3sidHlwZSI6ICJ2aWRlbyIsICJwYXRoIjogIiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzIwMzAwOTEvbW92aWUuMjkzeDE2NS5qcGc/dD0xNDQ3MzU5NDg3In0sIHsidHlwZSI6ICJpbWFnZSIsICJwYXRoIjogImh0dHBzOi8vc2hhcmVkLmFrYW1haS5zdGVhbXN0YXRpYy5jb20vc3RvcmVfaXRlbV9hc3NldHMvc3RlYW0vYXBwcy8yNDIwNTAvc3NfODI0NTM2OTQ4NTQyNzI5NjdlNmY3YzIxNDdmZjg2ZjYyYTJiMTQwOC4xOTIweDEwODAuanBnP3Q9MTc3NzA1Mjc0MCIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzI0MjA1MC9zc184MjQ1MzY5NDg1NDI3Mjk2N2U2ZjdjMjE0N2ZmODZmNjJhMmIxNDA4LjYwMHgzMzguanBnP3Q9MTc3NzA1Mjc0MCJ9LCB7InR5cGUiOiAiaW1hZ2UiLCAicGF0aCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvMjQyMDUwL3NzX2FmYjZmOTFiYzk2YjUxZDA2ODRiYjRjYmVjNDM4OWI0OWRjYjk2ZGIuMTkyMHgxMDgwLmpwZz90PTE3NzcwNTI3NDAiLCAidGh1bWJuYWlsIjogImh0dHBzOi8vc2hhcmVkLmFrYW1haS5zdGVhbXN0YXRpYy5jb20vc3RvcmVfaXRlbV9hc3NldHMvc3RlYW0vYXBwcy8yNDIwNTAvc3NfYWZiNmY5MWJjOTZiNTFkMDY4NGJiNGNiZWM0Mzg5YjQ5ZGNiOTZkYi42MDB4MzM4LmpwZz90PTE3NzcwNTI3NDAifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzI0MjA1MC9zc19jMmFkN2YyMmUyN2I4ZmZhYjJhODk2ZTU0M2E4MmZjOGY2ZTlhMWE3LjE5MjB4MTA4MC5qcGc/dD0xNzc3MDUyNzQwIiwgInRodW1ibmFpbCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvMjQyMDUwL3NzX2MyYWQ3ZjIyZTI3YjhmZmFiMmE4OTZlNTQzYTgyZmM4ZjZlOWExYTcuNjAweDMzOC5qcGc/dD0xNzc3MDUyNzQwIn1d'), true),
                'dlcs_ids' => json_decode(base64_decode('W10='), true)
            ],
            [
                'title' => base64_decode('Q3liZXJwdW5rIDIwNzc='),
                'description' => base64_decode('Q3liZXJwdW5rIDIwNzcgaXMgYW4gb3Blbi13b3JsZCwgYWN0aW9uLWFkdmVudHVyZSBSUEcgc2V0IGluIHRoZSBkYXJrIGZ1dHVyZSBvZiBOaWdodCBDaXR5IOKAlCBhIGRhbmdlcm91cyBtZWdhbG9wb2xpcyBvYnNlc3NlZCB3aXRoIHBvd2VyLCBnbGFtb3IsIGFuZCBjZWFzZWxlc3MgYm9keSBtb2RpZmljYXRpb24u'),
                'price' => 699999,
                'genre' => json_decode(base64_decode('WyJSUEciXQ=='), true),
                'developer' => base64_decode('Q0QgUFJPSkVLVCBSRUQ='),
                'publisher' => base64_decode('Q0QgUFJPSkVLVCBSRUQ='),
                'release_date' => date('Y-m-d', strtotime('9 Des 2020')),
                'cover_image' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1091500/e9047d8ec47ae3d94bb8b464fb0fc9e9972b4ac7/header.jpg?t=1784714077',
                'steam_appid' => '1091500',
                'galleries' => json_decode(base64_decode('W3sidHlwZSI6ICJ2aWRlbyIsICJwYXRoIjogIiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzI1NzA4MTEzMi84ODUzMzFkMjRhOGU4OGEwYTkwOTAzZjU4ODgzNWVkMzhiMmY4MmNmL21vdmllXzYwMHgzMzcuanBnP3Q9MTczNDQzNDc4MyJ9LCB7InR5cGUiOiAiaW1hZ2UiLCAicGF0aCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvMTA5MTUwMC9zc18yZjY0OWI2OGQ1NzliZjg3MDExNDg3ZDI5YmM0Y2NiZmRkOTdkMzRmLjE5MjB4MTA4MC5qcGc/dD0xNzg0NzE0MDc3IiwgInRodW1ibmFpbCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvMTA5MTUwMC9zc18yZjY0OWI2OGQ1NzliZjg3MDExNDg3ZDI5YmM0Y2NiZmRkOTdkMzRmLjYwMHgzMzguanBnP3Q9MTc4NDcxNDA3NyJ9LCB7InR5cGUiOiAiaW1hZ2UiLCAicGF0aCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvMTA5MTUwMC9zc18wZTY0MTcwNzUxZTFhZTIwZmY4ZmRiNzAwMWE4ODkyZmQ0ODI2MGU3LjE5MjB4MTA4MC5qcGc/dD0xNzg0NzE0MDc3IiwgInRodW1ibmFpbCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvMTA5MTUwMC9zc18wZTY0MTcwNzUxZTFhZTIwZmY4ZmRiNzAwMWE4ODkyZmQ0ODI2MGU3LjYwMHgzMzguanBnP3Q9MTc4NDcxNDA3NyJ9LCB7InR5cGUiOiAiaW1hZ2UiLCAicGF0aCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvMTA5MTUwMC9zc19hZjI4MDRhYTRiZjM1ZDQyNTEwNDM3NDQ0MTJjZTNiMzU5YTEyNWVmLjE5MjB4MTA4MC5qcGc/dD0xNzg0NzE0MDc3IiwgInRodW1ibmFpbCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvMTA5MTUwMC9zc19hZjI4MDRhYTRiZjM1ZDQyNTEwNDM3NDQ0MTJjZTNiMzU5YTEyNWVmLjYwMHgzMzguanBnP3Q9MTc4NDcxNDA3NyJ9XQ=='), true),
                'dlcs_ids' => json_decode(base64_decode('WzIxMzgzMzAsIDE0OTU3MTBd'), true)
            ],
            [
                'title' => base64_decode('VGhlIFdpdGNoZXIgMzogV2lsZCBIdW50IC0gQ29tcGxldGUgRWRpdGlvbg=='),
                'description' => base64_decode('WW91IGFyZSBHZXJhbHQgb2YgUml2aWEsIG1lcmNlbmFyeSBtb25zdGVyIHNsYXllci4gQmVmb3JlIHlvdSBzdGFuZHMgYSB3YXItdG9ybiwgbW9uc3Rlci1pbmZlc3RlZCBjb250aW5lbnQgeW91IGNhbiBleHBsb3JlIGF0IHdpbGwuIFlvdXIgY3VycmVudCBjb250cmFjdD8gVHJhY2tpbmcgZG93biBDaXJpIOKAlCB0aGUgQ2hpbGQgb2YgUHJvcGhlY3ksIGEgbGl2aW5nIHdlYXBvbiB0aGF0IGNhbiBhbHRlciB0aGUgc2hhcGUgb2YgdGhlIHdvcmxkLg=='),
                'price' => 539000,
                'genre' => json_decode(base64_decode('WyJSUEciXQ=='), true),
                'developer' => base64_decode('Q0QgUFJPSkVLVCBSRUQ='),
                'publisher' => base64_decode('Q0QgUFJPSkVLVCBSRUQ='),
                'release_date' => date('Y-m-d', strtotime('18 Mei 2015')),
                'cover_image' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/292030/97cca1147b256f450331044255b1dbd2d57d609e/header_alt_assets_4.jpg?t=1788333513',
                'steam_appid' => '292030',
                'galleries' => json_decode(base64_decode('W3sidHlwZSI6ICJ2aWRlbyIsICJwYXRoIjogIiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzI1NzQxMTI1Mi84NThlMTkxNWQ3MzU2ZWM3NWZiMDVlMzk0YTVlMjkxZTY1MTEzMjk2L21vdmllXzYwMHgzMzcuanBnP3Q9MTc4ODMzMzQ4MiJ9LCB7InR5cGUiOiAiaW1hZ2UiLCAicGF0aCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvMjkyMDMwL3NzXzU3MTAyOThhZjIzMThhZmQ5YWE3MjQ0OWVmMjlhYzRhMmVmNjRkOGUuMTkyMHgxMDgwLmpwZz90PTE3ODgzMzM1MTMiLCAidGh1bWJuYWlsIjogImh0dHBzOi8vc2hhcmVkLmFrYW1haS5zdGVhbXN0YXRpYy5jb20vc3RvcmVfaXRlbV9hc3NldHMvc3RlYW0vYXBwcy8yOTIwMzAvc3NfNTcxMDI5OGFmMjMxOGFmZDlhYTcyNDQ5ZWYyOWFjNGEyZWY2NGQ4ZS42MDB4MzM4LmpwZz90PTE3ODgzMzM1MTMifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzI5MjAzMC9zc18wOTAxZTY0ZTlkNGI4ZWJhZWE4MzQ4YzE5NGU3YTM2NDRkMmQ4MzJkLjE5MjB4MTA4MC5qcGc/dD0xNzg4MzMzNTEzIiwgInRodW1ibmFpbCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvMjkyMDMwL3NzXzA5MDFlNjRlOWQ0YjhlYmFlYTgzNDhjMTk0ZTdhMzY0NGQyZDgzMmQuNjAweDMzOC5qcGc/dD0xNzg4MzMzNTEzIn0sIHsidHlwZSI6ICJpbWFnZSIsICJwYXRoIjogImh0dHBzOi8vc2hhcmVkLmFrYW1haS5zdGVhbXN0YXRpYy5jb20vc3RvcmVfaXRlbV9hc3NldHMvc3RlYW0vYXBwcy8yOTIwMzAvc3NfMTEyYjFlMTc2YzFiZDI3MWQ4YTU2NWVhY2I2ZmVhZjkwZjI0MGJiMi4xOTIweDEwODAuanBnP3Q9MTc4ODMzMzUxMyIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzI5MjAzMC9zc18xMTJiMWUxNzZjMWJkMjcxZDhhNTY1ZWFjYjZmZWFmOTBmMjQwYmIyLjYwMHgzMzguanBnP3Q9MTc4ODMzMzUxMyJ9XQ=='), true),
                'dlcs_ids' => json_decode(base64_decode('WzUwMDY1MzAsIDEyMjkzMjBd'), true)
            ],
            [
                'title' => base64_decode('UmVkIERlYWQgUmVkZW1wdGlvbiAy'),
                'description' => base64_decode('QXJ0aHVyIE1vcmdhbiBkYW4gVmFuIGRlciBMaW5kZSBHYW5nIGFkYWxhaCBwZWxhbmdnYXIgaHVrdW0geWFuZyBrYWJ1ci4gRGlpa3V0aSBhZ2VuIGZlZGVyYWwgZGFuIHBlbWJ1cnUgaGFkaWFoLCBtZXJla2EgaGFydXMgbWVyYW1wb2ssIG1lbmN1cmksIGRhbiBiZXJ0YXJ1bmcgZGkgc2VwYW5qYW5nIGphbGFuIGRhcmF0YW4gbGlhciBndW5hIGJlcnRhaGFuIGhpZHVwLg=='),
                'price' => 879000,
                'genre' => json_decode(base64_decode('WyJBa3NpIiwgIlBldHVhbGFuZ2FuIl0='), true),
                'developer' => base64_decode('Um9ja3N0YXIgR2FtZXM='),
                'publisher' => base64_decode('Um9ja3N0YXIgR2FtZXM='),
                'release_date' => date('Y-m-d', strtotime('5 Des 2019')),
                'cover_image' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1174180/header.jpg?t=1759502961',
                'steam_appid' => '1174180',
                'galleries' => json_decode(base64_decode('W3sidHlwZSI6ICJ2aWRlbyIsICJwYXRoIjogIiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzI1Njc2ODM3MS9tb3ZpZS4yOTN4MTY1LmpwZz90PTE1NzQ4ODEzNTIifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzExNzQxODAvc3NfNjZiNTUzZjRjMjA5NDc2ZDNlNGNlMjVmYTQ3MTQwMDJjYzkxNGM0Zi4xOTIweDEwODAuanBnP3Q9MTc1OTUwMjk2MSIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzExNzQxODAvc3NfNjZiNTUzZjRjMjA5NDc2ZDNlNGNlMjVmYTQ3MTQwMDJjYzkxNGM0Zi42MDB4MzM4LmpwZz90PTE3NTk1MDI5NjEifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzExNzQxODAvc3NfYmFjNjBiYWNiZjVkYTg5NDUxMDM2NDhjMDhkMjdkNWUyMDI0NDRjYS4xOTIweDEwODAuanBnP3Q9MTc1OTUwMjk2MSIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzExNzQxODAvc3NfYmFjNjBiYWNiZjVkYTg5NDUxMDM2NDhjMDhkMjdkNWUyMDI0NDRjYS42MDB4MzM4LmpwZz90PTE3NTk1MDI5NjEifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzExNzQxODAvc3NfNjY4ZGFmZTQ3Nzc0M2Y4YjUwYjgxOGQ1YmJmY2VjNjY5ZTliYTkzZS4xOTIweDEwODAuanBnP3Q9MTc1OTUwMjk2MSIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzExNzQxODAvc3NfNjY4ZGFmZTQ3Nzc0M2Y4YjUwYjgxOGQ1YmJmY2VjNjY5ZTliYTkzZS42MDB4MzM4LmpwZz90PTE3NTk1MDI5NjEifV0='), true),
                'dlcs_ids' => json_decode(base64_decode('W10='), true)
            ],
            [
                'title' => base64_decode('R3JhbmQgVGhlZnQgQXV0byBWIExlZ2FjeQ=='),
                'description' => base64_decode('R3JhbmQgVGhlZnQgQXV0byBWIGZvciBQQyBvZmZlcnMgcGxheWVycyB0aGUgb3B0aW9uIHRvIGV4cGxvcmUgdGhlIGF3YXJkLXdpbm5pbmcgd29ybGQgb2YgTG9zIFNhbnRvcyBhbmQgQmxhaW5lIENvdW50eSBpbiByZXNvbHV0aW9ucyBvZiB1cCB0byA0ayBhbmQgYmV5b25kLCBhcyB3ZWxsIGFzIHRoZSBjaGFuY2UgdG8gZXhwZXJpZW5jZSB0aGUgZ2FtZSBydW5uaW5nIGF0IDYwIGZyYW1lcyBwZXIgc2Vjb25kLg=='),
                'price' => 4500,
                'genre' => json_decode(base64_decode('WyJBa3NpIiwgIlBldHVhbGFuZ2FuIl0='), true),
                'developer' => base64_decode('Um9ja3N0YXIgTm9ydGg='),
                'publisher' => base64_decode('Um9ja3N0YXIgR2FtZXM='),
                'release_date' => date('Y-m-d', strtotime('13 Apr 2015')),
                'cover_image' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/271590/header.jpg?t=1765387725',
                'steam_appid' => '271590',
                'galleries' => json_decode(base64_decode('W3sidHlwZSI6ICJ2aWRlbyIsICJwYXRoIjogIiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzI1NzEwOTc4Ni85NzRmOTNkYjFiOTdmMjA2ZGU2ZjIxZDU4NTUyNGQ1OThjZGJlMTM3L21vdmllXzYwMHgzMzcuanBnP3Q9MTc0MTExOTk3OCJ9LCB7InR5cGUiOiAiaW1hZ2UiLCAicGF0aCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvMjcxNTkwL3NzXzMyYWExOGFiMzE3NWUzMDAyMjE3ODYyZGQ1OTE3NjQ2ZDI5OGFiNmIuMTkyMHgxMDgwLmpwZz90PTE3NjUzODc3MjUiLCAidGh1bWJuYWlsIjogImh0dHBzOi8vc2hhcmVkLmFrYW1haS5zdGVhbXN0YXRpYy5jb20vc3RvcmVfaXRlbV9hc3NldHMvc3RlYW0vYXBwcy8yNzE1OTAvc3NfMzJhYTE4YWIzMTc1ZTMwMDIyMTc4NjJkZDU5MTc2NDZkMjk4YWI2Yi42MDB4MzM4LmpwZz90PTE3NjUzODc3MjUifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzI3MTU5MC9zc18yNzQ0ZjExMmZhMDYwMzIwZDE5MWE1MGU4YjNhOTI0NDFhNjQ4YTU2LjE5MjB4MTA4MC5qcGc/dD0xNzY1Mzg3NzI1IiwgInRodW1ibmFpbCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvMjcxNTkwL3NzXzI3NDRmMTEyZmEwNjAzMjBkMTkxYTUwZThiM2E5MjQ0MWE2NDhhNTYuNjAweDMzOC5qcGc/dD0xNzY1Mzg3NzI1In0sIHsidHlwZSI6ICJpbWFnZSIsICJwYXRoIjogImh0dHBzOi8vc2hhcmVkLmFrYW1haS5zdGVhbXN0YXRpYy5jb20vc3RvcmVfaXRlbV9hc3NldHMvc3RlYW0vYXBwcy8yNzE1OTAvc3NfZGEzOWMxNmRiMTc1ZjY5NzM3NzBiYWU2YjkxZDQxMTI1MTc2MzE1Mi4xOTIweDEwODAuanBnP3Q9MTc2NTM4NzcyNSIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzI3MTU5MC9zc19kYTM5YzE2ZGIxNzVmNjk3Mzc3MGJhZTZiOTFkNDExMjUxNzYzMTUyLjYwMHgzMzguanBnP3Q9MTc2NTM4NzcyNSJ9XQ=='), true),
                'dlcs_ids' => json_decode(base64_decode('W10='), true)
            ],
            [
                'title' => base64_decode('QmFsZHVyXCdzIEdhdGUgMw=='),
                'description' => base64_decode('QmFsZHVy4oCZcyBHYXRlIDMgaXMgYSBzdG9yeS1yaWNoLCBwYXJ0eS1iYXNlZCBSUEcgc2V0IGluIHRoZSB1bml2ZXJzZSBvZiBEdW5nZW9ucyAmYW1wOyBEcmFnb25zLCB3aGVyZSB5b3VyIGNob2ljZXMgc2hhcGUgYSB0YWxlIG9mIGZlbGxvd3NoaXAgYW5kIGJldHJheWFsLCBzdXJ2aXZhbCBhbmQgc2FjcmlmaWNlLCBhbmQgdGhlIGx1cmUgb2YgYWJzb2x1dGUgcG93ZXIu'),
                'price' => 699999,
                'genre' => json_decode(base64_decode('WyJQZXR1YWxhbmdhbiIsICJSUEciLCAiU3RyYXRlZ2kiXQ=='), true),
                'developer' => base64_decode('TGFyaWFuIFN0dWRpb3M='),
                'publisher' => base64_decode('TGFyaWFuIFN0dWRpb3M='),
                'release_date' => date('Y-m-d', strtotime('3 Agu 2023')),
                'cover_image' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1086940/48a2fcbda8565bb45025e98fd8ebde8a7203f6a0/header.jpg?t=1777363040',
                'steam_appid' => '1086940',
                'galleries' => json_decode(base64_decode('W3sidHlwZSI6ICJ2aWRlbyIsICJwYXRoIjogIiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzI1Njk4NzQyNC9tb3ZpZS4yOTN4MTY1LmpwZz90PTE3MDIwMDc2NDUifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzEwODY5NDAvc3NfYzczYmM1NDQxNTE3OGMwN2ZlZjg1ZjU0ZWUyNjYyMTcyOGM3NzUwNC4xOTIweDEwODAuanBnP3Q9MTc3NzM2MzA0MCIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzEwODY5NDAvc3NfYzczYmM1NDQxNTE3OGMwN2ZlZjg1ZjU0ZWUyNjYyMTcyOGM3NzUwNC42MDB4MzM4LmpwZz90PTE3NzczNjMwNDAifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzEwODY5NDAvc3NfNzNkOTNiZWE4NDJiOTM5MTRkOTY2NjIyMTA0ZGNiOGMwZjQyOTcyYi4xOTIweDEwODAuanBnP3Q9MTc3NzM2MzA0MCIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzEwODY5NDAvc3NfNzNkOTNiZWE4NDJiOTM5MTRkOTY2NjIyMTA0ZGNiOGMwZjQyOTcyYi42MDB4MzM4LmpwZz90PTE3NzczNjMwNDAifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzEwODY5NDAvc3NfY2Y5MzZkMzEwNjFiNThlOThlMGM2NDZhZWUwMGU2MDMwYzQxMGNkYS4xOTIweDEwODAuanBnP3Q9MTc3NzM2MzA0MCIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzEwODY5NDAvc3NfY2Y5MzZkMzEwNjFiNThlOThlMGM2NDZhZWUwMGU2MDMwYzQxMGNkYS42MDB4MzM4LmpwZz90PTE3NzczNjMwNDAifV0='), true),
                'dlcs_ids' => json_decode(base64_decode('WzIzNzg1MDAsIDI5NTYzMjBd'), true)
            ],
            [
                'title' => base64_decode('RUxERU4gUklORw=='),
                'description' => base64_decode('VEhFIENSSVRJQ0FMTFkgQUNDTEFJTUVEIEZBTlRBU1kgQUNUSU9OIFJQRy4gUmlzZSwgVGFybmlzaGVkLCBhbmQgYmUgZ3VpZGVkIGJ5IGdyYWNlIHRvIGJyYW5kaXNoIHRoZSBwb3dlciBvZiB0aGUgRWxkZW4gUmluZyBhbmQgYmVjb21lIGFuIEVsZGVuIExvcmQgaW4gdGhlIExhbmRzIEJldHdlZW4u'),
                'price' => 599000,
                'genre' => json_decode(base64_decode('WyJBa3NpIiwgIlJQRyJd'), true),
                'developer' => base64_decode('RnJvbVNvZnR3YXJlLCBJbmMu'),
                'publisher' => base64_decode('RnJvbVNvZnR3YXJlLCBJbmMu'),
                'release_date' => date('Y-m-d', strtotime('24 Feb 2022')),
                'cover_image' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1245620/header.jpg?t=1787868578',
                'steam_appid' => '1245620',
                'galleries' => json_decode(base64_decode('W3sidHlwZSI6ICJ2aWRlbyIsICJwYXRoIjogIiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzI1Njg4OTQ1Ni9tb3ZpZS4yOTN4MTY1LmpwZz90PTE2NTQxMDkyNDEifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzEyNDU2MjAvc3NfOTQzYmY2ZmU2MjM1Mjc1N2Q5MDcwYzFkMzNlNTBiOTJmZTg1MzlmMS4xOTIweDEwODAuanBnP3Q9MTc4Nzg2ODU3OCIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzEyNDU2MjAvc3NfOTQzYmY2ZmU2MjM1Mjc1N2Q5MDcwYzFkMzNlNTBiOTJmZTg1MzlmMS42MDB4MzM4LmpwZz90PTE3ODc4Njg1NzgifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzEyNDU2MjAvc3NfZGNkYWM5ZTRiMjZhYzBlZTUyNDhiZmQyOTY3ZDc2NGZkMDBjZGI0Mi4xOTIweDEwODAuanBnP3Q9MTc4Nzg2ODU3OCIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzEyNDU2MjAvc3NfZGNkYWM5ZTRiMjZhYzBlZTUyNDhiZmQyOTY3ZDc2NGZkMDBjZGI0Mi42MDB4MzM4LmpwZz90PTE3ODc4Njg1NzgifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzEyNDU2MjAvc3NfM2M0MTM4NGEyNGQ4NmRkZGQ1OGE4ZjYxZGI3N2Y5ZGMwYmZkYThiNS4xOTIweDEwODAuanBnP3Q9MTc4Nzg2ODU3OCIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzEyNDU2MjAvc3NfM2M0MTM4NGEyNGQ4NmRkZGQ1OGE4ZjYxZGI3N2Y5ZGMwYmZkYThiNS42MDB4MzM4LmpwZz90PTE3ODc4Njg1NzgifV0='), true),
                'dlcs_ids' => json_decode(base64_decode('WzM2NTU2OTAsIDI3Nzg1OTBd'), true)
            ],
            [
                'title' => base64_decode('R29kIG9mIFdhcg=='),
                'description' => base64_decode('SGlzIHZlbmdlYW5jZSBhZ2FpbnN0IHRoZSBHb2RzIG9mIE9seW1wdXMgeWVhcnMgYmVoaW5kIGhpbSwgS3JhdG9zIG5vdyBsaXZlcyBhcyBhIG1hbiBpbiB0aGUgcmVhbG0gb2YgTm9yc2UgR29kcyBhbmQgbW9uc3RlcnMuIEl0IGlzIGluIHRoaXMgaGFyc2gsIHVuZm9yZ2l2aW5nIHdvcmxkIHRoYXQgaGUgbXVzdCBmaWdodCB0byBzdXJ2aXZl4oCmIGFuZCB0ZWFjaCBoaXMgc29uIHRvIGRvIHRoZSBzYW1lLg=='),
                'price' => 729000,
                'genre' => json_decode(base64_decode('WyJBa3NpIiwgIlBldHVhbGFuZ2FuIiwgIlJQRyJd'), true),
                'developer' => base64_decode('U2FudGEgTW9uaWNhIFN0dWRpbw=='),
                'publisher' => base64_decode('UGxheVN0YXRpb24gUHVibGlzaGluZyBMTEM='),
                'release_date' => date('Y-m-d', strtotime('14 Jan 2022')),
                'cover_image' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1593500/header.jpg?t=1763059412',
                'steam_appid' => '1593500',
                'galleries' => json_decode(base64_decode('W3sidHlwZSI6ICJ2aWRlbyIsICJwYXRoIjogIiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzI1Njg2NDAwNC9tb3ZpZS4yOTN4MTY1LmpwZz90PTE2MzkwMDE4MTcifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzE1OTM1MDAvc3NfNmVjY2M5NzBiNWRlMjk0MzU0NmQ5M2QzMTliZTFiNWMwNjE4ZjIxYi4xOTIweDEwODAuanBnP3Q9MTc2MzA1OTQxMiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzE1OTM1MDAvc3NfNmVjY2M5NzBiNWRlMjk0MzU0NmQ5M2QzMTliZTFiNWMwNjE4ZjIxYi42MDB4MzM4LmpwZz90PTE3NjMwNTk0MTIifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzE1OTM1MDAvc3NfZjFiZmYyNGQzOTY3YTIxZDMwM2Q5NWUxMWVkODkyZTNkOTExMzA1Ny4xOTIweDEwODAuanBnP3Q9MTc2MzA1OTQxMiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzE1OTM1MDAvc3NfZjFiZmYyNGQzOTY3YTIxZDMwM2Q5NWUxMWVkODkyZTNkOTExMzA1Ny42MDB4MzM4LmpwZz90PTE3NjMwNTk0MTIifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzE1OTM1MDAvc3NfMzY3MGJhNzJjN2UzZTljM2MzMjI1NTQ3ZWYyYzEwNTM1MDRlNjJiOC4xOTIweDEwODAuanBnP3Q9MTc2MzA1OTQxMiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzE1OTM1MDAvc3NfMzY3MGJhNzJjN2UzZTljM2MzMjI1NTQ3ZWYyYzEwNTM1MDRlNjJiOC42MDB4MzM4LmpwZz90PTE3NjMwNTk0MTIifV0='), true),
                'dlcs_ids' => json_decode(base64_decode('W10='), true)
            ],
            [
                'title' => base64_decode('SG9yaXpvbiBaZXJvIERhd27ihKIgQ29tcGxldGUgRWRpdGlvbg=='),
                'description' => base64_decode('RXhwZXJpZW5jZSBBbG954oCZcyBsZWdlbmRhcnkgcXVlc3QgdG8gdW5yYXZlbCB0aGUgbXlzdGVyaWVzIG9mIGEgZnV0dXJlIEVhcnRoIHJ1bGVkIGJ5IE1hY2hpbmVzLiBVc2UgZGV2YXN0YXRpbmcgdGFjdGljYWwgYXR0YWNrcyBhZ2FpbnN0IHlvdXIgcHJleSBhbmQgZXhwbG9yZSBhIG1hamVzdGljIG9wZW4gd29ybGQgaW4gdGhpcyBhd2FyZC13aW5uaW5nIGFjdGlvbiBSUEch'),
                'price' => 4500,
                'genre' => json_decode(base64_decode('WyJBa3NpIiwgIlBldHVhbGFuZ2FuIiwgIlJQRyJd'), true),
                'developer' => base64_decode('R3VlcnJpbGxh'),
                'publisher' => base64_decode('UGxheVN0YXRpb24gUHVibGlzaGluZyBMTEM='),
                'release_date' => date('Y-m-d', strtotime('7 Agu 2020')),
                'cover_image' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1151640/header.jpg?t=1776465788',
                'steam_appid' => '1151640',
                'galleries' => json_decode(base64_decode('W3sidHlwZSI6ICJ2aWRlbyIsICJwYXRoIjogIiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzI1Njg5OTY0MC9tb3ZpZS4yOTN4MTY1LmpwZz90PTE2NTk3MTEwNjEifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzExNTE2NDAvc3NfZDA5MTA2MDYwZmI3ZGU4YmYzNDJjMjNkZjE4YjE0ZGViYzhhMTVhMy4xOTIweDEwODAuanBnP3Q9MTc3NjQ2NTc4OCIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzExNTE2NDAvc3NfZDA5MTA2MDYwZmI3ZGU4YmYzNDJjMjNkZjE4YjE0ZGViYzhhMTVhMy42MDB4MzM4LmpwZz90PTE3NzY0NjU3ODgifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzExNTE2NDAvc3NfMjcxZjg1MGVlYzNmOTZiMjJhYTE3YmUzNWI5NDgyNjhlMDc3MWM3Zi4xOTIweDEwODAuanBnP3Q9MTc3NjQ2NTc4OCIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzExNTE2NDAvc3NfMjcxZjg1MGVlYzNmOTZiMjJhYTE3YmUzNWI5NDgyNjhlMDc3MWM3Zi42MDB4MzM4LmpwZz90PTE3NzY0NjU3ODgifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzExNTE2NDAvc3NfMTVmNTc1OWM0NDFlNGU1ZjUxZTFhOGVlMzMzZTRhYjlkZjlhYTc4My4xOTIweDEwODAuanBnP3Q9MTc3NjQ2NTc4OCIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzExNTE2NDAvc3NfMTVmNTc1OWM0NDFlNGU1ZjUxZTFhOGVlMzMzZTRhYjlkZjlhYTc4My42MDB4MzM4LmpwZz90PTE3NzY0NjU3ODgifV0='), true),
                'dlcs_ids' => json_decode(base64_decode('W10='), true)
            ],
            [
                'title' => base64_decode('UGVyc29uYSA1IFJveWFs'),
                'description' => base64_decode('TGVhZCB0aGUgUGhhbnRvbSBUaGlldmVzIG9mIEhlYXJ0cyBpbiB0aGlzIGF3YXJkIHdpbm5pbmcsIHN0eWxpc2ggdHVybi1iYXNlZCBSUEcgZmlsbGVkIHdpdGggZHVuZ2VvbiBjcmF3bGluZywgcGFydHkgY3VzdG9taXphdGlvbiwgc3RyYXRlZ2ljIGNvbWJhdCwgYW5kIFBlcnNvbmEgZnVzaW9uLiBFeHBsb3JlIFRva3lvLCBidWlsZCByZWxhdGlvbnNoaXBzLCBiYXR0bGUgU2hhZG93cywgYW5kIGluZmlsdHJhdGUgdGhlIG1pbmRzIG9mIHRoZSBjb3JydXB0IHRvIG1ha2UgdGhlbSBjaGFuZ2UgdGhlaXIgd2F5cyE='),
                'price' => 798000,
                'genre' => json_decode(base64_decode('WyJSUEciXQ=='), true),
                'developer' => base64_decode('QVRMVVM='),
                'publisher' => base64_decode('U0VHQQ=='),
                'release_date' => date('Y-m-d', strtotime('20 Okt 2022')),
                'cover_image' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1687950/header.jpg?t=1783356822',
                'steam_appid' => '1687950',
                'galleries' => json_decode(base64_decode('W3sidHlwZSI6ICJ2aWRlbyIsICJwYXRoIjogIiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzI1NjkxMTkxOS9tb3ZpZS4yOTN4MTY1LmpwZz90PTE3MjI0MjgxNDcifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzE2ODc5NTAvc3NfNjYzMTcxZGMzYWZjZThmZTk4N2U1N2U4NjU5ZjkxYjY5ZmFhMzliYy4xOTIweDEwODAuanBnP3Q9MTc4MzM1NjgyMiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzE2ODc5NTAvc3NfNjYzMTcxZGMzYWZjZThmZTk4N2U1N2U4NjU5ZjkxYjY5ZmFhMzliYy42MDB4MzM4LmpwZz90PTE3ODMzNTY4MjIifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzE2ODc5NTAvc3NfYTMyNThhYmE4NGFlMmYyZmYxM2EwMmExNjBmNzQ5NWJmYzE1MmFkYi4xOTIweDEwODAuanBnP3Q9MTc4MzM1NjgyMiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzE2ODc5NTAvc3NfYTMyNThhYmE4NGFlMmYyZmYxM2EwMmExNjBmNzQ5NWJmYzE1MmFkYi42MDB4MzM4LmpwZz90PTE3ODMzNTY4MjIifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzE2ODc5NTAvc3NfZGRjYzAxNmI4ZTVjNDM0Y2NiZDFhODljMDE1N2NlNzNhY2Y5MDVhZS4xOTIweDEwODAuanBnP3Q9MTc4MzM1NjgyMiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzE2ODc5NTAvc3NfZGRjYzAxNmI4ZTVjNDM0Y2NiZDFhODljMDE1N2NlNzNhY2Y5MDVhZS42MDB4MzM4LmpwZz90PTE3ODMzNTY4MjIifV0='), true),
                'dlcs_ids' => json_decode(base64_decode('W10='), true)
            ],
            [
                'title' => base64_decode('TW9uc3RlciBIdW50ZXI6IFdvcmxk'),
                'description' => base64_decode('V2VsY29tZSB0byBhIG5ldyB3b3JsZCEgSW4gTW9uc3RlciBIdW50ZXI6IFdvcmxkLCB0aGUgbGF0ZXN0IGluc3RhbGxtZW50IGluIHRoZSBzZXJpZXMsIHlvdSBjYW4gZW5qb3kgdGhlIHVsdGltYXRlIGh1bnRpbmcgZXhwZXJpZW5jZSwgdXNpbmcgZXZlcnl0aGluZyBhdCB5b3VyIGRpc3Bvc2FsIHRvIGh1bnQgbW9uc3RlcnMgaW4gYSBuZXcgd29ybGQgdGVlbWluZyB3aXRoIHN1cnByaXNlcyBhbmQgZXhjaXRlbWVudC4='),
                'price' => 389000,
                'genre' => json_decode(base64_decode('WyJBa3NpIl0='), true),
                'developer' => base64_decode('Q0FQQ09NIENvLiwgTHRkLg=='),
                'publisher' => base64_decode('Q0FQQ09NIENvLiwgTHRkLg=='),
                'release_date' => date('Y-m-d', strtotime('8 Agu 2018')),
                'cover_image' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/582010/header.jpg?t=1773328926',
                'steam_appid' => '582010',
                'galleries' => json_decode(base64_decode('W3sidHlwZSI6ICJ2aWRlbyIsICJwYXRoIjogIiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzI1Njc2OTAyMi9tb3ZpZS4yOTN4MTY1LmpwZz90PTE1OTY1MjQ0MDYifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzU4MjAxMC9zc19hMjYyYzUzYjg2MjlkZTdjNjU0NzkzM2RjMGI0OWQzMWY0ZTFiMWYxLjE5MjB4MTA4MC5qcGc/dD0xNzczMzI4OTI2IiwgInRodW1ibmFpbCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvNTgyMDEwL3NzX2EyNjJjNTNiODYyOWRlN2M2NTQ3OTMzZGMwYjQ5ZDMxZjRlMWIxZjEuNjAweDMzOC5qcGc/dD0xNzczMzI4OTI2In0sIHsidHlwZSI6ICJpbWFnZSIsICJwYXRoIjogImh0dHBzOi8vc2hhcmVkLmFrYW1haS5zdGVhbXN0YXRpYy5jb20vc3RvcmVfaXRlbV9hc3NldHMvc3RlYW0vYXBwcy81ODIwMTAvc3NfNmI0OTg2YTM3YzdiNWMxODVhNzk2MDg1YzAwMmZlYmNkZDUzNTdiNS4xOTIweDEwODAuanBnP3Q9MTc3MzMyODkyNiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzU4MjAxMC9zc182YjQ5ODZhMzdjN2I1YzE4NWE3OTYwODVjMDAyZmViY2RkNTM1N2I1LjYwMHgzMzguanBnP3Q9MTc3MzMyODkyNiJ9LCB7InR5cGUiOiAiaW1hZ2UiLCAicGF0aCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvNTgyMDEwL3NzXzBkZmIyMGY2ZjA5YzE5NmJmYzMxN2JkNTE3ZGM0MzBlZDZlNmEyYTQuMTkyMHgxMDgwLmpwZz90PTE3NzMzMjg5MjYiLCAidGh1bWJuYWlsIjogImh0dHBzOi8vc2hhcmVkLmFrYW1haS5zdGVhbXN0YXRpYy5jb20vc3RvcmVfaXRlbV9hc3NldHMvc3RlYW0vYXBwcy81ODIwMTAvc3NfMGRmYjIwZjZmMDljMTk2YmZjMzE3YmQ1MTdkYzQzMGVkNmU2YTJhNC42MDB4MzM4LmpwZz90PTE3NzMzMjg5MjYifV0='), true),
                'dlcs_ids' => json_decode(base64_decode('WzExMTgwMTAsIDkxMjU0MF0='), true)
            ],
            [
                'title' => base64_decode('RklOQUwgRkFOVEFTWSBWSUkgUkVNQUtFIElOVEVSR1JBREU='),
                'description' => base64_decode('Q2xvdWQgU3RyaWZlLCBhbiBleC1TT0xESUVSIG9wZXJhdGl2ZSwgZGVzY2VuZHMgb24gdGhlIG1ha28tcG93ZXJlZCBjaXR5IG9mIE1pZGdhci4gVGhlIHdvcmxkIG9mIHRoZSB0aW1lbGVzcyBjbGFzc2ljIEZJTkFMIEZBTlRBU1kgVklJIGlzIHJlYm9ybiwgdXNpbmcgY3V0dGluZy1lZGdlIGdyYXBoaWNzIHRlY2hub2xvZ3ksIGEgbmV3IGJhdHRsZSBzeXN0ZW0gYW5kIGFuIGFkZGl0aW9uYWwgYWR2ZW50dXJlIGZlYXR1cmluZyBZdWZmaWUgS2lzYXJhZ2ku'),
                'price' => 579000,
                'genre' => json_decode(base64_decode('WyJBa3NpIiwgIlBldHVhbGFuZ2FuIiwgIlJQRyJd'), true),
                'developer' => base64_decode('U3F1YXJlIEVuaXg='),
                'publisher' => base64_decode('U3F1YXJlIEVuaXg='),
                'release_date' => date('Y-m-d', strtotime('17 Jun 2022')),
                'cover_image' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1462040/header.jpg?t=1788446328',
                'steam_appid' => '1462040',
                'galleries' => json_decode(base64_decode('W3sidHlwZSI6ICJ2aWRlbyIsICJwYXRoIjogIiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzI1Njg3ODg5OS9tb3ZpZS4yOTN4MTY1LmpwZz90PTE2NTU0MTY4MjMifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzE0NjIwNDAvc3NfMjBhMzUyYTJjMjBkZDRiZmIwOGZhMTMxZGM0YzJlNzYzNTEwZjU4NC4xOTIweDEwODAuanBnP3Q9MTc4ODQ0NjMyOCIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzE0NjIwNDAvc3NfMjBhMzUyYTJjMjBkZDRiZmIwOGZhMTMxZGM0YzJlNzYzNTEwZjU4NC42MDB4MzM4LmpwZz90PTE3ODg0NDYzMjgifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzE0NjIwNDAvc3NfNTU5MDNlNzQ3NTE2MDFlNDJmYjdmODU4ZDVjMDdlYzA0ODM4NmNlMi4xOTIweDEwODAuanBnP3Q9MTc4ODQ0NjMyOCIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzE0NjIwNDAvc3NfNTU5MDNlNzQ3NTE2MDFlNDJmYjdmODU4ZDVjMDdlYzA0ODM4NmNlMi42MDB4MzM4LmpwZz90PTE3ODg0NDYzMjgifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzE0NjIwNDAvc3NfYWM1NTMwNmQwMDkyM2YyOTAyZGUyMGJiNWFkZTdmOWNjMTkwZThjZi4xOTIweDEwODAuanBnP3Q9MTc4ODQ0NjMyOCIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzE0NjIwNDAvc3NfYWM1NTMwNmQwMDkyM2YyOTAyZGUyMGJiNWFkZTdmOWNjMTkwZThjZi42MDB4MzM4LmpwZz90PTE3ODg0NDYzMjgifV0='), true),
                'dlcs_ids' => json_decode(base64_decode('WzMzNjU2NTAsIDMzNjU2NzBd'), true)
            ],
            [
                'title' => base64_decode('U2VraXJv4oSiOiBTaGFkb3dzIERpZSBUd2ljZSAtIEdPVFkgRWRpdGlvbg=='),
                'description' => base64_decode('R2FtZSBvZiB0aGUgWWVhciAtIFRoZSBHYW1lIEF3YXJkcyAyMDE5IEJlc3QgQWN0aW9uIEdhbWUgb2YgMjAxOSAtIElHTiBDYXJ2ZSB5b3VyIG93biBjbGV2ZXIgcGF0aCB0byB2ZW5nZWFuY2UgaW4gdGhlIGF3YXJkIHdpbm5pbmcgYWR2ZW50dXJlIGZyb20gZGV2ZWxvcGVyIEZyb21Tb2Z0d2FyZSwgY3JlYXRvcnMgb2YgQmxvb2Rib3JuZSBhbmQgdGhlIERhcmsgU291bHMgc2VyaWVzLiBUYWtlIFJldmVuZ2UuIFJlc3RvcmUgWW91ciBIb25vci4gS2lsbCBJbmdlbmlvdXNseS4='),
                'price' => 891000,
                'genre' => json_decode(base64_decode('WyJBa3NpIiwgIlBldHVhbGFuZ2FuIl0='), true),
                'developer' => base64_decode('RnJvbVNvZnR3YXJlLCBJbmMu'),
                'publisher' => base64_decode('QWN0aXZpc2lvbiAoRXhjbHVkaW5nIEphcGFuIGFuZCBBc2lhKQ=='),
                'release_date' => date('Y-m-d', strtotime('21 Mar 2019')),
                'cover_image' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/814380/header.jpg?t=1762888662',
                'steam_appid' => '814380',
                'galleries' => json_decode(base64_decode('W3sidHlwZSI6ICJ2aWRlbyIsICJwYXRoIjogIiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzI1NjgwNjg5OS9tb3ZpZS4yOTN4MTY1LmpwZz90PTE2MDM4Mzc5NzkifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzgxNDM4MC9zc18wZjdiMGY4ZWQ5ZmZjNDlhYmEyNmY5MzI4Y2FhOWExZDU5YWQ2MGYwLjE5MjB4MTA4MC5qcGc/dD0xNzYyODg4NjYyIiwgInRodW1ibmFpbCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvODE0MzgwL3NzXzBmN2IwZjhlZDlmZmM0OWFiYTI2ZjkzMjhjYWE5YTFkNTlhZDYwZjAuNjAweDMzOC5qcGc/dD0xNzYyODg4NjYyIn0sIHsidHlwZSI6ICJpbWFnZSIsICJwYXRoIjogImh0dHBzOi8vc2hhcmVkLmFrYW1haS5zdGVhbXN0YXRpYy5jb20vc3RvcmVfaXRlbV9hc3NldHMvc3RlYW0vYXBwcy84MTQzODAvc3NfMjY4NWRkODQ0YTJhNTIzYjZjN2VjMjA3ZDQ2YTUzOGRiNmE5MDhjZC4xOTIweDEwODAuanBnP3Q9MTc2Mjg4ODY2MiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzgxNDM4MC9zc18yNjg1ZGQ4NDRhMmE1MjNiNmM3ZWMyMDdkNDZhNTM4ZGI2YTkwOGNkLjYwMHgzMzguanBnP3Q9MTc2Mjg4ODY2MiJ9LCB7InR5cGUiOiAiaW1hZ2UiLCAicGF0aCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvODE0MzgwL3NzXzE1ZjBlOTk4MjYyMWFlZDQ0OTAwMjE1YWQyODM4MTFhZjA3NzliMWQuMTkyMHgxMDgwLmpwZz90PTE3NjI4ODg2NjIiLCAidGh1bWJuYWlsIjogImh0dHBzOi8vc2hhcmVkLmFrYW1haS5zdGVhbXN0YXRpYy5jb20vc3RvcmVfaXRlbV9hc3NldHMvc3RlYW0vYXBwcy84MTQzODAvc3NfMTVmMGU5OTgyNjIxYWVkNDQ5MDAyMTVhZDI4MzgxMWFmMDc3OWIxZC42MDB4MzM4LmpwZz90PTE3NjI4ODg2NjIifV0='), true),
                'dlcs_ids' => json_decode(base64_decode('W10='), true)
            ],
            [
                'title' => base64_decode('SG9nd2FydHMgTGVnYWN5'),
                'description' => base64_decode('SG9nd2FydHMgTGVnYWN5IGlzIGFuIGltbWVyc2l2ZSwgb3Blbi13b3JsZCBhY3Rpb24gUlBHLiBOb3cgeW91IGNhbiB0YWtlIGNvbnRyb2wgb2YgdGhlIGFjdGlvbiBhbmQgYmUgYXQgdGhlIGNlbnRlciBvZiB5b3VyIG93biBhZHZlbnR1cmUgaW4gdGhlIHdpemFyZGluZyB3b3JsZC4='),
                'price' => 799000,
                'genre' => json_decode(base64_decode('WyJBa3NpIiwgIlBldHVhbGFuZ2FuIiwgIlJQRyJd'), true),
                'developer' => base64_decode('QXZhbGFuY2hlIFNvZnR3YXJl'),
                'publisher' => base64_decode('V2FybmVyIEJyb3MuIEdhbWVz'),
                'release_date' => date('Y-m-d', strtotime('10 Feb 2023')),
                'cover_image' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/990080/a3cdc6f40d97df8ac993679c2dd1edeb5222421e/header.jpg?t=1788224492',
                'steam_appid' => '990080',
                'galleries' => json_decode(base64_decode('W3sidHlwZSI6ICJ2aWRlbyIsICJwYXRoIjogIiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzI1NjkzMDUwNC83NDRiZjg0MjRhOGY1MTM1YjBiYjViMzEwZmY5NjgyNTY1NzYwZjNlL21vdmllXzYwMHgzMzcuanBnP3Q9MTc3ODc5NzQ3MiJ9LCB7InR5cGUiOiAiaW1hZ2UiLCAicGF0aCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvOTkwMDgwL3NzXzcyNWJmNTg0ODViZWI0YWEzN2EzYTY5YzFlMmJhYTY5YmYzZTQ2NTMuMTkyMHgxMDgwLmpwZz90PTE3ODgyMjQ0OTIiLCAidGh1bWJuYWlsIjogImh0dHBzOi8vc2hhcmVkLmFrYW1haS5zdGVhbXN0YXRpYy5jb20vc3RvcmVfaXRlbV9hc3NldHMvc3RlYW0vYXBwcy85OTAwODAvc3NfNzI1YmY1ODQ4NWJlYjRhYTM3YTNhNjljMWUyYmFhNjliZjNlNDY1My42MDB4MzM4LmpwZz90PTE3ODgyMjQ0OTIifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzk5MDA4MC9zc19kZjkzYjVlOGExODNmNzIzMmQ2OGJlOTRhZTc4OTIwYTkwZGUxNDQzLjE5MjB4MTA4MC5qcGc/dD0xNzg4MjI0NDkyIiwgInRodW1ibmFpbCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvOTkwMDgwL3NzX2RmOTNiNWU4YTE4M2Y3MjMyZDY4YmU5NGFlNzg5MjBhOTBkZTE0NDMuNjAweDMzOC5qcGc/dD0xNzg4MjI0NDkyIn0sIHsidHlwZSI6ICJpbWFnZSIsICJwYXRoIjogImh0dHBzOi8vc2hhcmVkLmFrYW1haS5zdGVhbXN0YXRpYy5jb20vc3RvcmVfaXRlbV9hc3NldHMvc3RlYW0vYXBwcy85OTAwODAvc3NfOTQwNTg0OTdiZjBmOGZhYmRkZTE3ZWU4ZDU5YmVjZTYwOWE2MDY2My4xOTIweDEwODAuanBnP3Q9MTc4ODIyNDQ5MiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzk5MDA4MC9zc185NDA1ODQ5N2JmMGY4ZmFiZGRlMTdlZThkNTliZWNlNjA5YTYwNjYzLjYwMHgzMzguanBnP3Q9MTc4ODIyNDQ5MiJ9XQ=='), true),
                'dlcs_ids' => json_decode(base64_decode('WzE4ODA4MzIsIDE4ODA4MzFd'), true)
            ],
            [
                'title' => base64_decode('RE9PTSBFdGVybmFs'),
                'description' => base64_decode('SGVsbOKAmXMgYXJtaWVzIGhhdmUgaW52YWRlZCBFYXJ0aC4gQmVjb21lIHRoZSBTbGF5ZXIgaW4gYW4gZXBpYyBzaW5nbGUtcGxheWVyIGNhbXBhaWduIHRvIGNvbnF1ZXIgZGVtb25zIGFjcm9zcyBkaW1lbnNpb25zIGFuZCBzdG9wIHRoZSBmaW5hbCBkZXN0cnVjdGlvbiBvZiBodW1hbml0eS4gVGhlIG9ubHkgdGhpbmcgdGhleSBmZWFyLi4uIGlzIHlvdS4='),
                'price' => 329999,
                'genre' => json_decode(base64_decode('WyJBa3NpIl0='), true),
                'developer' => base64_decode('aWQgU29mdHdhcmU='),
                'publisher' => base64_decode('QmV0aGVzZGEgU29mdHdvcmtz'),
                'release_date' => date('Y-m-d', strtotime('19 Mar 2020')),
                'cover_image' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/782330/feaf8293bcd2d078422faa547bc0d707c08f606e/header.jpg?t=1783432602',
                'steam_appid' => '782330',
                'galleries' => json_decode(base64_decode('W3sidHlwZSI6ICJ2aWRlbyIsICJwYXRoIjogIiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzI1NzE4MjEwMS84NDJkNGYwZTAwMmQ0MGI2ODgzM2NhMmRiMDEzNzA0NGJhMWMwNGJjL21vdmllXzYwMHgzMzcuanBnP3Q9MTc1NTEwOTkwOSJ9LCB7InR5cGUiOiAiaW1hZ2UiLCAicGF0aCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvNzgyMzMwL3NzXzRmOTNhN2M1MDAzZDQ5Y2IzMmY2YzBjNmU1NDc0NTJiMjg0NTgwYTAuMTkyMHgxMDgwLmpwZz90PTE3ODM0MzI2MDIiLCAidGh1bWJuYWlsIjogImh0dHBzOi8vc2hhcmVkLmFrYW1haS5zdGVhbXN0YXRpYy5jb20vc3RvcmVfaXRlbV9hc3NldHMvc3RlYW0vYXBwcy83ODIzMzAvc3NfNGY5M2E3YzUwMDNkNDljYjMyZjZjMGM2ZTU0NzQ1MmIyODQ1ODBhMC42MDB4MzM4LmpwZz90PTE3ODM0MzI2MDIifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzc4MjMzMC9zc183ZTZhMjE0ODMyMWM4MDI0Mjg1ZTM5MjQ5MDNkODg5N2NhYzk1MzU4LjE5MjB4MTA4MC5qcGc/dD0xNzgzNDMyNjAyIiwgInRodW1ibmFpbCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvNzgyMzMwL3NzXzdlNmEyMTQ4MzIxYzgwMjQyODVlMzkyNDkwM2Q4ODk3Y2FjOTUzNTguNjAweDMzOC5qcGc/dD0xNzgzNDMyNjAyIn0sIHsidHlwZSI6ICJpbWFnZSIsICJwYXRoIjogImh0dHBzOi8vc2hhcmVkLmFrYW1haS5zdGVhbXN0YXRpYy5jb20vc3RvcmVfaXRlbV9hc3NldHMvc3RlYW0vYXBwcy83ODIzMzAvc3NfYWYzYjQzYzRiZTAwMjliNTJjZWVmYWY1NWViZTE5MThlMmNiMzQ3MS4xOTIweDEwODAuanBnP3Q9MTc4MzQzMjYwMiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzc4MjMzMC9zc19hZjNiNDNjNGJlMDAyOWI1MmNlZWZhZjU1ZWJlMTkxOGUyY2IzNDcxLjYwMHgzMzguanBnP3Q9MTc4MzQzMjYwMiJ9XQ=='), true),
                'dlcs_ids' => json_decode(base64_decode('WzEwOTgyOTEsIDEwOTgyOTJd'), true)
            ],
            [
                'title' => base64_decode('UmVzaWRlbnQgRXZpbCA0'),
                'description' => base64_decode('U3Vydml2YWwgaXMganVzdCB0aGUgYmVnaW5uaW5nLiBTaXggeWVhcnMgaGF2ZSBwYXNzZWQgc2luY2UgdGhlIGJpb2xvZ2ljYWwgZGlzYXN0ZXIgaW4gUmFjY29vbiBDaXR5LiBMZW9uIFMuIEtlbm5lZHksIG9uZSBvZiB0aGUgc3Vydml2b3JzLCB0cmFja3MgdGhlIHByZXNpZGVudFwncyBraWRuYXBwZWQgZGF1Z2h0ZXIgdG8gYSBzZWNsdWRlZCBFdXJvcGVhbiB2aWxsYWdlLCB3aGVyZSB0aGVyZSBpcyBzb21ldGhpbmcgdGVycmlibHkgd3Jvbmcgd2l0aCB0aGUgbG9jYWxzLg=='),
                'price' => 519000,
                'genre' => json_decode(base64_decode('WyJBa3NpIiwgIlBldHVhbGFuZ2FuIl0='), true),
                'developer' => base64_decode('Q0FQQ09NIENvLiwgTHRkLg=='),
                'publisher' => base64_decode('Q0FQQ09NIENvLiwgTHRkLg=='),
                'release_date' => date('Y-m-d', strtotime('23 Mar 2023')),
                'cover_image' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/2050650/header.jpg?t=1772502922',
                'steam_appid' => '2050650',
                'galleries' => json_decode(base64_decode('W3sidHlwZSI6ICJ2aWRlbyIsICJwYXRoIjogIiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzI1Njk5ODEzMC9tb3ZpZS4yOTN4MTY1LmpwZz90PTE3MDc0NTU3NDkifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzIwNTA2NTAvc3NfNTlkMWIxOTk2NGNjNTMyMjEzZGY5MmM4Mjg3Yjc1YTBiZmZlYjMzYy4xOTIweDEwODAuanBnP3Q9MTc3MjUwMjkyMiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzIwNTA2NTAvc3NfNTlkMWIxOTk2NGNjNTMyMjEzZGY5MmM4Mjg3Yjc1YTBiZmZlYjMzYy42MDB4MzM4LmpwZz90PTE3NzI1MDI5MjIifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzIwNTA2NTAvc3NfYWI4MDdmOGFkOWU5NjhhNjIwNzc3Y2FmNDgzY2I2MDIwMzY3YjllZS4xOTIweDEwODAuanBnP3Q9MTc3MjUwMjkyMiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzIwNTA2NTAvc3NfYWI4MDdmOGFkOWU5NjhhNjIwNzc3Y2FmNDgzY2I2MDIwMzY3YjllZS42MDB4MzM4LmpwZz90PTE3NzI1MDI5MjIifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzIwNTA2NTAvc3NfMDQ0MmY3ZmI0MzI3ZDc5ODAyYzJkYjhlYThkMjNkMjI4YTI4ZDg5Ni4xOTIweDEwODAuanBnP3Q9MTc3MjUwMjkyMiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzIwNTA2NTAvc3NfMDQ0MmY3ZmI0MzI3ZDc5ODAyYzJkYjhlYThkMjNkMjI4YTI4ZDg5Ni42MDB4MzM4LmpwZz90PTE3NzI1MDI5MjIifV0='), true),
                'dlcs_ids' => json_decode(base64_decode('WzIxMDkzMDAsIDI1OTM2MDBd'), true)
            ],
            [
                'title' => base64_decode('SXQgVGFrZXMgVHdv'),
                'description' => base64_decode('RW1iYXJrIG9uIHRoZSBjcmF6aWVzdCBqb3VybmV5IG9mIHlvdXIgbGlmZSBpbiBJdCBUYWtlcyBUd28uIEludml0ZSBhIGZyaWVuZCB0byBqb2luIGZvciBmcmVlIHdpdGggRnJpZW5k4oCZcyBQYXNzIGFuZCB3b3JrIHRvZ2V0aGVyIGFjcm9zcyBhIGh1Z2UgdmFyaWV0eSBvZiBnbGVlZnVsbHkgZGlzcnVwdGl2ZSBnYW1lcGxheSBjaGFsbGVuZ2VzLiBXaW5uZXIgb2YgR0FNRSBPRiBUSEUgWUVBUiBhdCB0aGUgR2FtZSBBd2FyZHMgMjAyMS4='),
                'price' => 479000,
                'genre' => json_decode(base64_decode('WyJBa3NpIiwgIlBldHVhbGFuZ2FuIl0='), true),
                'developer' => base64_decode('SGF6ZWxpZ2h0IFN0dWRpb3M='),
                'publisher' => base64_decode('RWxlY3Ryb25pYyBBcnRz'),
                'release_date' => date('Y-m-d', strtotime('25 Mar 2021')),
                'cover_image' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1426210/header.jpg?t=1777395376',
                'steam_appid' => '1426210',
                'galleries' => json_decode(base64_decode('W3sidHlwZSI6ICJ2aWRlbyIsICJwYXRoIjogIiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzI1NjgyNzA5My9tb3ZpZS4yOTN4MTY1LmpwZz90PTE2MTY1MTQ1MzUifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzE0MjYyMTAvc3NfM2U1OTc1M2VlZmFiYTlhNzcwNGExOGU5MDJiNDhlOGQzOGU5NWUwYi4xOTIweDEwODAuanBnP3Q9MTc3NzM5NTM3NiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzE0MjYyMTAvc3NfM2U1OTc1M2VlZmFiYTlhNzcwNGExOGU5MDJiNDhlOGQzOGU5NWUwYi42MDB4MzM4LmpwZz90PTE3NzczOTUzNzYifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzE0MjYyMTAvc3NfNmU5ODdhMDY3OGIwMTNiZmQwMDczYTlhYzQ3MDNlMWYwNGNhNGRlYS4xOTIweDEwODAuanBnP3Q9MTc3NzM5NTM3NiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzE0MjYyMTAvc3NfNmU5ODdhMDY3OGIwMTNiZmQwMDczYTlhYzQ3MDNlMWYwNGNhNGRlYS42MDB4MzM4LmpwZz90PTE3NzczOTUzNzYifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzE0MjYyMTAvc3NfZmRhYzUyM2UzZWE0ZDJmMzJhNDQ0NDliYjhjMjI0ODU3NTYzYmQ3ZC4xOTIweDEwODAuanBnP3Q9MTc3NzM5NTM3NiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzE0MjYyMTAvc3NfZmRhYzUyM2UzZWE0ZDJmMzJhNDQ0NDliYjhjMjI0ODU3NTYzYmQ3ZC42MDB4MzM4LmpwZz90PTE3NzczOTUzNzYifV0='), true),
                'dlcs_ids' => json_decode(base64_decode('WzQxMTA5NDBd'), true)
            ],
            [
                'title' => base64_decode('RGV2aWwgTWF5IENyeSA1'),
                'description' => base64_decode('VGhlIHVsdGltYXRlIERldmlsIEh1bnRlciBpcyBiYWNrIGluIHN0eWxlLCBpbiB0aGUgZ2FtZSBhY3Rpb24gZmFucyBoYXZlIGJlZW4gd2FpdGluZyBmb3Iu'),
                'price' => 389000,
                'genre' => json_decode(base64_decode('WyJBa3NpIl0='), true),
                'developer' => base64_decode('Q0FQQ09NIENvLiwgTHRkLg=='),
                'publisher' => base64_decode('Q0FQQ09NIENvLiwgTHRkLg=='),
                'release_date' => date('Y-m-d', strtotime('7 Mar 2019')),
                'cover_image' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/601150/header.jpg?t=1768869803',
                'steam_appid' => '601150',
                'galleries' => json_decode(base64_decode('W3sidHlwZSI6ICJ2aWRlbyIsICJwYXRoIjogIiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzI1NjgxNTUxMS9tb3ZpZS4yOTN4MTY1LmpwZz90PTE2NjkxMzU4OTEifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzYwMTE1MC9zc180NDEwYmFkYTI1NjU4NDNkYWU2OTNiMDNhYzNhNTAyNTZmZjVkZDY2LjE5MjB4MTA4MC5qcGc/dD0xNzY4ODY5ODAzIiwgInRodW1ibmFpbCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvNjAxMTUwL3NzXzQ0MTBiYWRhMjU2NTg0M2RhZTY5M2IwM2FjM2E1MDI1NmZmNWRkNjYuNjAweDMzOC5qcGc/dD0xNzY4ODY5ODAzIn0sIHsidHlwZSI6ICJpbWFnZSIsICJwYXRoIjogImh0dHBzOi8vc2hhcmVkLmFrYW1haS5zdGVhbXN0YXRpYy5jb20vc3RvcmVfaXRlbV9hc3NldHMvc3RlYW0vYXBwcy82MDExNTAvc3NfNGNlMTgwZWQ4OTc5YTUxYzcyZGU1MWY5ODVlOWU5YmExMzUwMDUwOC4xOTIweDEwODAuanBnP3Q9MTc2ODg2OTgwMyIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzYwMTE1MC9zc180Y2UxODBlZDg5NzlhNTFjNzJkZTUxZjk4NWU5ZTliYTEzNTAwNTA4LjYwMHgzMzguanBnP3Q9MTc2ODg2OTgwMyJ9LCB7InR5cGUiOiAiaW1hZ2UiLCAicGF0aCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvNjAxMTUwL3NzX2UyYmU3MDU2NWY5NGE3ZjZjMzkyY2NjZGRjZTA4YzY3ZjJmODc2MTIuMTkyMHgxMDgwLmpwZz90PTE3Njg4Njk4MDMiLCAidGh1bWJuYWlsIjogImh0dHBzOi8vc2hhcmVkLmFrYW1haS5zdGVhbXN0YXRpYy5jb20vc3RvcmVfaXRlbV9hc3NldHMvc3RlYW0vYXBwcy82MDExNTAvc3NfZTJiZTcwNTY1Zjk0YTdmNmMzOTJjY2NkZGNlMDhjNjdmMmY4NzYxMi42MDB4MzM4LmpwZz90PTE3Njg4Njk4MDMifV0='), true),
                'dlcs_ids' => json_decode(base64_decode('WzE0MzI2NDAsIDE0MzI2NDFd'), true)
            ],
            [
                'title' => base64_decode('SEVMTERJVkVSU+KEoiAy'),
                'description' => base64_decode('VGhlIEdhbGF4eeKAmXMgTGFzdCBMaW5lIG9mIE9mZmVuY2UuIEVubGlzdCBpbiB0aGUgSGVsbGRpdmVycyBhbmQgam9pbiB0aGUgZmlnaHQgZm9yIGZyZWVkb20gYWNyb3NzIGEgaG9zdGlsZSBnYWxheHkgaW4gYSBmYXN0LCBmcmFudGljLCBhbmQgZmVyb2Npb3VzIHRoaXJkLXBlcnNvbiBzaG9vdGVyLg=='),
                'price' => 579000,
                'genre' => json_decode(base64_decode('WyJBa3NpIl0='), true),
                'developer' => base64_decode('QXJyb3doZWFkIEdhbWUgU3R1ZGlvcw=='),
                'publisher' => base64_decode('UGxheVN0YXRpb24gUHVibGlzaGluZyBMTEM='),
                'release_date' => date('Y-m-d', strtotime('8 Feb 2024')),
                'cover_image' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/553850/850e2f9e5bb15c5706bb9a1edc832f4f782e8be5/header.jpg?t=1788431424',
                'steam_appid' => '553850',
                'galleries' => json_decode(base64_decode('W3sidHlwZSI6ICJ2aWRlbyIsICJwYXRoIjogIiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzI1NzQxNjM4OC80MGJiMzU2YWRhYWJkMjVmZDczNDg4MjY0ODQwYWJlODRhYzg0NzE4L21vdmllXzYwMHgzMzcuanBnP3Q9MTc4ODQzMTQxMSJ9LCB7InR5cGUiOiAiaW1hZ2UiLCAicGF0aCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvNTUzODUwL2FlZDA4OTc5ZTE1ZjU0Nzk4ZjI4N2RkODgyMGZiNTgzODQ3NmI1ZmEvc3NfYWVkMDg5NzllMTVmNTQ3OThmMjg3ZGQ4ODIwZmI1ODM4NDc2YjVmYS4xOTIweDEwODAuanBnP3Q9MTc4ODQzMTQyNCIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzU1Mzg1MC9hZWQwODk3OWUxNWY1NDc5OGYyODdkZDg4MjBmYjU4Mzg0NzZiNWZhL3NzX2FlZDA4OTc5ZTE1ZjU0Nzk4ZjI4N2RkODgyMGZiNTgzODQ3NmI1ZmEuNjAweDMzOC5qcGc/dD0xNzg4NDMxNDI0In0sIHsidHlwZSI6ICJpbWFnZSIsICJwYXRoIjogImh0dHBzOi8vc2hhcmVkLmFrYW1haS5zdGVhbXN0YXRpYy5jb20vc3RvcmVfaXRlbV9hc3NldHMvc3RlYW0vYXBwcy81NTM4NTAvNzFlZjdjNjVmY2Y2M2ZmOTc0Y2U0MjE1MDE5MGMwNDIwYzY2YzcyYi9zc183MWVmN2M2NWZjZjYzZmY5NzRjZTQyMTUwMTkwYzA0MjBjNjZjNzJiLjE5MjB4MTA4MC5qcGc/dD0xNzg4NDMxNDI0IiwgInRodW1ibmFpbCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvNTUzODUwLzcxZWY3YzY1ZmNmNjNmZjk3NGNlNDIxNTAxOTBjMDQyMGM2NmM3MmIvc3NfNzFlZjdjNjVmY2Y2M2ZmOTc0Y2U0MjE1MDE5MGMwNDIwYzY2YzcyYi42MDB4MzM4LmpwZz90PTE3ODg0MzE0MjQifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzU1Mzg1MC82YzhiNjFhN2M2MzQ3OWI2ZjUyNmQyODVhZjY2ODdhMzYyZDVkN2FlL3NzXzZjOGI2MWE3YzYzNDc5YjZmNTI2ZDI4NWFmNjY4N2EzNjJkNWQ3YWUuMTkyMHgxMDgwLmpwZz90PTE3ODg0MzE0MjQiLCAidGh1bWJuYWlsIjogImh0dHBzOi8vc2hhcmVkLmFrYW1haS5zdGVhbXN0YXRpYy5jb20vc3RvcmVfaXRlbV9hc3NldHMvc3RlYW0vYXBwcy81NTM4NTAvNmM4YjYxYTdjNjM0NzliNmY1MjZkMjg1YWY2Njg3YTM2MmQ1ZDdhZS9zc182YzhiNjFhN2M2MzQ3OWI2ZjUyNmQyODVhZjY2ODdhMzYyZDVkN2FlLjYwMHgzMzguanBnP3Q9MTc4ODQzMTQyNCJ9XQ=='), true),
                'dlcs_ids' => json_decode(base64_decode('W10='), true)
            ],
            [
                'title' => base64_decode('U3RhcmRldyBWYWxsZXk='),
                'description' => base64_decode('WW91XCd2ZSBpbmhlcml0ZWQgeW91ciBncmFuZGZhdGhlclwncyBvbGQgZmFybSBwbG90IGluIFN0YXJkZXcgVmFsbGV5LiBBcm1lZCB3aXRoIGhhbmQtbWUtZG93biB0b29scyBhbmQgYSBmZXcgY29pbnMsIHlvdSBzZXQgb3V0IHRvIGJlZ2luIHlvdXIgbmV3IGxpZmUuIENhbiB5b3UgbGVhcm4gdG8gbGl2ZSBvZmYgdGhlIGxhbmQgYW5kIHR1cm4gdGhlc2Ugb3Zlcmdyb3duIGZpZWxkcyBpbnRvIGEgdGhyaXZpbmcgaG9tZT8='),
                'price' => 115999,
                'genre' => json_decode(base64_decode('WyJJbmRpZSIsICJSUEciLCAiU2ltdWxhc2kiXQ=='), true),
                'developer' => base64_decode('Q29uY2VybmVkQXBl'),
                'publisher' => base64_decode('Q29uY2VybmVkQXBl'),
                'release_date' => date('Y-m-d', strtotime('26 Feb 2016')),
                'cover_image' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/413150/header.jpg?t=1786554168',
                'steam_appid' => '413150',
                'galleries' => json_decode(base64_decode('W3sidHlwZSI6ICJ2aWRlbyIsICJwYXRoIjogIiIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzI1NjgxNTk2Ny9tb3ZpZS4yOTN4MTY1LmpwZz90PTE3NTQ2OTI4NjIifSwgeyJ0eXBlIjogImltYWdlIiwgInBhdGgiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzQxMzE1MC9zc19iODg3NjUxYTkzYjA1MjU3MzkwNDllYjQxOTRmNjMzZGUyZGY3NWJlLjE5MjB4MTA4MC5qcGc/dD0xNzg2NTU0MTY4IiwgInRodW1ibmFpbCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvNDEzMTUwL3NzX2I4ODc2NTFhOTNiMDUyNTczOTA0OWViNDE5NGY2MzNkZTJkZjc1YmUuNjAweDMzOC5qcGc/dD0xNzg2NTU0MTY4In0sIHsidHlwZSI6ICJpbWFnZSIsICJwYXRoIjogImh0dHBzOi8vc2hhcmVkLmFrYW1haS5zdGVhbXN0YXRpYy5jb20vc3RvcmVfaXRlbV9hc3NldHMvc3RlYW0vYXBwcy80MTMxNTAvc3NfOWFjODk5ZmUyY2RhMTVkNDhiMDU0OWJiYTc3ZWY4YzRhMDkwYTcxYy4xOTIweDEwODAuanBnP3Q9MTc4NjU1NDE2OCIsICJ0aHVtYm5haWwiOiAiaHR0cHM6Ly9zaGFyZWQuYWthbWFpLnN0ZWFtc3RhdGljLmNvbS9zdG9yZV9pdGVtX2Fzc2V0cy9zdGVhbS9hcHBzLzQxMzE1MC9zc185YWM4OTlmZTJjZGExNWQ0OGIwNTQ5YmJhNzdlZjhjNGEwOTBhNzFjLjYwMHgzMzguanBnP3Q9MTc4NjU1NDE2OCJ9LCB7InR5cGUiOiAiaW1hZ2UiLCAicGF0aCI6ICJodHRwczovL3NoYXJlZC5ha2FtYWkuc3RlYW1zdGF0aWMuY29tL3N0b3JlX2l0ZW1fYXNzZXRzL3N0ZWFtL2FwcHMvNDEzMTUwL3NzXzRmYTA4NjY3MDllZGUzNzUzZmRmMjc0NTM0OWI1MjhkNWU4YzQwNTQuMTkyMHgxMDgwLmpwZz90PTE3ODY1NTQxNjgiLCAidGh1bWJuYWlsIjogImh0dHBzOi8vc2hhcmVkLmFrYW1haS5zdGVhbXN0YXRpYy5jb20vc3RvcmVfaXRlbV9hc3NldHMvc3RlYW0vYXBwcy80MTMxNTAvc3NfNGZhMDg2NjcwOWVkZTM3NTNmZGYyNzQ1MzQ5YjUyOGQ1ZThjNDA1NC42MDB4MzM4LmpwZz90PTE3ODY1NTQxNjgifV0='), true),
                'dlcs_ids' => json_decode(base64_decode('WzQ0MDgyMF0='), true)
            ],
        ];

        foreach ($games as $g) {
            $game = Game::updateOrCreate(
                ['title' => $g['title']],
                [
                    'description' => $g['description'],
                    'price' => $g['price'],
                    'genre' => $g['genre'],
                    'developer' => $g['developer'],
                    'publisher' => $g['publisher'],
                    'release_date' => $g['release_date'] ?: now(),
                    'cover_image' => $g['cover_image'],
                    'steam_appid' => $g['steam_appid'],
                    'type' => 'game',
                    'is_active' => true,
                ]
            );

            // Galleries
            $game->galleries()->delete();
            foreach ($g['galleries'] as $gal) {
                if (!empty($gal['path'])) {
                    GameGallery::create([
                        'game_id' => $game->id,
                        'type' => $gal['type'],
                        'path' => $gal['path'],
                        'thumbnail' => $gal['thumbnail'] ?? null,
                    ]);
                }
            }

            // Dummy DLCs based on ids
            foreach ($g['dlcs_ids'] as $idx => $dlc_id) {
                Game::updateOrCreate(
                    ['title' => $g['title'] . ' - DLC Pack ' . ($idx + 1)],
                    [
                        'parent_id' => $game->id,
                        'description' => 'Ekspansi tambahan untuk ' . $g['title'],
                        'price' => floor($g['price'] * 0.3), // 30% of base price
                        'genre' => $g['genre'],
                        'developer' => $g['developer'],
                        'publisher' => $g['publisher'],
                        'release_date' => now(),
                        'cover_image' => $g['cover_image'], // fallback
                        'type' => 'dlc',
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
