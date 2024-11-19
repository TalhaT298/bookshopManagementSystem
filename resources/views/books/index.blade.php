@extends('layout')

@section('content')

<p class="text-end">
    <a class="btn btn-dark" href="{{ route('books.create') }}">New Book</a>
</p>

<table class="table table-striped table-bordered">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Author</th>
        <th>Actions</th>
    </tr>

    @foreach ($books as $book)
    <tr>
        <td>{{$book->id}}</td>
        <td>{{$book->title}}</td>
        <td>{{$book->author}}</td>
        <td>
            <div class="d-flex align-items-center">
                <a href="{{ route('books.show', $book->id) }}" class="btn btn-primary btn-sm me-2">Details</a>
            
                <form method="post" action="{{ route('books.destroy', $book->id) }}" onsubmit="return confirm('Are you sure you want to delete this book?')" class="m-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
            
        </td>
    </tr>
    @endforeach
</table>

{{$books->links()}}

@endsection