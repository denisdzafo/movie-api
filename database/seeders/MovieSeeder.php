<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Movie;
use App\Models\Category;
use App\Services\TmdbApiService;

class MovieSeeder extends Seeder
{
    protected $tmdbApiService;

    public function __construct(TmdbApiService $tmdbApiService)
    {
        $this->tmdbApiService = $tmdbApiService;
    }

    public function run()
    {
        try {
            $topMoviesByGenre = $this->tmdbApiService->getTopMoviesByGenre();
            foreach ($topMoviesByGenre as $genreId => $movies) {
                foreach ($movies as $movieData) {
                    try {
                        $movie = Movie::create([
                            'id' => $movieData['id'],
                            'original_title' => $movieData['original_title'],
                            'overview' => $movieData['overview'],
                            'poster_path' => $movieData['poster_path'],
                            'release_date' => $movieData['release_date'],
                        ]);

                        $categoryIds = $movieData['genre_ids'];
                        $categories = Category::whereIn('id', $categoryIds)->get();
                        $movie->categories()->sync($categories);
                    } catch (\Exception $e) {

                        \Log::error("Error seeding movie with ID {$movieData['id']}: {$e->getMessage()}");
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error("Error fetching top movies by genre from TMDb: {$e->getMessage()}");
        }
    }
}
