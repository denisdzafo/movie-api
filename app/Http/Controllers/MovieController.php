<?php

namespace App\Http\Controllers;

use App\Http\Repository\MovieRepository;
use App\Http\Requests\GetMoviesRequest;
use App\Http\Requests\IdRequest;
use App\Traits\GetAuthUser;
use App\Traits\ProcessRequest;

class MovieController extends Controller
{
    use GetAuthUser;

    use ProcessRequest;

    private $movieRepository;

    public function __construct(MovieRepository $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    public function getAllMovies(GetMoviesRequest $request)
    {
        $requestParameters = $this->processRequest($request);
        $movieDetails =  $this->movieRepository->getAllMovies($requestParameters['searchTerm'],
            $requestParameters['orderBy'],
            $requestParameters['orderProp'],
            $requestParameters['perPage'],
            $requestParameters['categories']);

        return response()->json($movieDetails, 200);
    }

    public function addMovieToFavourite(IdRequest $request)
    {
        $movieId = $request->id;
        $user = $this->getAuthUser();
        $this->movieRepository->addMovieToFavourite($movieId, $user->id);

        return response()->json(['status' => 'success'], 200);
    }

    public function removeMovieFromFavourite(IdRequest $request)
    {
        $movieId = $request->id;
        $user = $this->getAuthUser();
        $this->movieRepository->removeMovieFromFavourite($movieId, $user->id);

        return response()->json(['status' => 'success'], 200);
    }

    public function getSingleMovie(IdRequest $request)
    {
        $movieId = $request->id;
        $movie = $this->movieRepository->getSingleMovie($movieId);

        return response()->json(['data' => $movie], 200);
    }

    public function getMyFavouriteMovies()
    {
        $user = $this->getAuthUser();

        return response()->json(['data' => $user->movies], 200);
    }

}
