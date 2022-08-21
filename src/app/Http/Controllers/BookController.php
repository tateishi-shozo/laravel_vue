<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BookPost;
use App\Book;
use App\Http\Controllers\Controller;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::paginate(3);

        return $books;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  BookPost  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookPost $request)
    {
        Book::create($request->all());
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
        $update = [
            'category' => $request->category,
            'read_flg' => $request->read_flg,
            'title' => $request->title,
            'evaluation' => $request->evaluation,
            'conclude' => $request->conclude,
            'image' => $request->image
        ];

        Book::where('id',$id)->update($update);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Book::where('id',$id)->delete();
    }
}
