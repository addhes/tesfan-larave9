<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'email|required',
                'password'  => 'required'
            ]);

            if($validator->fails()){
                return response()->json($validator->errors(), 400);
            }

            $credentials = request(['email', 'password']);
            if(!Auth::attempt($credentials)){
                return response()->json([
                    'meta' => [
                        'status' => 'Failed',
                        'message' => 'Unauthorized'
                    ],
                    'data' => '',
                ], 500);
            }

            $user = User::where('email', $request->email)->first();

            if(!Hash::check($request->password, $user->password, [])){
                throw new \Exception('Invalid Credentials');
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'meta' => [
                    'status' => 'Sukses',
                    'message' => 'Berhasil Login'
                ],
                'token' => $tokenResult,
                'data' => $user,
            ], 200);

        } catch (\Exception $error) {
            return response()->json([
                'meta' => [
                    'status' => '500',
                    'message' => 'Something went wrong',
                    'error' => $error,
                ],
                'data' => '',
            ]);
        }
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users',
                'npp' => 'string|required',
                'npp_supervisor' => 'string|required',
                'password' => 'required|string|min:8',
            ]);

            // dd($validator);

            if($validator->fails()){
                return response()->json($validator->errors(), 400);
            }

            User::create([
                'name'  => $request->name,
                'email' => $request->email,
                'npp' => $request->npp,
                'npp_supervisor' => $request->npp_supervisor,
                'password'  => Hash::make($request->password),
            ]);

            
            $user = User::where('email', $request->email)->first();
            
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'meta' => [
                    'status' => 'Sukses',
                    'message' => 'Berhasil registrasi'
                ],
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 200);


        } catch (\Exception $error) {
            return response()->json([
                'meta' => [
                    'status' => 'Failed',
                    'message' => 'Something went wrong',
                    'error' => $error
                ],
                'data' => '',
            ], 500);

        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();

        return response()->json([
            'meta' => [
                'status' => 'Sukses',
                'message' => 'Token Revoked',
            ],
            $token
        ], 200);
        // return ResponseFormatter::success($token, 'Token Revoked');
    }

    // public function updateProfile(Request $request)
    // {
    //     $validator = Validator::make($request->all(),[
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|max:255|unique:users',
    //         'handphone' => 'required|numeric|digits_between:9,14',
    //         'alamat' => 'string',
    //         'photo' => 'image|mimes:jpg,png,jpeg|max:2048',
    //         'password' => 'required|string|min:8',
    //     ]);

    //     // dd($validator);

    //     if($validator->fails()){
    //         return response()->json($validator->errors(), 400);
    //     }

    //     if($request->photo == ''){
    //         $image_path = '';
    //     } else {
    //         $file_ext = $request->file('photo')->getClientOriginalExtension();
    //         $file_name = 'avatar' . "-" . rand(1111, 9999) . "-" . Carbon::now()->format('dmY') . "." . $file_ext;
    //         $image_path = $request->file('photo')->storeAs('images/photo', $file_name);
    //     }

    //     $user = Auth::user();

    //     $user->update([
    //             'name'  => $request->name,
    //             'email' => $request->email,
    //             'handphone' => $request->handphone,
    //             'alamat' => $request->alamat,
    //             'photo' => $image_path,
    //             'password'  => Hash::make($request->password),
    //     ]);

    //     return ResponseFormatter::success($user, 'Profile Berhasil diupdate');
    // }
}
