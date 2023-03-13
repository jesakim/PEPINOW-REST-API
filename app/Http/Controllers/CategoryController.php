<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
   /**
     * @OA\Get(
     *     path="/api/category",
     *     tags={"Categories"},
     *     summary="Get all categories",
     *     operationId="index",
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
       return response()->json(['categories' => Category::all()]);
    }

    /**
     * @OA\Post(
     *     path="/api/category",
     *     tags={"Categories"},
     *     summary="Store new category",
     *     operationId="store",
     * @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Category name to be stored",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="category created successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid values"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category'=>'required|string|max:255'
        ]);

        if($validator->fails()){
                return response()->json($validator->errors(), 400);
        }

        $category = Category::create($request->only('category'));

        return response()->json([
            'massage'=>'category created successfully',
            'category'=>$category,
        ],201);
    }

     /**
     * @OA\get(
     *     path="/api/category/1",
     *     tags={"Categories"},
     *     summary="Get category with Id",
     *     operationId="show",
     *     @OA\Response(
     *         response=201,
     *         description="category created successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid values"
     *     )
     * )
     */
    public function show(Category $category)
    {
        return response()->json([
            'category'=>$category,
        ],201);
    }

    /**
     * @OA\Put(
     *     path="/api/category/1",
     *     tags={"Categories"},
     *     summary="Update category",
     *     operationId="update",
     *  @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Category name to be stored",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category updated successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Category not found"
     *     )
     * )
     */
    public function update(Request $request,Category $category)
    {
        $validator = Validator::make($request->all(), [
            'category'=>'required|string|max:255'
        ]);

        if($validator->fails()){
                return response()->json($validator->errors(), 400);
        }

        $category->update(
            $request->only('category')
        );

        return response()->json([
            'massage'=>'category Updated successfully',
            'category'=>$category,
        ],201);
    }

    /**
     * @OA\Delete(
     *     path="/api/category/1",
     *     tags={"Categories"},
     *     summary="delete category",
     *     operationId="destroy",
     *     @OA\Response(
     *         response=201,
     *         description="category deleted successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Category not found"
     *     )
     * )
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([
            'massage'=>'category Deleted successfully',
            'category'=>$category,
        ],200);
    }
}
