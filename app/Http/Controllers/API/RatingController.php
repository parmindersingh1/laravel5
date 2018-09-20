<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Bike;
use App\Rating;
use App\Http\Resources\RatingResource;

class RatingController extends Controller
{
   
    /**
    * Protect update and delete methods, only for authenticated         users.
    *
    * @return Unauthorized
    */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request, Bike $bike)
    {
      $rating = Rating::firstOrCreate(
        [
          'user_id' => $request->user()->id,
          'bike_id' => $bike->id,
        ],
        ['rating' => $request->rating]
      );
      return new RatingResource($rating);
    }

    
}
