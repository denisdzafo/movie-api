<?php

namespace App\Http\Repository;

use App\Models\Movie;

class MovieRepository
{
    public function getAllMovies($searchTerm = null, $orderBy = 'asc', $orderProp = 'id', $perPage = 10, $categoryIds = [])
    {
        $query = Movie::with('categories')
            ->when($searchTerm, function ($q) use ($searchTerm) {
                $q->where('original_title', 'like', '%' . $searchTerm . '%');
            })
            ->when(!empty($categoryIds), function ($q) use ($categoryIds) {
                $q->whereHas('categories', function ($subQ) use ($categoryIds) {
                    $subQ->whereIn('category_id', $categoryIds);
                });
            })
            ->orderBy($orderProp, $orderBy);

        $movies = $query->paginate($perPage);

        return $movies;
    }

    public function addMovieToFavourite($movieId, $userId)
    {
        $movie = Movie::find($movieId);
        $movie->users()->attach($userId);
    }

    public function removeMovieFromFavourite($movieId, $userId)
    {
        $movie = Movie::find($movieId);
        $movie->users()->detach($userId);
    }

    public function getSingleMovie($movieId)
    {
        return Movie::find($movieId);
    }

}
