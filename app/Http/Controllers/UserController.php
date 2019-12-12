<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if(! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'logged' => false,
                    'message' => 'Invalid Email or Password'
                ]);
            }
        } catch (JWTException $e) {
            return response()->json([
                'logged' => false,
                'message' => 'Generate Token Failed'
            ]);
        }
        return response()->json([
            "logged" => true,
            "token" => $token,
            "message" => 'Login Berhasil'
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'email' => 'required|string|max:191|unique:users',
            'password' => 'required|string|min:3',
            'role' => 'required|string|in:admin,petugas'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 0,
                'message' => $validator->errors()->toJson()
            ]);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->save();

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'status' => '1',
            'message' => 'User Berhasil Melakukan Registrasi'
        ], 201);
    }

    public function getAuthenticatedUser() 
    {
        try {
            if(! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'auth' => false,
                    'message' => 'Invalid Token'
                ]);
            } 
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json([
                'auth' => false,
                'message' => 'Token Expired'
            ], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json([
                'auth' => false,
                'message' => 'Invalid Token'
            ], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json([
                'auth' => false,
                'message' => 'Token Absnet'
            ], $e->getStatusCode());
        }
        return response()->json([
            "auth" => true,
            "user" => $user,
            "Message" => 'Anda Berhasil Terregistrasi'
        ], 201);

    }

    public function index($limit = 10, $offset = 0)
  {
    $data["count"] = User::count();
    $user = array();

    foreach (User::take($limit)->skip($offset)->get() as $p) {
        $item = [
            "id"                => $p->id,
            "name"              => $p->name,
            "email"             => $p->email,
            "role"              => $p->role,
            "created_at"        => now(),    
            "updated_at"        => now(),    

            // "poin"              => $p->poins,
        ];

        array_push($user, $item);
    }
    $data["user"] = $user;
    $data["status"] = 1;
    return response($data);
  }

  public function store(Request $request)
  {
      $user = new User([
        'name'         => $request->name,
        'email'  => $request->email,
        'password' => $request->password,
        'role'       => $request->role,
      ]);

      $user->save();
      return response()->json([
        'status'  => '1',
        'message' => 'Petugas berhasil ditambahkan.'
      ]);

  }

  public function show($id)
  {
      $user = User::where('id', $id)->get();

      $datauser = array();
      foreach ($user as $p) {
          $item = [
            "id"                => $p->id,
            "name"              => $p->name,
            "role"              => $p->role,
            "email"             => $p->email,
            "created_at"        =>now(),
            "update_at"         =>now(),
          ];
          array_push($datauser, $item);
      }

      $data["datauser"] = $datauser;
      $data["status"] = 1;
      return response($data);

  }

  public function update($id, Request $request)
  {
      $user = User::where('id', $id)->first();

      $user->name           = $request->name;
      $user->email          = $request->email;
      $user->password       = $request->password;
      $user->role           = $request->role;

      $user->save();

      return response()->json([
        'status'  => '1',
        'message' => 'Petugas berhasil diubah.'
      ]);
  }

  public function destroy($id)
  {
      $user = User::where('id', $id)->first();

      $user->delete();

      return response()->json([
        'status'  => '1',
        'message' => 'Data petugas berhasil dihapus.'
      ]);
  }
}

