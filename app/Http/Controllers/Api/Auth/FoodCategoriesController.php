<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\FoodCategories;
use App\Models\Food;
use App\Models\FoodVariation;
use App\Models\Variations;
use Facade\Ignition\Tabs\Tab;
use Illuminate\Http\Request;
use App\BackendRepository\UuidRepository\UuidRepository;
use App\BackendRepository\ImageRepository\SaveImageRepository;
use App\BackendRepository\ImageRepository\DeleteImageRepository;
use Auth;

class FoodCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(UuidRepository $uuid, SaveImageRepository $saveImage, DeleteImageRepository $deleteImage)
    {
        $this->uuid = $uuid;
        $this->saveImage = $saveImage;
        $this->deleteImage = $deleteImage;
    }

    public function index(Request $request)
    {
        try {

            $food_categories = FoodCategories::paginate($request->paginator, ['*'], 'page', $request->page);
            foreach ($food_categories as $category) {
                $category['image_url'] = '/images/thumbnail/'.$category['image'];
            }
            if ($food_categories) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $food_categories
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found"
                ], 404);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get food_categories, please try again. {$exception->getMessage()}"
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $food_categories = new FoodCategories;
            if (Auth::user()->school_id != null) {
                $food_categories->school_id = Auth::user()->school_id;
            }
            else
            {
                $food_categories->school_id = Auth::user()->id;
            }
            $food_categories->category_name = $request['category_name'];
            $food_categories->image = $this->saveImage->saveImage($request);
            $food_categories->status = $request['status'];
            $food_categories->save();

            return response([
                'status' => 'success',
                'code' => 1,
                'data' => $food_categories
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store food_categories, please try again. {$exception->getMessage()}"
            ], 500);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search($search, Request $request)
    {
        try {
            $searchQuery = trim($search);
            $requestData = ['id','category_name','image','status','created_at','updated_at'];
            $food_categories = FoodCategories::where(function ($q) use ($requestData, $searchQuery) {
                foreach ($requestData as $field)
                    $q->orWhere($field, 'like', "%{$searchQuery}%");
            })->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($food_categories) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $food_categories
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found"
                ], 404);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get food_categories, please try again. {$exception->getMessage()}"
            ], 500);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $food_categories = FoodCategories::where('id', '=', $id)->first();
            $food_categories['image_url'] = '/images/thumbnail/'.$food_categories['image'];
            if ($food_categories) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $food_categories
                ], 200);
            } else {

                return response([
                    'status' => 'error',
                    'code' => 0,
                    'message' => "No record found"
                ], 404);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get food_categories data, please try again. {$exception->getMessage()}"
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        try {
            $food_categories = FoodCategories::where('id',$id)->get()->first();
            if (Auth::user()->school_id != null) {
                $food_categories->school_id = Auth::user()->school_id;
            }
            else
            {
                $food_categories->school_id = Auth::user()->id;
            }
            $food_categories->category_name = $request['category_name'];
           if ($request->hasFile('image')) 
           {
                $this->deleteImage->deleteImage($food_categories);
                $food_categories->image = $this->saveImage->saveImage($request);
           }
           $food_categories->status = $request['status'];

            $food_categories->save();
            if ($food_categories) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $food_categories
                ], 200);
            }
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update food_categories"
            ], 500);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update food_categories, please try again. {$exception->getMessage()}"
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $food_cat = FoodCategories::find($id);
            $this->deleteImage->deleteImage($food_cat);
            $res = $food_cat->delete();
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'message' => "Deleted successfully"
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "Failed to delete food_categories"
                ], 500);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to delete food_categories, please try again. {$exception->getMessage()}"
            ], 500);
        }
    }

    public function food_pos($id)
    {
        try
        {
            $food = Food::where('food_category_id', $id)->where('status', 'active')->get();
            foreach ($food as $f) {
                $f['image_url'] = '/images/thumbnail/'.$f['image'];
                $variations = FoodVariation::where('food_id', $f['id'])->get();
                foreach ($variations as $variation) {
                    $var = Variations::where('id', $variation['variation_id'])->get()->first();
                    $variation['variation_name'] = $var['variation_name'];
                    $variation['food_name'] = $f['food_name'];

                }
                $f['variations'] = $variations;
                


            }
            if (count($food) > 0) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $food
                ], 200);
            } else {
                return response([
                    'status' => 'error',
                    'code' => 0,
                    'data' => "No record found"
                ], 404);
            }
        }
        catch(\Exception $exception)
        {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to show foods, please try again. {$exception->getMessage()}"
            ], 500);

        }
    }
}

