<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Laravel Bookstore Application

This project is a Laravel-based application for managing a bookstore. It includes functionality for creating, displaying, searching, and deleting book records.

## Prerequisites

- PHP 8.x
- Composer
- A database (e.g., MySQL)

---

## Installation

### Step 1: Clone and Set Up the Project

```bash
composer create-project laravel/laravel laravel-bookstore
cd laravel-bookstore
```
### Step 2: Install Dependencies
```bash
composer install
```
### Step 3: Generate Application Key
```bash
php artisan key:generate
```
### Step 4: Run Database Migrations
```bash
php artisan migrate
```
### Step 5: Seed the Database
```bash
php artisan db:seed DatabaseSeeder
```
### Step 6: Start the Development Server
```bash
php artisan serve
```
## Models

### Book Model

The `Book` model represents the structure of a book record. It includes attributes such as title, author, ISBN, price, and stock.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "author",
        "isbn",
        "price",
        "stock",
    ];
}
```
## Controllers

### BookController

Handles CRUD operations for books.

```php
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
            "title" => "required",
            "author" => "required",
            "isbn" => "required|size:13",
            "stock" => "required|numeric|integer|gte:0",
            "price" => "required|numeric"
        ];

        $messages = [
            'stock.gte' => 'The stock must be greater than or equal to 0',
        ];

        $request->validate($rules, $messages);

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
        $books = Book::where('title', 'LIKE', $text)->paginate(10);
        return view('books.index')->with('books', $books);
    }
}
```
## Database

### Migration: Create `books` Table

Defines the schema for the `books` table.

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('author', 255);
            $table->string('isbn', 13);
            $table->smallInteger('stock')->default(0);
            $table->float('price', 8)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
```
## Factory

### BookFactory

Generates fake data for books to seed the database.

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence,
            'author' => fake()->name,
            'isbn' => fake()->isbn13(),
            'stock' => fake()->numberBetween(1, 10),
            'price' => fake()->randomFloat(2, 10, 100),
        ];
    }
}
```
## Seeder

### DatabaseSeeder

Seeds the database with initial data for books and users.

```php
<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Book::factory()->count(200)->create();
    }
}
```
