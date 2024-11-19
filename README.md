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

###  Clone and Set Up the Project

```bash
composer create-project laravel/laravel laravel-bookstore
cd laravel-bookstore
```
### (A)Install Dependencies
```bash
composer install
```
### Generate Application Key
```bash
php artisan key:generate
```
### Run Database Migrations
```bash
php artisan migrate
```
### Seed the Database
```bash
php artisan db:seed DatabaseSeeder
```
### (B) Step1: Start the Development Server
```bash
php artisan serve
```
## Set Up the Database

1. **Create a New Database**  
   Create a new database (e.g., `laravel_bookstore`) in your database server.

2. **Update the `.env` File**  
   Configure your database credentials in the `.env` file as follows:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel_bookstore
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```
### Step 2: Run Migrations

Run the following command to create the necessary tables in the database:

```bash
php artisan migrate
```
### Step 3: Seed the Database

Seed the database with dummy data by running the following command:

```bash
php artisan db:seed DatabaseSeeder
```
### Step 4: Start the Development Server

Start the Laravel development server using the following command:

```bash
php artisan serve
```
Your application will be available at:
```bash
http://127.0.0.1:8000
```
## (c)Application Structure

### Step 1: Create the `Book` Model, Controller, and Factory

1. Run the following command to generate the `Book` model, its controller, and the migration file:

```bash
php artisan make:model Book -cfm
```
### Step 2: Update the `Book` Model

In `app/Models/Book.php`, update the model to include the fillable attributes:

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
### Step 3: Update the Migration File

In `database/migrations/<timestamp>_create_books_table.php`, define the `books` table schema as follows:

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
### Step 4: Update the Migration File

In `database/migrations/<timestamp>_create_books_table.php`, define the `books` table schema as follows:

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
### Step 5: Seed the Database with Books

In `database/seeders/DatabaseSeeder.php`, update the file as follows:

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
### Run the Seeder

To populate the database with dummy data, execute the following command:

```bash
php artisan db:seed
```
### Step 6: Create the `BookController`

In `app/Http/Controllers/BookController.php`, update the file as follows:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    // Display a list of books with pagination
    public function index()
    {
        $books = Book::paginate(10);
        return view('books.index')->with('books', $books);
    }

    // Show details of a specific book
    public function show($id) 
    {
        $book = Book::find($id);
        return view('books.show')->with('book', $book);
    }

    // Display the form for creating a new book
    public function create()
    {
        return view('books.create');
    }

    // Store a newly created book in the database
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

    // Delete a book from the database
    public function destroy(Request $request, $id)  
    {
        $book = Book::find($id);
        $book->delete();

        return redirect()->route("books.index");
    }

    // Search for books based on the title
    public function search(Request $request)
    {
        $text = '%' . $request->search . '%';
        $books = Book::where('title', 'LIKE', $text)->paginate(10);
        return view('books.index')->with('books', $books);
    }
}
```

### Step 14: Define Routes

In `routes/web.php`, add the following route:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

// Route for listing all books
Route::get('/books', [BookController::class, 'index'])->name('books.index');

// Route for displaying a single book's details
Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');

// Route for showing the book creation form
Route::get('/books/create', [BookController::class, 'create'])->name('books.create');

// Route for storing a newly created book
Route::post('/books', [BookController::class, 'store'])->name('books.store');

// Route for deleting a book
Route::delete('/books/{id}', [BookController::class, 'destroy'])->name('books.destroy');

// Route for searching books by title
Route::get('/books/search', [BookController::class, 'search'])->name('books.search');
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
## Run the Seeder

To seed the database with the initial data for books and users, use the following command:

```bash
php artisan db:seed --class=DatabaseSeeder
```
