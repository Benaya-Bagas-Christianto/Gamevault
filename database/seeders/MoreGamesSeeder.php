<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;

class MoreGamesSeeder extends Seeder
{
    public function run()
    {
        // Fix GTA V price if it exists
        $gta = Game::where('title', 'like', '%Grand Theft Auto V%')->first();
        if ($gta) {
            $gta->update(['price' => 350000]);
        }

        $games = [
            ['title' => 'The Last of Us Part I', 'price' => 879000, 'genre' => ['Action', 'Adventure', 'Story Rich'], 'dev' => 'Naughty Dog'],
            ['title' => 'Horizon Zero Dawn', 'price' => 729000, 'genre' => ['Action', 'RPG', 'Open World'], 'dev' => 'Guerrilla Games'],
            ['title' => 'Death Stranding', 'price' => 589000, 'genre' => ['Action', 'Adventure', 'Open World'], 'dev' => 'Kojima Productions'],
            ['title' => 'Ghost of Tsushima', 'price' => 879000, 'genre' => ['Action', 'Adventure', 'Open World'], 'dev' => 'Sucker Punch'],
            ['title' => 'Spider-Man Remastered', 'price' => 879000, 'genre' => ['Action', 'Adventure', 'Open World'], 'dev' => 'Insomniac Games'],
            ['title' => 'Days Gone', 'price' => 729000, 'genre' => ['Action', 'Adventure', 'Zombies'], 'dev' => 'Bend Studio'],
            ['title' => 'Uncharted 4', 'price' => 729000, 'genre' => ['Action', 'Adventure', 'Story Rich'], 'dev' => 'Naughty Dog'],
            ['title' => 'Detroit: Become Human', 'price' => 589000, 'genre' => ['Adventure', 'Story Rich', 'Choices Matter'], 'dev' => 'Quantic Dream'],
            ['title' => 'Heavy Rain', 'price' => 279000, 'genre' => ['Adventure', 'Story Rich'], 'dev' => 'Quantic Dream'],
            ['title' => 'Beyond Two Souls', 'price' => 279000, 'genre' => ['Adventure', 'Story Rich'], 'dev' => 'Quantic Dream'],
            ['title' => 'Final Fantasy VII Remake', 'price' => 1029000, 'genre' => ['RPG', 'Action', 'Fantasy'], 'dev' => 'Square Enix'],
            ['title' => 'Final Fantasy XV', 'price' => 589000, 'genre' => ['RPG', 'Action', 'Open World'], 'dev' => 'Square Enix'],
            ['title' => 'NieR: Automata', 'price' => 589000, 'genre' => ['RPG', 'Action', 'Hack and Slash'], 'dev' => 'PlatinumGames'],
            ['title' => 'Persona 5 Royal', 'price' => 798000, 'genre' => ['RPG', 'Turn-Based', 'Anime'], 'dev' => 'ATLUS'],
            ['title' => 'Yakuza 0', 'price' => 260000, 'genre' => ['Action', 'Adventure', 'Story Rich'], 'dev' => 'Ryu Ga Gotoku Studio'],
            ['title' => 'Yakuza Kiwami', 'price' => 260000, 'genre' => ['Action', 'Adventure'], 'dev' => 'Ryu Ga Gotoku Studio'],
            ['title' => 'Judgment', 'price' => 589000, 'genre' => ['Action', 'Adventure', 'Detective'], 'dev' => 'Ryu Ga Gotoku Studio'],
            ['title' => 'Dragon Quest XI', 'price' => 589000, 'genre' => ['RPG', 'Turn-Based', 'Fantasy'], 'dev' => 'Square Enix'],
            ['title' => 'Monster Hunter Rise', 'price' => 589000, 'genre' => ['Action', 'RPG', 'Hunting'], 'dev' => 'Capcom'],
            ['title' => 'Devil May Cry 5', 'price' => 450000, 'genre' => ['Action', 'Hack and Slash'], 'dev' => 'Capcom'],
            ['title' => 'Street Fighter 6', 'price' => 830000, 'genre' => ['Action', 'Fighting', 'Competitive'], 'dev' => 'Capcom'],
            ['title' => 'Tekken 8', 'price' => 999000, 'genre' => ['Action', 'Fighting', 'Competitive'], 'dev' => 'Bandai Namco'],
            ['title' => 'Mortal Kombat 1', 'price' => 999000, 'genre' => ['Action', 'Fighting', 'Gore'], 'dev' => 'NetherRealm'],
            ['title' => 'Guilty Gear Strive', 'price' => 589000, 'genre' => ['Action', 'Fighting', 'Anime'], 'dev' => 'Arc System Works'],
            ['title' => 'Dragon Ball FighterZ', 'price' => 589000, 'genre' => ['Action', 'Fighting', 'Anime'], 'dev' => 'Arc System Works'],
            ['title' => 'Doom Eternal', 'price' => 589000, 'genre' => ['Action', 'FPS', 'Gore'], 'dev' => 'id Software'],
            ['title' => 'Wolfenstein II', 'price' => 589000, 'genre' => ['Action', 'FPS', 'Story Rich'], 'dev' => 'MachineGames'],
            ['title' => 'Prey', 'price' => 450000, 'genre' => ['Action', 'FPS', 'Sci-Fi'], 'dev' => 'Arkane Studios'],
            ['title' => 'Dishonored 2', 'price' => 450000, 'genre' => ['Action', 'Stealth', 'Story Rich'], 'dev' => 'Arkane Studios'],
            ['title' => 'Deathloop', 'price' => 879000, 'genre' => ['Action', 'FPS', 'Stealth'], 'dev' => 'Arkane Studios'],
            ['title' => 'Fallout 4', 'price' => 260000, 'genre' => ['RPG', 'Open World', 'Post-apocalyptic'], 'dev' => 'Bethesda'],
            ['title' => 'Skyrim Special Edition', 'price' => 589000, 'genre' => ['RPG', 'Open World', 'Fantasy'], 'dev' => 'Bethesda'],
            ['title' => 'The Outer Worlds', 'price' => 589000, 'genre' => ['RPG', 'Open World', 'Sci-Fi'], 'dev' => 'Obsidian'],

            ['title' => 'The Witcher 2', 'price' => 130000, 'genre' => ['RPG', 'Action', 'Fantasy'], 'dev' => 'CD Projekt Red'],
            ['title' => 'Mass Effect Legendary', 'price' => 879000, 'genre' => ['RPG', 'Action', 'Sci-Fi'], 'dev' => 'BioWare'],
            ['title' => 'Dragon Age Inquisition', 'price' => 589000, 'genre' => ['RPG', 'Fantasy', 'Story Rich'], 'dev' => 'BioWare'],
            ['title' => 'Dead Space Remake', 'price' => 879000, 'genre' => ['Action', 'Horror', 'Sci-Fi'], 'dev' => 'Motive'],
            ['title' => 'Star Wars Jedi Survivor', 'price' => 879000, 'genre' => ['Action', 'Adventure', 'Sci-Fi'], 'dev' => 'Respawn'],
            ['title' => 'Battlefield 2042', 'price' => 879000, 'genre' => ['Action', 'FPS', 'Multiplayer'], 'dev' => 'DICE'],
            ['title' => 'Titanfall 2', 'price' => 450000, 'genre' => ['Action', 'FPS', 'Sci-Fi'], 'dev' => 'Respawn'],
            ['title' => 'Apex Legends', 'price' => 0, 'genre' => ['Action', 'FPS', 'Battle Royale'], 'dev' => 'Respawn'],
            ['title' => 'Overwatch 2', 'price' => 0, 'genre' => ['Action', 'FPS', 'Hero Shooter'], 'dev' => 'Blizzard'],
            ['title' => 'World of Warcraft', 'price' => 0, 'genre' => ['MMORPG', 'Fantasy', 'Multiplayer'], 'dev' => 'Blizzard'],
            ['title' => 'Destiny 2', 'price' => 0, 'genre' => ['Action', 'FPS', 'Looter Shooter'], 'dev' => 'Bungie'],
            ['title' => 'Warframe', 'price' => 0, 'genre' => ['Action', 'Looter Shooter', 'Sci-Fi'], 'dev' => 'Digital Extremes'],
            ['title' => 'Path of Exile', 'price' => 0, 'genre' => ['Action RPG', 'Hack and Slash', 'Dark Fantasy'], 'dev' => 'Grinding Gear Games'],
            ['title' => 'Genshin Impact', 'price' => 0, 'genre' => ['RPG', 'Action', 'Anime'], 'dev' => 'HoYoverse'],
            ['title' => 'Honkai Star Rail', 'price' => 0, 'genre' => ['RPG', 'Turn-Based', 'Anime'], 'dev' => 'HoYoverse'],
            ['title' => 'Zenless Zone Zero', 'price' => 0, 'genre' => ['Action', 'RPG', 'Anime'], 'dev' => 'HoYoverse']
        ];

        foreach ($games as $g) {
            Game::updateOrCreate(
                ['title' => $g['title']],
                [
                    'description' => 'Ini adalah game ' . $g['title'] . ' yang luar biasa, dikembangkan oleh ' . $g['dev'] . '.',
                    'price' => $g['price'],
                    'genre' => $g['genre'],
                    'developer' => $g['dev'],
                    'publisher' => $g['dev'],
                    'release_date' => now()->subDays(rand(1, 1000))->format('Y-m-d'),
                    'type' => 'game',
                ]
            );
        }
    }
}
