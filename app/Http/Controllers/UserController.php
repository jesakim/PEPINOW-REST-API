<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    /**
     * @OA\post(
     *     path="/api/login",
     *     tags={"User"},
     *     summary="Login",
     *     operationId="authenticate",
     *   @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User email",
     *         required=true,
     *     ),
     *  @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User password",
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
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|exists:users',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $credentials = $request->only('email', 'password');

            if (! $token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }


        return response()->json(compact('token'));
    }


    /**
     * @OA\post(
     *     path="/api/register",
     *     tags={"User"},
     *     summary="Register",
     *     operationId="register",
     *  @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="User name",
     *         required=true,
     *     ),
     * @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User email",
     *         required=true,
     *     ),
     * @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User password",
     *         required=true,
     *     ),
     * @OA\Parameter(
     *         name="password_confirmation",
     *         in="query",
     *         description="User password_confirmation",
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
    public function register(Request $request)
    {
            $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
                return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        return response()->json([
            'massage'=>'user created successfully',
            'user'=>$user,
        ],201);
    }

    public function getAuthenticatedUser()
        {
            return response()->json([
                'user'=>auth()->user()
            ]);
        }
    public function logout(){
        auth()->logout();
        return response()->json([
            'message'=>'user LoggedOut successfully'
        ]);
    }
    public function refresh(){
        return response()->json([
            'user'=>auth()->refresh()
        ]);

    }
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        // dd($user);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email,'.$user->id,
            'password' => 'nullable|string|confirmed|min:5',
        ]);

        if($validator->fails()){
                return response()->json($validator->errors(), 400);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return response()->json([
            'message' => 'User profile updated successfully',
            'user' => $user,
        ]);
    }

    public function changeRole(Request $request, $user_id){
        $user = User::find($user_id);
        if($user==Null){
            return response()->json([
                'message' => 'User Not Found'
            ], 404);
        }

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'role' => 'required|integer|in:0,1,2',
        ]);

        if($validator->fails()){
                return response()->json($validator->errors(), 400);
        }

        $user->role = intval($request->get('role'));
        $user->save();

        return response()->json([
            'message' => 'User role has been updated',
            'user'=>$user
        ], 200);
    }
}
