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
     *     security={{"bearer_token":{}}},
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
     *     security={{"bearer_token":{}}},
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
            'image'=>'mimes:png,jpg,jpeg',
            'category_id'=>'required|int|exists:categories,id',

        ]);

        if($validator->fails()){
                return response()->json($validator->errors(), 400);
        }

        if($request->hasFile('image')){
            $image = $request->name.'_'.uniqid().'.'.$request->image->extension();
            $request->image->storeAs('public/images',$image);

        }else{
            $image = 'Default.png';

        }


        // return auth('api')->user()->id;

        $plant = Plant::create([
            "name"=>$request->name,
            "description"=>$request->description,
            "price"=>$request->price,
            "category_id"=>$request->category_id,
            "image"=>$image,
            "user_id"=>auth('api')->user()->id
        ]);

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
     *     security={{"bearer_token":{}}},
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
     *     security={{"bearer_token":{}}},
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
        if( $plant->user_id == auth("api")->user()->id || auth("api")->user()->role == 2){


        $validator = Validator::make($request->all(), [
            'name'=>'required|string|max:255',
            'description'=>'required|string|max:255',
            'price'=>'required|integer',
            'image'=>'mimes:png,jpg,jpeg',
            'category_id'=>'required|int|exists:categories,id',

        ]);

        if($validator->fails()){
                return response()->json($validator->errors(), 400);
        }

        if($request->hasFile('image')){
            $image = $request->name.'_'.uniqid().'.'.$request->image->extension();
            $request->image->storeAs('public/images',$image);

        }else{
            $image = $plant->image;
        }

        $plant->update([
            'name'=>$request->name,
            'description'=>$request->description,
            'price'=>$request->price,
            'category_id'=>$request->category_id,
            'image'=>$image,
        ]);

        return response()->json([
            'massage'=>'plant updated successfully',
            'plant'=>$plant,
            'category'=>$plant->category,
        ],201);
    }else{
        return response()->json([
            'massage'=>'not yours',
        ],403);
    }
    }

    /**
     * @OA\delete(
     *     path="/api/plant/1",
     *     tags={"Plants"},
     *     summary="delete Plant",
     *     operationId="destroyP",
     *     security={{"bearer_token":{}}},
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
        if( $plant->user_id == auth("api")->user()->id || auth("api")->user()->role == 2){
        $plant->delete();
        return response()->json([
            'massage'=>'plant Deleted successfully',
            'plant'=>$plant,
        ],200);
    }else{
        return response()->json([
            'massage'=>'not yours',
        ],403);
    }
    }
}
