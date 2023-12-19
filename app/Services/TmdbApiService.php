<?php

namespace App\Services;

use GuzzleHttp\Client;

class TmdbApiService
{
    protected $baseUrl = 'https://api.themoviedb.org/3/';
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.api_key');
    }

    public function getAllGenres()
    {
        $url = $this->baseUrl . "genre/movie/list?api_key=$this->apiKey";

        $client = new Client();
        $response = $client->get($url);

        return json_decode($response->getBody(), true);
    }

    public function getTopMoviesByGenre()
    {
        $topMoviesByGenre = [];

        foreach ($this->getAllGenres()['genres'] as $genre) {
            $genreId = $genre['id'];

            $url = $this->baseUrl . "discover/movie?api_key={$this->apiKey}&with_genres={$genreId}&sort_by=popularity.desc&page=1";

            $client = new Client();
            $response = $client->get($url);
            $movies = json_decode($response->getBody(), true);

            $topMoviesByGenre[$genreId] = array_slice($movies['results'], 0, 20);
        }

        return $topMoviesByGenre;
    }
}
