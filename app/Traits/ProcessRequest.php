<?php


namespace App\Traits;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;

trait ProcessRequest
{
    public function processRequest($request)
    {
        $requestParameters = [];

        $requestParameters['searchTerm'] = null;
        if($request->searchTerm)
            $requestParameters['searchTerm'] = $request->searchTerm;

        $requestParameters['orderBy'] = 'asc';
        if($request->orderBy)
            $requestParameters['orderBy'] = $request->orderBy;

        $requestParameters['orderProp'] = 'id';
        if($request->orderProp)
            $requestParameters['orderProp'] = $request->orderProp;

        $requestParameters['perPage'] = 10;
        if($request->perPage)
            $requestParameters['perPage'] = $request->perPage;

        $requestParameters['categories'] = [];
        if($request->categories)
            $requestParameters['categories'] = $request->categories;

        return $requestParameters;
    }
}
