<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::paginate(10);
        return view('books.index')->with('books', $books);
    }

    public function show($id) 
    {
        $book = Book::find($id);
        return view('books.show')->with('book', $book);
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $rules = [
            "title"=> "required",
            "author" => "required",
            "isbn" => "required|size:13",
            "stock" => "required|numeric|integer|gte:0",
            "price" => "required|numeric"
        ];

        $message = [
            'stock.gte' => 'The stock must be greater than or equal to 0',
        ];

        $request->validate($rules);

        // $book = new Book();
        // $book->title = $request->title;
        // $book->author = $request->author;
        // $book->isbn = $request->isbn;
        // $book->price = $request->price;
        // $book->stock = $request->stock;
        // $book->save();

        $book = Book::create($request->all());

        return redirect()->route("books.show", $book->id);
    }

    public function destroy(Request $request, $id)  
    {
        $book = Book::find($id);
        $book->delete();

        return redirect()->route("books.index");
    }

    public function search(Request $request)
    {
        $text = '%' . $request->search . '%';
        $books = Book::where('title', 'LIKE', $text)->paginate(10);; 
        return view('books.index')->with('books', $books);
    }
}