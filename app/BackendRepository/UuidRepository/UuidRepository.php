<?php

namespace App\BackendRepository\UuidRepository;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Str;

class UuidRepository extends Controller
{
	public function generateUuid($table_name)
	{
		$uuid = Str::uuid()->toString().mt_rand(5, 100000);
		$checkuuid = DB::table($table_name)->where('uuid',$uuid)->get()->first();
		if (!empty($checkuuid)) {
			$this->generateUuid($table_name);
		}
		else
		{
			return $uuid;
		}
	}

}