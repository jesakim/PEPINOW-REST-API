<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlantController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/plant",
     *     tags={"Plants"},
     *     summary="Get all Plants",
     *     operationId="get",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     )
     * )
     */
    public function index()
    {
       return response()->json(['plants' => Plant::all()]);

    }

   /**
     * @OA\post(
     *     path="/api/plant",
     *     tags={"Plants"},
     *     summary="Store new Plant",
     *     operationId="storep",
     * @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Plant name to be stored",
     *         required=true,
     *     ),
     * @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="plant description to be stored",
     *         required=true,
     *     ),
     * @OA\Parameter(
     *         name="price",
     *         in="query",
     *         description="plant price to be stored",
     *         required=true,
     *     ),
     * @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Plant category id to be stored",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     )
     * )
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
     * @OA\get(
     *     path="/api/plant/1",
     *     tags={"Plants"},
     *     summary="Get plant",
     *     operationId="showp",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     )
     * )
     */
    public function show(Plant $plant)
    {
       return response()->json([
        'plant' => $plant,
        'category' => $plant->category,
    ]);

    }

    /**
     * @OA\put(
     *     path="/api/plant/1",
     *     tags={"Plants"},
     *     summary="Update Plant",
     *     operationId="updatep",
     * * @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Plant name to be updated",
     *         required=true,
     *     ),
     * @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="plant description to be updated",
     *         required=true,
     *     ),
     * @OA\Parameter(
     *         name="price",
     *         in="query",
     *         description="plant price to be updated",
     *         required=true,
     *     ),
     * @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Plant category id to be updated",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     )
     * )
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
     * @OA\delete(
     *     path="/api/plant/1",
     *     tags={"Plants"},
     *     summary="delete Plant",
     *     operationId="destroyP",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     )
     * )
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
