<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Books;
use App\Models\BookCategories;
use Facade\Ignition\Tabs\Tab;
use Illuminate\Http\Request;
use App\BackendRepository\UuidRepository\UuidRepository;
use App\BackendRepository\ImageRepository\SaveImageRepository;
use App\BackendRepository\ImageRepository\DeleteImageRepository;
use Auth;

class BooksController extends Controller
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

            $books = Books::paginate($request->paginator, ['*'], 'page', $request->page);
            foreach ($books as $book) {
                $book_category = BookCategories::where('id', $book['book_category_id'])->get()->first();
                $book['book_category_name'] = $book_category['book_category_name'];
                $book['image_url'] = '/images/thumbnail/'.$book['image'];
            }
            if ($books) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $books
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
                'message' => "Failed to get books, please try again. {$exception->getMessage()}"
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
        $request->validate([
            'book_category_id' => 'required',
            'book_name' => 'required',
        ]);
        try {
            // $books = Books::create($request->all());
            $books = new Books;
            $books->uuid = $this->uuid->generateUuid('books');
            $books->book_category_id = $request['book_category_id'];
            $books->school_id = Auth::user()->id;
            $books->book_name = $request['book_name'];
            $books->image = $this->saveImage->saveImage($request);
            $books->grade = $request['grade'];
            $books->subject = $request['subject'];
            $books->save();

            return response([
                'status' => 'success',
                'code' => 1,
                'data' => $books
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to store books, please try again. {$exception->getMessage()}"
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
            $requestData = ['id','uuid','book_category_id','school_id','book_name','grade','subject','created_at','updated_at'];
            $books = Books::where(function ($q) use ($requestData, $searchQuery) {
                foreach ($requestData as $field)
                    $q->orWhere($field, 'like', "%{$searchQuery}%");
            })->paginate($request->paginator, ['*'], 'page', $request->page);
            if ($books) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $books
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
                'message' => "Failed to get books, please try again. {$exception->getMessage()}"
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
            $books = Books::where('id', '=', $id)->first();
            if ($books) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $books
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
                'message' => "Failed to get books data, please try again. {$exception->getMessage()}"
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

            $books = Books::find($id);
           $books->book_category_id = $input['book_category_id'];
           $books->school_id = Auth::user()->id;
           $books->book_name = $input['book_name'];
           if ($request->hasFile('image')) 
           {
                $this->deleteImage->deleteImage($books);
                $books->image = $this->saveImage->saveImage($request);
           }
           $books->grade = $input['grade'];
           $books->subject = $input['subject'];

            $res = $books->update();
            if ($res) {
                return response([
                    'status' => 'success',
                    'code' => 1,
                    'data' => $books
                ], 200);
            }
            return response([
                'status' => 'error',
                'code' => 0,
                'data' => "Failed to update books"
            ], 500);
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to update books, please try again. {$exception->getMessage()}"
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
            $res = Books::find($id)->delete();
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
                    'data' => "Failed to delete books"
                ], 500);
            }
        } catch (\Exception $exception) {
            return response([
                'status' => 'error',
                'code' => 0,
                'message' => "Failed to delete books, please try again. {$exception->getMessage()}"
            ], 500);
        }
    }

    public function filter_book(Request $request)
    {
        try
        {
            $book = Books::where('book_category_id', $request['book_category_id'])->get();
            if (count($book)>0) {
                return response([
                    'status' => 'success',
                    'data' => $book
                ], 200);
            }
            else
            {
                return response([
                    'status' => 'error',
                    'message' => "No book found"
                ], 200);
            }
        }
        catch(\Exception $exception)
        {
            return response([
                'status' => 'error',
                'message' => "Error, please try again. {$exception->getMessage()}"
            ]);
        }

    }
}

