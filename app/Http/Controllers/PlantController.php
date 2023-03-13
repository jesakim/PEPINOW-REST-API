<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return response()->json(['plants' => Plant::all()]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required|string|max:255',
            'description'=>'required|string|max:255',
            'price'=>'required|integer',
            'category_id'=>'required|int|exists:categories,id',

        ]);

        if($validator->fails()){
                return response()->json($validator->errors(), 400);
        }

        // return $validator->validate();

        $plant = Plant::create($validator->validate());

        return response()->json([
            'massage'=>'plant created successfully',
            'plant'=>$plant,
            'category'=>$plant->category,
        ],201);
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Plant $plant)
    {
       return response()->json([
        'plant' => $plant,
        'category' => $plant->category,
    ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plant $plant)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required|string|max:255',
            'description'=>'required|string|max:255',
            'price'=>'required|integer',
            'category_id'=>'required|int|exists:categories,id',

        ]);

        if($validator->fails()){
                return response()->json($validator->errors(), 400);
        }

        // return $validator->validate();

        $plant->update($validator->validate());

        return response()->json([
            'massage'=>'plant updated successfully',
            'plant'=>$plant,
            'category'=>$plant->category,
        ],201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plant $plant)
    {
        $plant->delete();
        return response()->json([
            'massage'=>'plant Deleted successfully',
            'plant'=>$plant,
        ],200);
    }
}
