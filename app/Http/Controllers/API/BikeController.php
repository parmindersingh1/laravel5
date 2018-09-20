<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Bike;
use Validator;
use App\Http\Resources\BikesResource;

class BikeController extends Controller
{
    /**
     * Protect update and delete methods, only for authenticated users.
     *
     * @return Unauthorized
     */
    public  function  __construct()
    {
        $this->middleware('auth:api')->except(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listBikes = Bike::all();
        return $listBikes;
        // Using Paginate method We explain this later in the book
        // return BikesResource::collection(Bike::with('ratings')->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'make' => 'required',
            'model' => 'required',
            'year'=> 'required',
            'mods'=> 'required',
            'builder_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $createBike = Bike::create($request->all());
        return new BikesResource($createBike);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Bike $bike)
    {
        return new BikesResource($bike);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bike $bike)
    {
         // check if currently authenticated user is the bike owner
         if ($request->user()->id !== $bike->user_id) {
            return response()->json(['error' => 'You can only edit your own bike.'], 403);
        }
        $bike->update($request->only(['make', 'model', 'year', 'mods', 'picture']));
        return new BikesResource($bike);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleteBikeById = Bike::find($id)->delete();
        return response()->json([], 204);
    }
}
