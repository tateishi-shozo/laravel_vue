<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BookPost;
use App\Book;
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
            //多分いらない
            // $books = DB::table('books')->paginate(5);
            // return $books;
            
            $books = Book::paginate(5);
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
        //
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
}
