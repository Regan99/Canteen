<?php

namespace App\BackendRepository\ImageRepository;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use File;
use Image;

class SaveImageRepository extends Controller
{
	public function saveImage($request)
	{
		if ($request->hasFile('image')) {
			$this->createDirectory();
			$file = $request['image'];
			$image = Image::make($file);
			$imagename = time().'-'.mt_rand(5, 100000).'.'.$file->getClientOriginalExtension();
			$image->save($this->imagePath.$imagename);
			$image->resize(400,400);
			$image->save($this->thumbnailPath.$imagename);
			return $imagename;
		}
	}

	public function createDirectory()
	{
		$paths = [
			'image_path' => public_path('images/'),
			'thumbnail_path' => public_path('images/thumbnail/')
		];
		foreach ($paths as $path) {
			if (!File::isDirectory($path)) 
			{
				File::makeDirectory($path,077,true,true);
			}
			$this->imagePath = $paths['image_path'];
			$this->thumbnailPath = $paths['thumbnail_path'];
		}
	}

}