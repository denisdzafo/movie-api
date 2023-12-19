<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Services\TmdbApiService;

class CategorySeeder extends Seeder
{
    protected $tmdbApiService;

    public function __construct(TmdbApiService $tmdbApiService)
    {
        $this->tmdbApiService = $tmdbApiService;
    }

    public function run()
    {
        $tmdbGenres = $this->tmdbApiService->getAllGenres();

        foreach ($tmdbGenres['genres'] as $genre) {
            Category::create([
                'id' => $genre['id'],
                'name' => $genre['name'],
            ]);
        }
    }
}
