<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\RolePermissions;
use Illuminate\Support\Facades\Auth;
use Hash;
use Validator;

class LibraryController extends Controller
{
    public function libraryLogin(Request $request)
    {
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
			return $this->createNewToken($token);

		    // 	$response = [
		    // 		'user' => $user,
		    // 		'token' => $token
		    // 	];
		    // return response($response, 200);
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
}
