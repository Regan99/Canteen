<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Illuminate\Support\Facades\Auth;
use App\Models\Users;
use Validator;
use App\Models\RolePermissions;
use Hash;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'libraryLogin']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->get('username'),
            'password' => $request->get('password')
        ];

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => $validator->errors()
            ], 422);
        }
        if (!$token = auth()->attempt($validator->validated())) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Invalid Credentials"
            ], 401);
        }

        $user = Users::where('email', $request['username'])->get()->first();
        $permission = RolePermissions::where('role_id', $user['role_id'])->get();

        return $this->createNewToken($token, $permission);
    }

    public function libraryLogin(Request $request)
    {
        $credentials = [
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ];

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $c = '';
        $user = Users::where('email', $request['email'])->get()->first();

        if(!$user || !Hash::check($request['password'], $user['password']))
        {
            abort(
                response()->json(['message' => 'Incorrect Credentials'], 401)
            );
        }

        $permission = RolePermissions::where('role_id', $user['role_id'])->get();


        foreach ($permission as $p) {
            if ($p['permission_name'] == 'Book') {
                $c = 'librarian';   
            }
        }
        if ($c == 'librarian') {
            $token = auth()->attempt($validator->validated());


                $response = [
                    'status' => 'success',
                    'code' => 1,
                    'message' => "Token Generated",
                    'data' => [
                        'access_token' => $token,
                        'token_type' => 'bearer',
                        'expires_in' => auth()->factory()->getTTL() * 60 * 60 * 60,
                        'user' => auth()->user()
                    ]
                ];
            return response($response, 200);
        }
        else
        {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Unauthorized"
            ], 401);
        }

    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
        
'uuid' => 'string',
'role_id' => 'required|integer',
'name' => 'required|string',
'email' => 'required|string|email|max:100|unique:users',
'email_verified_at' => 'date_format:Y-m-d H:i:s',
'password' => 'required|string|min:6',
'address' => 'required|string',
'contact_number' => 'required|string',
'image' => 'string',
'status' => 'required|string',
'discount' => 'string',
'school_id' => 'string',
'parent_id' => 'string',
'remember_token' => 'string',
'created_at' => 'date_format:Y-m-d H:i:s',
'updated_at' => 'date_format:Y-m-d H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = Users::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));
        return response([
            'status' => 'success',
            'code' => 1,
            'message' => "Users successfully registered",
            'data' => $user
        ], 201);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        try {
            return response([
                'status' => 'success',
                'code' => 1,
                'message' => "Token Generated",
                'data' => auth()->user()
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get user profile. {$exception->getMessage()}"
            ], 500);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token, $permission)
    {
        return response([
            'status' => 'success',
            'code' => 1,
            'message' => "Token Generated",
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60 * 60 * 60,
                'user' => auth()->user(),
                'permission' => $permission,
            ]
        ], 200);
    }
}



