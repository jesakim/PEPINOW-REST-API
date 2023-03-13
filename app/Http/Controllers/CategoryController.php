<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return response()->json(['categories' => Category::all()]);
    }

    /**
     * Store a newly created resource in storage.
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
            'massage'=>'user created successfully',
            'user'=>$category,
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response()->json([
            'category'=>$category,
        ],201);
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
