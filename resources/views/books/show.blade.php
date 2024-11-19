@extends('layout')

@section('content')

<a href="{{ route('books.index') }}">Back</a>

<table class="table table-striped">
    <tr>
        <th>ID</th>
        <td>{{$book->id}}</td>
    </tr>
    <tr>
        <th>Title</th>
        <td>{{$book->title}}</td>
    </tr>
    <tr>
        <th>Author</th>
        <td>{{$book->author}}</td>
    </tr>
    <tr>
        <th>ISBN</th>
        <td>{{$book->isbn}}</td>
    </tr>
    <tr>
        <th>Price</th>
        <td>{{$book->price}}</td>
    </tr>
</table>

@endsection