<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BookPost;
use App\Book;
use App\User;
use App\Http\Controllers\Controller;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $books = Book::paginate(5);
            $count = $books->count();

            for($i = 0 ; $i < $count ; $i ++){

                $book_id = $books[$i]->id;
                $user_id = $books[$i]->user_id;

                $book = Book::find($book_id);
                $comments_count = $book -> users() -> count();
                $books[$i]['comments_count'] = $comments_count;

                $user = User::find($user_id);
                $user_name = $user -> name;
                $books[$i]['user_name'] = $user_name;

            };

            return $books;

        }catch(\Throwable $e){
            throw $e;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  BookPost  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookPost $request)
    {
        try{
            \DB::beginTransaction();
            $book = Book::make($request->all());
            $book -> saveOrFail();
            \DB::commit();
        }catch(\Throwable $e){
            \DB::rollback();
            throw $e;
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
        try{
            $book = Book::find($id);

            $user_id = $book -> user_id;

            $user = User::find($user_id);
            $user_name = $user -> name;
            $book['user_name'] = $user_name;

            return $book;

        }catch(\Throwable $e){
            throw $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  BookPost $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BookPost $request,$id)
    {
        try{
            \DB::beginTransaction();
            $book = Book::findOrFail($id);
            $book -> fill(['category' => $request->category,'title' => $request->title]);
            $book -> saveOrFail();
            \DB::commit();
            
        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            throw $e;
        }catch(\Throwable $e){
            \DB::rollback();
            \Log::error($e);
            throw $e;
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
            Book::destroy($id);
        } catch (\Throwable $e) {
            \Log::error($e);
            throw $e;
        }
    }

    //検索
    public function result(Request $request){
        $result_books = [];

        $search = $request->input('search');
        $query = Book::query();
    
        if (!empty($search)) {
            $query->where('title', 'LIKE', "%{$search}%")
            ->orWhere('category', 'LIKE', "%{$search}%");
        }
    
        $books = $query->get();

        $result_count = $books -> count();

        for($i = 0 ; $i < $result_count ; $i ++){

            $book_id = $books[$i]->id;
            $user_id = $books[$i]->user_id;

            $book = Book::find($book_id);
            $comments_count = $book -> users() -> count();
            $books[$i]['comments_count'] = $comments_count;

            $user = User::find($user_id);
            $user_name = $user -> name;
            $books[$i]['user_name'] = $user_name;

        };

        $result_books['data'] = $books;
        $result_books['result_count'] = $result_count;

        return $result_books;
    }
}
