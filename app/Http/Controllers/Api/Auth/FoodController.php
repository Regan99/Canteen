<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Users;
use App\Models\FoodCategories;
use App\Models\Variations;
use App\Models\FoodVariation;
use Facade\Ignition\Tabs\Tab;
use Illuminate\Http\Request;
use App\BackendRepository\UuidRepository\UuidRepository;
use App\BackendRepository\ImageRepository\SaveImageRepository;
use App\BackendRepository\ImageRepository\DeleteImageRepository;
use Auth;

class FoodController extends Controller
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

            $food = Food::paginate($request->paginator, ['*'], 'page', $request->page);
            foreach ($food as $f) {
                $school = Users::where('id',$f['school_id'])->get()->first();
                $food_cat = FoodCategories::where('id', $f['food_category_id'])->get()->first();
                $f['image_url'] = '/images/thumbnail/'.$f['image'];
                $f['school_name'] = $school['name'];
                $f['category_name'] = $food_cat['category_name'];
            }
            if ($food) {
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
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get food, please try again. {$exception->getMessage()}"
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
            $food = new Food;
            $food->food_category_id = $request['food_category_id'];
            if (Auth::user()->school_id != null) {
                $food->school_id = Auth::user()->school_id;
            }
            else
            {
                $food->school_id = Auth::user()->id;
            }
            $food->food_name = $request['food_name'];
            $food->image = $this->saveImage->saveImage($request);
            $food->status = $request['status'];
            $variations = json_decode($request['variation'], true);
            $food->save();
            
            foreach ($variations as $variation) {
                $food_variation = new FoodVariation;
                $food_variation->food_id = $food['id'];
                $food_variation->variation_id = $variation['variation_id'];
                $food_variation->price = $variation['price'];
                $food_variation->discount = $variation['discount'];
                $food_variation->save();

            }
            

            return response([
                'status' => 'success',
                'code' => 1,
                'data' => $food
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store food, please try again. {$exception->getMessage()}"
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
            $requestData = ['id','food_category_id','variation_id','school_id','food_name','image','price','discount','status','created_at','updated_at'];
            $food = Food::where(function ($q) use ($requestData, $searchQuery) {
                foreach ($requestData as $field)
                    $q->orWhere($field, 'like', "%{$searchQuery}%");
            })->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($food) {
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
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to get food, please try again. {$exception->getMessage()}"
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
            $food = Food::where('id', '=', $id)->first();
            $food_category = FoodCategories::where('id', $food['food_category_id'])->get()->first();
            $food['food_category_name'] = $food_category['category_name'];
            $variation = FoodVariation::where('food_id', $food['id'])->get();
            foreach ($variation as $v) {
                $var = Variations::where('id', $v['variation_id'])->get()->first();
                $v['variation_name'] = $var['variation_name'];
            }
            if ($food) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $food,
                    'variation' => $variation
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
                'message' => "Failed to get food data, please try again. {$exception->getMessage()}"
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
            $input = $request->all();

            $food = Food::find($id);

           $food->food_category_id = $input['food_category_id'];
           $food->variation_id = $input['variation_id'];
           if (Auth::user()->school_id != null) {
                $food->school_id = Auth::user()->school_id;
            }
            else
            {
                $food->school_id = Auth::user()->id;
            }
           $food->food_name = $input['food_name'];
           if ($request->hasFile('image')) 
           {
                $this->deleteImage->deleteImage($food);
                $food->image = $this->saveImage->saveImage($request);
           }
           $food->price = $input['price'];
           $food->status = $input['status'];

            $res = $food->update();

            $food_var = FoodVariation::where('food_id', $food['id'])->get();
            foreach ($food_var as $f) {
                $f->delete();
            }

            foreach ($request['variation'] as $variation) {
                $food_variation = new FoodVariation;
                $food_variation->food_id = $food['id'];
                $food_variation->variation_id = $variation['variation_id'];
                $food_variation->price = $variation['price'];
                $food_variation->discount = $variation['discount'];
                $food_variation->save();

            }
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $food
                ], 200);
            }
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update food"
            ], 500);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update food, please try again. {$exception->getMessage()}"
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
            $food = Food::find($id);
            $this->deleteImage->deleteImage($food);
            $variation = FoodVariation::where('food_id',$food['id'])->get();
            foreach ($variation as $v) {
                $v->delete();
            }
            $res = $food->delete();
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
                    'data' => "Failed to delete food"
                ], 500);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to delete food, please try again. {$exception->getMessage()}"
            ], 500);
        }
    }
}

