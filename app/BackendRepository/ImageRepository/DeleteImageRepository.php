<?php

namespace App\BackendRepository\ImageRepository;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use File;
use Image;

class DeleteImageRepository extends Controller
{

	public function deleteImage($image)
	{
		$paths = [
		  'image_path' => public_path('images/'),
		  'thumnail_image_path' => public_path('images/thumbnail/')
		];

		foreach ($paths as $path) {
		  if (file_exists($path.$image['image'])) {
		    unlink($path.$image['image']);
		  }
		}

	}

}